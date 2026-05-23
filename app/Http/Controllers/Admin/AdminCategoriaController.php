<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminCategoriaController extends Controller
{
    public function index()
    {
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
        $datos['mostrar_en_inicio'] = $request->boolean('mostrar_en_inicio');
        $datos['orden_inicio'] = $datos['orden_inicio'] ?? null;

        $this->asegurarMinimoCategoriasVisibles($datos['mostrar_en_inicio']);

        if ($request->hasFile('imagen')) {
            $datos['imagen_url'] = $this->guardarImagen($request->file('imagen'), $datos['slug']);
        }

        Categoria::create($datos);

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
        $datos = $this->validarCategoria($request, $categoria->id);
        $datos['slug'] = $this->normalizarSlug($datos['nombre'], $datos['slug'] ?? null);
        $this->asegurarSlugValido($datos['slug']);
        $datos['mostrar_en_inicio'] = $request->boolean('mostrar_en_inicio');
        $datos['orden_inicio'] = $datos['orden_inicio'] ?? null;

        $this->asegurarMinimoCategoriasVisibles($datos['mostrar_en_inicio'], $categoria);

        $imagenAnterior = $categoria->imagen_url;

        if ($request->boolean('eliminar_imagen')) {
            $datos['imagen_url'] = null;
        }

        if ($request->hasFile('imagen')) {
            $datos['imagen_url'] = $this->guardarImagen($request->file('imagen'), $datos['slug']);
        }

        $categoria->update($datos);

        if (($request->boolean('eliminar_imagen') || $request->hasFile('imagen')) && $imagenAnterior !== $categoria->imagen_url) {
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
            'orden_inicio' => ['nullable', 'integer', 'min:1'],
            'eliminar_imagen' => ['nullable', 'boolean'],
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

    private function asegurarMinimoCategoriasVisibles(bool $mostrarEnInicio, ?Categoria $categoria = null): void
    {
        $totalCategoriasDespues = Categoria::count() + ($categoria ? 0 : 1);

        if ($totalCategoriasDespues < 6) {
            return;
        }

        $visiblesBase = Categoria::query()
            ->when($categoria, function ($query) use ($categoria) {
                $query->where('id', '!=', $categoria->id);
            })
            ->where('mostrar_en_inicio', true)
            ->count();

        $visiblesDespues = $visiblesBase + ($mostrarEnInicio ? 1 : 0);

        if ($visiblesDespues >= 6) {
            return;
        }

        throw ValidationException::withMessages([
            'mostrar_en_inicio' => 'Debe haber al menos 6 categorias visibles en inicio.',
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
}
