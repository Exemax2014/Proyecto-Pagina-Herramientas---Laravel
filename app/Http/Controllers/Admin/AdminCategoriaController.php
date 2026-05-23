<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminCategoriaController extends Controller
{
    public function index()
    {
        $this->normalizarCategoriasInicio();

        $categorias = Categoria::withCount('productos')
            ->orderByDesc('mostrar_en_inicio')
            ->orderBy('orden_inicio')
            ->orderBy('nombre')
            ->paginate(12);

        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $datos = $this->validarCategoria($request);
        $datos['slug'] = $this->normalizarSlug($datos['nombre'], $datos['slug'] ?? null);
        $this->asegurarSlugValido($datos['slug']);
        $datos = $this->prepararDatosInicio($request, $datos);
        $this->asegurarCategoriaConProductosParaInicio(null, $datos['mostrar_en_inicio']);

        if ($request->hasFile('imagen')) {
            $datos['imagen_url'] = $this->guardarImagen($request->file('imagen'), $datos['slug']);
        }

        DB::transaction(function () use ($datos) {
            $this->normalizarCategoriasInicio();

            $categoria = Categoria::create($datos);

            $this->resolverEstadoInicio($categoria, false, null, $datos['mostrar_en_inicio'], $datos['orden_inicio']);
            $this->asegurarEstadoInicioValido();
        });

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoria creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        $categoria->loadCount('productos');

        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $estadoVisibleAnterior = (bool) $categoria->mostrar_en_inicio;
        $ordenAnterior = $categoria->orden_inicio;

        $datos = $this->validarCategoria($request, $categoria->id);
        $datos['slug'] = $this->normalizarSlug($datos['nombre'], $datos['slug'] ?? null);
        $this->asegurarSlugValido($datos['slug']);
        $datos = $this->prepararDatosInicio($request, $datos);
        $this->asegurarCategoriaConProductosParaInicio($categoria, $datos['mostrar_en_inicio']);

        $imagenAnterior = $categoria->imagen_url;

        if ($request->hasFile('imagen')) {
            $datos['imagen_url'] = $this->guardarImagen($request->file('imagen'), $datos['slug']);
        }

        DB::transaction(function () use ($categoria, $datos, $estadoVisibleAnterior, $ordenAnterior) {
            $this->normalizarCategoriasInicio();

            $categoria->update($datos);

            $this->resolverEstadoInicio($categoria, $estadoVisibleAnterior, $ordenAnterior, $datos['mostrar_en_inicio'], $datos['orden_inicio']);
            $this->asegurarEstadoInicioValido();
        });

        if ($request->hasFile('imagen') && $imagenAnterior !== $categoria->imagen_url) {
            $this->eliminarImagenSiCorresponde($imagenAnterior);
        }

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoria actualizada correctamente.');
    }

    private function validarCategoria(Request $request, ?int $categoriaId = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre,' . $categoriaId],
            'slug' => ['nullable', 'string', 'max:100', 'unique:categorias,slug,' . $categoriaId],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'mostrar_en_inicio' => ['nullable', 'boolean'],
            'orden_inicio' => ['nullable', 'integer', 'between:1,6'],
        ]);
    }

    private function normalizarSlug(string $nombre, ?string $slug): string
    {
        $base = filled($slug) ? $slug : $nombre;

        return Str::slug($base);
    }

    private function asegurarSlugValido(string $slug): void
    {
        if ($slug !== '') {
            return;
        }

        throw ValidationException::withMessages([
            'slug' => 'No se pudo generar un slug valido para esta categoria.',
        ]);
    }

    private function prepararDatosInicio(Request $request, array $datos): array
    {
        $datos['mostrar_en_inicio'] = $request->boolean('mostrar_en_inicio');
        $datos['orden_inicio'] = $datos['mostrar_en_inicio']
            ? ($datos['orden_inicio'] ?? null)
            : null;

        if ($datos['mostrar_en_inicio'] && $datos['orden_inicio'] === null) {
            throw ValidationException::withMessages([
                'orden_inicio' => 'Debes indicar un orden del 1 al 6 para mostrar la categoria en inicio.',
            ]);
        }

        return $datos;
    }

    private function resolverEstadoInicio(
        Categoria $categoria,
        bool $estadoVisibleAnterior,
        ?int $ordenAnterior,
        bool $mostrarEnInicio,
        ?int $ordenInicio
    ): void
    {
        if (! $mostrarEnInicio) {
            $categoria->update([
                'mostrar_en_inicio' => false,
                'orden_inicio' => null,
            ]);

            return;
        }

        $ocupante = Categoria::query()
            ->where('id', '!=', $categoria->id)
            ->where('mostrar_en_inicio', true)
            ->where('orden_inicio', $ordenInicio)
            ->first();

        if ($ocupante) {
            if ($estadoVisibleAnterior) {
                $ocupante->update([
                    'mostrar_en_inicio' => true,
                    'orden_inicio' => $ordenAnterior,
                ]);
            } else {
                $ocupante->update([
                    'mostrar_en_inicio' => false,
                    'orden_inicio' => null,
                ]);
            }
        }

        $categoria->update([
            'mostrar_en_inicio' => true,
            'orden_inicio' => $ordenInicio,
        ]);
    }

    private function guardarImagen($imagen, string $slug): string
    {
        $carpetaDestino = public_path('img/categorias');

        if (! file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        $nombreArchivo = $slug . '-' . uniqid() . '.' . $imagen->getClientOriginalExtension();
        $imagen->move($carpetaDestino, $nombreArchivo);

        return '/img/categorias/' . $nombreArchivo;
    }

    private function eliminarImagenSiCorresponde(?string $imagenUrl): void
    {
        if (! $imagenUrl) {
            return;
        }

        $directorioPermitido = public_path('img/categorias');
        $rutaFisica = public_path(ltrim($imagenUrl, '/'));

        $directorioReal = realpath($directorioPermitido);
        $rutaReal = file_exists($rutaFisica) ? realpath($rutaFisica) : false;

        if (! $directorioReal || ! $rutaReal) {
            return;
        }

        if (! str_starts_with($rutaReal, $directorioReal . DIRECTORY_SEPARATOR) && $rutaReal !== $directorioReal) {
            return;
        }

        unlink($rutaReal);
    }

    private function asegurarEstadoInicioValido(): void
    {
        $visibles = Categoria::query()
            ->where('mostrar_en_inicio', true)
            ->get(['id', 'orden_inicio']);

        if ($visibles->count() > 6) {
            throw ValidationException::withMessages([
                'mostrar_en_inicio' => 'No puede haber mas de 6 categorias visibles en inicio.',
            ]);
        }

        $ordenes = [];

        foreach ($visibles as $visible) {
            if ($visible->orden_inicio === null || $visible->orden_inicio < 1 || $visible->orden_inicio > 6) {
                throw ValidationException::withMessages([
                    'orden_inicio' => 'Las categorias visibles deben tener un orden valido del 1 al 6.',
                ]);
            }

            if (in_array($visible->orden_inicio, $ordenes, true)) {
                throw ValidationException::withMessages([
                    'orden_inicio' => 'No puede haber categorias visibles con orden duplicado.',
                ]);
            }

            $ordenes[] = $visible->orden_inicio;
        }
    }

    private function normalizarCategoriasInicio(): void
    {
        DB::transaction(function () {
            Categoria::query()
                ->where(function ($query) {
                    $query->where('mostrar_en_inicio', false)
                        ->whereNotNull('orden_inicio');
                })
                ->orWhere(function ($query) {
                    $query->where('mostrar_en_inicio', true)
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('orden_inicio')
                                ->orWhere('orden_inicio', '<', 1)
                                ->orWhere('orden_inicio', '>', 6);
                        });
                })
                ->update([
                    'mostrar_en_inicio' => false,
                    'orden_inicio' => null,
                ]);

            Categoria::query()
                ->where('mostrar_en_inicio', true)
                ->whereDoesntHave('productos')
                ->update([
                    'mostrar_en_inicio' => false,
                    'orden_inicio' => null,
                ]);

            $visibles = Categoria::query()
                ->where('mostrar_en_inicio', true)
                ->whereBetween('orden_inicio', [1, 6])
                ->orderBy('orden_inicio')
                ->orderBy('id')
                ->get(['id', 'orden_inicio']);

            $ordenesUsados = [];

            foreach ($visibles as $categoria) {
                if (! in_array($categoria->orden_inicio, $ordenesUsados, true)) {
                    $ordenesUsados[] = $categoria->orden_inicio;
                    continue;
                }

                Categoria::query()
                    ->where('id', $categoria->id)
                    ->update([
                        'mostrar_en_inicio' => false,
                        'orden_inicio' => null,
                    ]);
            }
        });
    }

    private function asegurarCategoriaConProductosParaInicio(?Categoria $categoria, bool $mostrarEnInicio): void
    {
        if (! $mostrarEnInicio) {
            return;
        }

        $cantidadProductos = $categoria
            ? $categoria->productos()->count()
            : 0;

        if ($cantidadProductos > 0) {
            return;
        }

        throw ValidationException::withMessages([
            'mostrar_en_inicio' => 'No se puede asignar esta categoría al inicio porque no tiene productos asociados. Cargue productos y luego asígnela a un lugar en la vista de inicio.',
        ]);
    }
}
