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
            ->paginate(10)
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
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'marca_nombre' => ['required', 'string', 'max:255'],
            'energia' => ['required', 'in:electrica,manual,inalambrica'],
            'activo' => ['required', 'boolean'],
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_anterior' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'ventas' => ['nullable', 'integer', 'min:0'],
            'etiqueta' => ['nullable', 'string', 'max:100'],
            'etiqueta_clase' => ['nullable', 'string', 'max:150'],
            'imagenes' => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $datos) {
            $marca = Marca::firstOrCreate(
                ['nombre' => trim($datos['marca_nombre'])],
                ['logo_url' => null]
            );
            $marcaId = $marca->id;

            $producto = Producto::create([
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
                'marca_id' => $marcaId,
            ]);

            if ($request->hasFile('imagenes')) {
                $carpetaDestino = public_path('img/productos');

                if (! file_exists($carpetaDestino)) {
                    mkdir($carpetaDestino, 0755, true);
                }

                foreach ($request->file('imagenes') as $index => $imagen) {
                    $nombreArchivo = Str::slug($producto->nombre) . '-' . uniqid() . '.' . $imagen->getClientOriginalExtension();

                    $imagen->move($carpetaDestino, $nombreArchivo);

                    ProductoImagen::create([
                        'producto_id' => $producto->id,
                        'url' => '/img/productos/' . $nombreArchivo,
                        'orden' => $index,
                        'es_principal' => $index === 0,
                    ]);
                }
            }
        });

        $this->exportarProductosJson();

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
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