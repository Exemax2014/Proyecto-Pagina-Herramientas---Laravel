<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminMarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::withCount('productos')
            ->orderByDesc('mostrar_en_inicio')
            ->orderBy('orden_inicio')
            ->orderBy('nombre')
            ->get();

        $marcasInicio = [];

        foreach (range(1, 12) as $posicion) {
            $marcasInicio[$posicion] = optional(
                $marcas->firstWhere('orden_inicio', $posicion)
            )->id;
        }

        return view('admin.marcas.index', compact('marcas', 'marcasInicio'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'nombre_nueva_marca' => trim((string) $request->input('nombre_nueva_marca')),
        ]);

        $datos = $request->validate([
            'nombre_nueva_marca' => ['required', 'string', 'max:100', 'unique:marcas,nombre'],
        ], [
            'nombre_nueva_marca.unique' => 'Ya existe una marca con ese nombre.',
        ]);

        Marca::create([
            'nombre' => trim($datos['nombre_nueva_marca']),
        ]);

        return redirect()
            ->route('admin.marcas.index')
            ->with('success', 'Marca creada correctamente.');
    }

    public function update(Request $request, Marca $marca)
    {
        $request->merge([
            'nombre' => trim((string) $request->input('nombre')),
        ]);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:marcas,nombre,' . $marca->id],
        ], [
            'nombre.unique' => 'Ya existe una marca con ese nombre.',
        ]);

        $marca->update([
            'nombre' => trim($datos['nombre']),
        ]);

        return redirect()
            ->route('admin.marcas.index')
            ->with('success', 'Marca actualizada correctamente.');
    }

    public function updateHomeSelection(Request $request)
    {
        $datos = $request->validate([
            'marcas_inicio' => ['nullable', 'array'],
            'marcas_inicio.*' => ['nullable', 'integer', 'exists:marcas,id'],
        ]);

        $seleccion = collect($datos['marcas_inicio'] ?? [])
            ->filter(fn ($marcaId) => filled($marcaId))
            ->map(fn ($marcaId) => (int) $marcaId)
            ->values();

        if (! in_array($seleccion->count(), [6, 8, 12], true)) {
            throw ValidationException::withMessages([
                'marcas_inicio' => 'Debe seleccionar exactamente 6, 8 o 12 marcas para el inicio.',
            ]);
        }

        if ($seleccion->count() !== $seleccion->unique()->count()) {
            throw ValidationException::withMessages([
                'marcas_inicio' => 'No puede repetir una misma marca en más de una posición.',
            ]);
        }

        DB::transaction(function () use ($seleccion) {
            Marca::query()->update([
                'mostrar_en_inicio' => false,
                'orden_inicio' => null,
            ]);

            foreach ($seleccion as $posicion => $marcaId) {
                Marca::query()
                    ->where('id', $marcaId)
                    ->update([
                        'mostrar_en_inicio' => true,
                        'orden_inicio' => $posicion + 1,
                    ]);
            }
        });

        return redirect()
            ->route('admin.marcas.index')
            ->with('success', 'Marcas del inicio actualizadas correctamente.');
    }
}
