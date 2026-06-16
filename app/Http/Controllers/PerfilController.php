<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    public function misDatos()
    {
        $usuario = Usuario::with(['domicilios' => function ($query) {
            $query->orderByDesc('es_principal')->latest('id')->limit(4);
        }])->findOrFail(session('usuario_id'));

        $domicilios = $usuario->domicilios->values();
        $domicilioPrincipal = $this->obtenerDomicilioPrincipal($usuario);
        $selectedDomicilioId = (int) old('selected_domicilio_id', $domicilioPrincipal?->id ?? 0);
        $domicilioMode = old('domicilio_mode', $domicilios->isNotEmpty() ? 'existing' : 'new');

        if ($domicilioMode === 'existing' && $selectedDomicilioId > 0) {
            $domicilioSeleccionado = $domicilios->firstWhere('id', $selectedDomicilioId) ?: $domicilioPrincipal;
        } else {
            $domicilioSeleccionado = $domicilioPrincipal;
        }

        $domicilioForm = $domicilioMode === 'new'
            ? $this->emptyDomicilioForm()
            : $this->armarDomicilioForm($domicilioSeleccionado);

        $domicilioForm = [
            'calle' => old('calle', $domicilioForm['calle']),
            'numero' => old('numero', $domicilioForm['numero']),
            'piso_departamento' => old('piso_departamento', $domicilioForm['piso_departamento']),
            'ciudad' => old('ciudad', $domicilioForm['ciudad']),
            'provincia' => old('provincia', $domicilioForm['provincia']),
            'codigo_postal' => old('codigo_postal', $domicilioForm['codigo_postal']),
            'referencia' => old('referencia', $domicilioForm['referencia']),
        ];

        $canAddDomicilio = $domicilios->count() < 4;

        return view('pages.mis-datos', compact(
            'usuario',
            'domicilios',
            'domicilioPrincipal',
            'domicilioSeleccionado',
            'selectedDomicilioId',
            'domicilioMode',
            'domicilioForm',
            'canAddDomicilio'
        ));
    }

    public function update(Request $request)
    {
        $usuario = Usuario::with(['domicilios' => function ($query) {
            $query->orderByDesc('es_principal')->latest('id');
        }])->findOrFail(session('usuario_id'));

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario->id),
            ],
            'dni' => ['nullable', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'domicilio_mode' => ['required', 'in:existing,new'],
            'selected_domicilio_id' => ['nullable', 'integer'],
            'calle' => ['required', 'string', 'max:120'],
            'numero' => ['required', 'string', 'max:40'],
            'piso_departamento' => ['nullable', 'string', 'max:80'],
            'ciudad' => ['required', 'string', 'max:100'],
            'provincia' => ['required', 'string', 'max:100'],
            'codigo_postal' => ['required', 'string', 'max:20'],
            'referencia' => ['nullable', 'string', 'max:255'],
        ]);

        $addressData = [
            'calle' => trim((string) ($datos['calle'] ?? '')),
            'numero' => trim((string) ($datos['numero'] ?? '')),
            'piso_departamento' => trim((string) ($datos['piso_departamento'] ?? '')),
            'ciudad' => trim((string) ($datos['ciudad'] ?? '')),
            'provincia' => trim((string) ($datos['provincia'] ?? '')),
            'codigo_postal' => trim((string) ($datos['codigo_postal'] ?? '')),
            'referencia' => trim((string) ($datos['referencia'] ?? '')),
        ];

        $usuario->fill([
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'email' => $datos['email'],
            'dni' => $datos['dni'],
            'telefono' => $datos['telefono'],
            'direccion' => trim($addressData['calle'] . ' ' . $addressData['numero']),
            'ciudad' => $addressData['ciudad'] ?: null,
            'provincia' => $addressData['provincia'] ?: null,
            'codigo_postal' => $addressData['codigo_postal'] ?: null,
        ]);
        $usuario->save();

        $selectedDomicilioId = (int) ($datos['selected_domicilio_id'] ?? 0);
        $domicilioMode = $datos['domicilio_mode'];
        $domicilioPrincipal = $this->obtenerDomicilioPrincipal($usuario);

        if ($domicilioMode === 'existing') {
            $domicilioSeleccionado = $usuario->domicilios->firstWhere('id', $selectedDomicilioId);

            if (! $domicilioSeleccionado) {
                return back()
                    ->withErrors(['domicilio' => 'Selecciona un domicilio valido para actualizar.'])
                    ->withInput();
            }
        } else {
            if ($usuario->domicilios->count() >= 4) {
                return back()
                    ->withErrors(['domicilio' => 'Maximo 4 domicilios registrados.'])
                    ->withInput();
            }

            $domicilioSeleccionado = $usuario->domicilios()->create([
                'calle' => '',
                'numero' => '',
                'piso_departamento' => null,
                'ciudad' => '',
                'provincia' => '',
                'codigo_postal' => null,
                'referencia' => null,
                'es_principal' => ! $domicilioPrincipal,
            ]);
        }

        $domicilioSeleccionado->fill([
            'calle' => $addressData['calle'],
            'numero' => $addressData['numero'],
            'piso_departamento' => $addressData['piso_departamento'] ?: null,
            'ciudad' => $addressData['ciudad'],
            'provincia' => $addressData['provincia'],
            'codigo_postal' => $addressData['codigo_postal'] ?: null,
            'referencia' => $addressData['referencia'] ?: null,
            'es_principal' => $domicilioSeleccionado->es_principal || ! $domicilioPrincipal,
        ])->save();

        session([
            'usuario_nombre' => $usuario->nombre,
            'usuario_email' => $usuario->email,
        ]);

        return redirect()
            ->route('mis-datos')
            ->with('success', 'Tus datos fueron actualizados correctamente.');
    }

    private function obtenerDomicilioPrincipal(Usuario $usuario): ?Domicilio
    {
        return $usuario->domicilios->firstWhere('es_principal', true)
            ?: $usuario->domicilios->first();
    }

    private function armarDomicilioForm(?Domicilio $domicilio): array
    {
        return [
            'calle' => $domicilio?->calle ?: '',
            'numero' => $domicilio?->numero ?: '',
            'piso_departamento' => $domicilio?->piso_departamento ?: '',
            'ciudad' => $domicilio?->ciudad ?: '',
            'provincia' => $domicilio?->provincia ?: '',
            'codigo_postal' => $domicilio?->codigo_postal ?: '',
            'referencia' => $domicilio?->referencia ?: '',
        ];
    }

    private function emptyDomicilioForm(): array
    {
        return [
            'calle' => '',
            'numero' => '',
            'piso_departamento' => '',
            'ciudad' => '',
            'provincia' => '',
            'codigo_postal' => '',
            'referencia' => '',
        ];
    }
}
