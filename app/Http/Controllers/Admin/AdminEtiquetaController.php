<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminEtiquetaController extends Controller
{
    public function index()
    {
        $etiquetas = Etiqueta::withCount('productos')
            ->orderByDesc('activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.etiquetas.index', compact('etiquetas'));
    }

    public function store(Request $request)
    {
        $datos = $this->validarEtiqueta($request);

        Etiqueta::create([
            'nombre' => $datos['nombre'],
            'slug' => Etiqueta::buildSlug($datos['nombre']),
            'color' => $datos['color'],
            'activo' => (bool) ($datos['activo'] ?? true),
        ]);

        return redirect()
            ->route('admin.etiquetas.index')
            ->with('success', 'Etiqueta creada correctamente.');
    }

    public function update(Request $request, Etiqueta $etiqueta)
    {
        $datos = $this->validarEtiqueta($request, $etiqueta);

        if ($etiqueta->slug === 'oferta' && strcasecmp($datos['nombre'], Etiqueta::OFERTA_NOMBRE) !== 0) {
            return redirect()
                ->route('admin.etiquetas.index')
                ->withErrors(['nombre' => 'La etiqueta Oferta es reservada y no puede cambiar de nombre.']);
        }

        $etiqueta->update([
            'nombre' => $datos['nombre'],
            'slug' => Etiqueta::buildSlug($datos['nombre']),
            'color' => $datos['color'],
            'activo' => (bool) ($datos['activo'] ?? true),
        ]);

        return redirect()
            ->route('admin.etiquetas.index')
            ->with('success', 'Etiqueta actualizada correctamente.');
    }

    private function validarEtiqueta(Request $request, ?Etiqueta $etiqueta = null): array
    {
        $request->merge([
            'nombre' => trim((string) $request->input('nombre')),
            'color' => trim((string) $request->input('color')),
        ]);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:etiquetas,nombre,' . ($etiqueta?->id ?? 'NULL') . ',id'],
            'color' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $slugObjetivo = Etiqueta::buildSlug($datos['nombre']);
        $slugDuplicado = Etiqueta::query()
            ->where('slug', $slugObjetivo)
            ->when($etiqueta, fn ($query) => $query->where('id', '!=', $etiqueta->id))
            ->exists();

        if ($slugDuplicado) {
            throw ValidationException::withMessages([
                'nombre' => 'Ya existe una etiqueta con ese nombre.',
            ]);
        }

        return $datos;
    }
}
