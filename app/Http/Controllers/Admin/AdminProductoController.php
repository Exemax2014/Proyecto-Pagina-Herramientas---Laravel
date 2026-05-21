<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $productos = Producto::with(['categoria', 'marca', 'imagenPrincipal'])
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

        return view('admin.productos.create', compact('categorias', 'marcas'));
    }

    public function store(Request $request)
    {
        $datos = $this->validarProducto($request, false);

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

        return view('admin.productos.edit', compact('producto', 'categorias', 'marcas'));
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $this->validarProducto($request, true);

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

    private function validarProducto(Request $request, bool $esEdicion): array
    {
        $reglas = [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'marca_nombre' => ['required', 'string', 'max:255'],
            'energia' => ['required', 'in:electrica,manual,inalambrica'],
            'activo' => ['required', 'boolean'],
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_anterior' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'ventas' => [$esEdicion ? 'required' : 'nullable', 'integer', 'min:0'],
            'etiqueta' => ['nullable', 'string', 'max:100'],
            'etiqueta_clase' => ['nullable', 'string', 'max:150'],
            'imagenes' => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];

        if ($esEdicion) {
            $reglas['imagen_principal_id'] = ['nullable', 'integer', 'exists:producto_imagenes,id'];
        }

        return $request->validate($reglas);
    }

    private function guardarDatosProducto(Producto $producto, array $datos): void
    {
        $marca = Marca::firstOrCreate(
            ['nombre' => trim($datos['marca_nombre'])],
            ['logo_url' => null]
        );

        $producto->fill([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'],
            'precio' => $datos['precio'],
            'precio_anterior' => $datos['precio_anterior'] ?? null,
            'stock' => $datos['stock'],
            'ventas' => $datos['ventas'] ?? 0,
            'energia' => $datos['energia'],
            'etiqueta' => $datos['etiqueta'] ?? null,
            'etiqueta_clase' => $datos['etiqueta_clase'] ?? null,
            'activo' => $datos['activo'],
            'categoria_id' => $datos['categoria_id'],
            'marca_id' => $marca->id,
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

    private function sincronizarImagenesExistentes(Producto $producto, array $datos): void
    {
        $imagenesEliminar = $datos['imagenes_eliminar'] ?? [];

        if (! empty($imagenesEliminar)) {
            $imagenes = ProductoImagen::where('producto_id', $producto->id)
                ->whereIn('id', $imagenesEliminar)
                ->get();

            foreach ($imagenes as $imagen) {
                $rutaFisica = public_path(ltrim($imagen->url, '/'));

                if (file_exists($rutaFisica)) {
                    unlink($rutaFisica);
                }

                $imagen->delete();
            }
        }

        $ordenes = $datos['imagenes_orden'] ?? [];

        foreach ($ordenes as $imagenId => $orden) {
            ProductoImagen::where('producto_id', $producto->id)
                ->where('id', $imagenId)
                ->update([
                    'orden' => $orden,
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
        $productos = Producto::with(['categoria', 'marca', 'imagenes'])
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
                    'energia' => $producto->energia,
                    'precio' => (float) $producto->precio,
                    'precio_anterior' => $producto->precio_anterior !== null
                        ? (float) $producto->precio_anterior
                        : null,
                    'stock' => (int) $producto->stock,
                    'ventas' => (int) $producto->ventas,
                    'descripcion' => $producto->descripcion,
                    'etiqueta' => $producto->etiqueta,
                    'etiqueta_clase' => $producto->etiqueta_clase,
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
}