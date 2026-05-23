<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminCategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('productos')
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

        $categoria->update($datos);

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoria actualizada correctamente.');
    }

    private function validarCategoria(Request $request, ?int $categoriaId = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre,' . $categoriaId],
            'slug' => ['nullable', 'string', 'max:100', 'unique:categorias,slug,' . $categoriaId],
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
}
