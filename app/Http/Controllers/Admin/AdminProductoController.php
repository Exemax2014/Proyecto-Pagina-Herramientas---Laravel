<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Etiqueta;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminProductoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $with = ['categoria', 'marca', 'imagenPrincipal'];

        if (Producto::etiquetasDisponibles()) {
            $with[] = 'etiquetaManual';
        }

        $productos = Producto::with($with)
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhereHas('categoria', function ($q) use ($buscar) {
                        $q->where('nombre', 'like', "%{$buscar}%");
                    })
                    ->orWhereHas('marca', function ($q) use ($buscar) {
                        $q->where('nombre', 'like', "%{$buscar}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('admin.productos.index', compact('productos', 'buscar'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $etiquetas = $this->obtenerEtiquetasManualesDisponibles();

        return view('admin.productos.create', compact('categorias', 'marcas', 'etiquetas'));
    }

    public function store(Request $request)
    {
        $datos = $this->validarProducto($request, false);
        $datos['activo'] = true;

        DB::transaction(function () use ($request, $datos) {
            $producto = new Producto();

            $this->guardarDatosProducto($producto, $datos);
            $this->guardarImagenesNuevas($request, $producto);
            $this->recalcularImagenPrincipal($producto);
        });

        $this->exportarProductosJson();

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $producto->load(['categoria', 'marca', 'imagenes']);

        $categorias = Categoria::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $etiquetas = $this->obtenerEtiquetasManualesDisponibles();

        return view('admin.productos.edit', compact('producto', 'categorias', 'marcas', 'etiquetas'));
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $this->validarProducto($request, true, $producto);

        DB::transaction(function () use ($request, $datos, $producto) {
            $this->guardarDatosProducto($producto, $datos);

            $imagenesEliminar = $datos['imagenes_eliminar'] ?? [];

            $this->eliminarImagenesExistentes($producto, $imagenesEliminar);

            $this->guardarImagenesNuevas($request, $producto);

            $imagenPrincipalId = ! empty($datos['imagen_principal_id'])
                ? (int) $datos['imagen_principal_id']
                : null;

            $imagenPrincipalFueEliminada = $imagenPrincipalId
                && in_array($imagenPrincipalId, array_map('intval', $imagenesEliminar), true);

            if ($imagenPrincipalId && ! $imagenPrincipalFueEliminada) {
                $this->marcarImagenPrincipal($producto, $imagenPrincipalId);
            } else {
                $this->recalcularImagenPrincipal($producto);
            }
        });

        $this->exportarProductosJson();

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function desactivar(Producto $producto)
    {
        $producto->update([
            'activo' => false,
        ]);

        $this->exportarProductosJson();

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto dado de baja correctamente.');
    }

    public function activar(Producto $producto)
    {
        $producto->update([
            'activo' => true,
        ]);

        $this->exportarProductosJson();

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto dado de alta correctamente.');
    }

    private function validarProducto(Request $request, bool $esEdicion, ?Producto $producto = null): array
    {
        $reglas = [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'marca_id' => ['required', 'exists:marcas,id'],
            // 'energia' removed: not a fixed product attribute
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_anterior' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'ventas' => [$esEdicion ? 'required' : 'nullable', 'integer', 'min:0'],
            'etiqueta_id' => Producto::etiquetasDisponibles()
                ? ['nullable', 'exists:etiquetas,id']
                : ['nullable'],
            'imagenes' => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];

        if ($esEdicion) {
            $reglas['activo'] = ['required', 'boolean'];
            $reglas['imagen_principal_id'] = ['nullable', 'integer', 'exists:producto_imagenes,id'];
        }

        $datos = $request->validate($reglas);

        $this->validarPrecioAnterior($datos);

        if ($esEdicion && $producto) {
            $imagenPrincipalId = ! empty($datos['imagen_principal_id'])
                ? (int) $datos['imagen_principal_id']
                : null;

            $this->validarImagenPrincipalPerteneceAlProducto($imagenPrincipalId, $producto);
        }

        return $datos;
    }

    private function guardarDatosProducto(Producto $producto, array $datos): void
    {
        $producto->fill([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'],
            'precio' => $datos['precio'],
            'precio_anterior' => $datos['precio_anterior'] ?? null,
            'stock' => $datos['stock'],
            'ventas' => $datos['ventas'] ?? 0,
            // 'energia' removed: not stored anymore
            'etiqueta_id' => $this->normalizarEtiquetaManualId($datos['etiqueta_id'] ?? null),
            'etiqueta' => $this->resolverEtiquetaNombre($datos['etiqueta_id'] ?? null),
            'etiqueta_clase' => null,
            'activo' => $datos['activo'],
            'categoria_id' => $datos['categoria_id'],
            'marca_id' => $datos['marca_id'],
        ]);

        $producto->save();
    }

    private function guardarImagenesNuevas(Request $request, Producto $producto): void
    {
        if (! $request->hasFile('imagenes')) {
            return;
        }

        $carpetaDestino = public_path('img/productos');

        if (! file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        $ultimoOrden = $producto->imagenes()->max('orden') ?? -1;

        foreach ($request->file('imagenes') as $index => $imagen) {
            $nombreArchivo = Str::slug($producto->nombre) . '-' . uniqid() . '.' . $imagen->getClientOriginalExtension();

            $imagen->move($carpetaDestino, $nombreArchivo);

            ProductoImagen::create([
                'producto_id' => $producto->id,
                'url' => '/img/productos/' . $nombreArchivo,
                'orden' => $ultimoOrden + $index + 1,
                'es_principal' => false,
            ]);
        }
    }

    private function recalcularImagenPrincipal(Producto $producto): void
    {
        $imagenes = $producto->imagenes()
            ->orderBy('orden')
            ->orderBy('id')
            ->get();

        if ($imagenes->isEmpty()) {
            return;
        }

        $producto->imagenes()->update([
            'es_principal' => false,
        ]);

        $imagenes->first()->update([
            'es_principal' => true,
        ]);
    }

    private function marcarImagenPrincipal(Producto $producto, int $imagenId): void
    {
        $imagenPerteneceAlProducto = $producto->imagenes()
            ->where('id', $imagenId)
            ->exists();

        if (! $imagenPerteneceAlProducto) {
            return;
        }

        $producto->imagenes()->update([
            'es_principal' => false,
        ]);

        $producto->imagenes()
            ->where('id', $imagenId)
            ->update([
                'es_principal' => true,
            ]);
    }

    private function eliminarImagenesExistentes(Producto $producto, array $imagenesIds): void
    {
        if (empty($imagenesIds)) {
            return;
        }

        $imagenes = ProductoImagen::where('producto_id', $producto->id)
            ->whereIn('id', $imagenesIds)
            ->get();

        foreach ($imagenes as $imagen) {
            $rutaFisica = public_path(ltrim($imagen->url, '/'));

            if (file_exists($rutaFisica)) {
                unlink($rutaFisica);
            }

            $imagen->delete();
        }
    }

    private function exportarProductosJson(): void
    {
        $with = ['categoria', 'marca', 'imagenes'];

        if (Producto::etiquetasDisponibles()) {
            $with[] = 'etiquetaManual';
        }

        $productos = Producto::with($with)
            ->orderBy('id')
            ->get()
            ->map(function ($producto) {
                return [
                    'nombre' => $producto->nombre,
                    'categoria' => [
                        'nombre' => $producto->categoria?->nombre,
                        'slug' => $producto->categoria?->slug,
                    ],
                    'marca' => [
                        'nombre' => $producto->marca?->nombre,
                        'logo_url' => $producto->marca?->logo_url,
                    ],
                    // 'energia' removed from export
                    'precio' => (float) $producto->precio,
                    'precio_anterior' => $producto->precio_anterior !== null
                        ? (float) $producto->precio_anterior
                        : null,
                    'stock' => (int) $producto->stock,
                    'ventas' => (int) $producto->ventas,
                    'descripcion' => $producto->descripcion,
                    'etiqueta' => $producto->etiqueta_manual_nombre,
                    'etiqueta_clase' => null,
                    'activo' => (bool) $producto->activo,
                    'imagenes' => $producto->imagenes
                        ->sortBy('orden')
                        ->map(function ($imagen) {
                            return [
                                'url' => $imagen->url,
                                'orden' => (int) $imagen->orden,
                                'es_principal' => (bool) $imagen->es_principal,
                            ];
                        })
                        ->values()
                        ->toArray(),
                ];
            })
            ->values()
            ->toArray();

        file_put_contents(
            database_path('data/productos.json'),
            json_encode($productos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    private function validarPrecioAnterior(array $datos): void
    {
        if (! array_key_exists('precio_anterior', $datos) || $datos['precio_anterior'] === null || $datos['precio_anterior'] === '') {
            return;
        }

        if ((float) $datos['precio_anterior'] > (float) $datos['precio']) {
            return;
        }

        throw ValidationException::withMessages([
            'precio_anterior' => 'El precio anterior debe ser mayor que el precio actual.',
        ]);
    }

    private function validarImagenPrincipalPerteneceAlProducto(?int $imagenPrincipalId, Producto $producto): void
    {
        if (! $imagenPrincipalId) {
            return;
        }

        $perteneceAlProducto = $producto->imagenes()
            ->where('id', $imagenPrincipalId)
            ->exists();

        if ($perteneceAlProducto) {
            return;
        }

        throw ValidationException::withMessages([
            'imagen_principal_id' => 'La imagen principal seleccionada no pertenece a este producto.',
        ]);
    }

    private function resolverEtiquetaNombre(?int $etiquetaId): ?string
    {
        $etiquetaId = $this->normalizarEtiquetaManualId($etiquetaId);

        if (! $etiquetaId || ! Producto::etiquetasDisponibles()) {
            return null;
        }

        return Etiqueta::query()->where('id', $etiquetaId)->value('nombre');
    }

    private function obtenerEtiquetasManualesDisponibles()
    {
        if (! Producto::etiquetasDisponibles()) {
            return collect();
        }

        return Etiqueta::query()
            ->where('activo', true)
            ->where('slug', '!=', Etiqueta::buildSlug(Etiqueta::OFERTA_NOMBRE))
            ->orderBy('nombre')
            ->get();
    }

    private function normalizarEtiquetaManualId($etiquetaId): ?int
    {
        if (! Producto::etiquetasDisponibles() || ! $etiquetaId) {
            return null;
        }

        $etiquetaId = (int) $etiquetaId;

        $esOferta = Etiqueta::query()
            ->where('id', $etiquetaId)
            ->where('slug', Etiqueta::buildSlug(Etiqueta::OFERTA_NOMBRE))
            ->exists();

        return $esOferta ? null : $etiquetaId;
    }
}
