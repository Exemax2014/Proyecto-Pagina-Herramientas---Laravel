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
        $usuario = Usuario::findOrFail(session('usuario_id'));
        $isAdmin = $this->esAdmin($usuario);

        $usuario->load(['domicilios' => function ($query) use ($isAdmin) {
            $query->where('activo', true)->orderByDesc('es_principal')->latest('id');

            if (! $isAdmin) {
                $query->limit(4);
            }
        }]);

        $domicilios = $usuario->domicilios->values();
        $domicilioPrincipal = $this->obtenerDomicilioPrincipal($usuario);

        if ($isAdmin) {
            $domicilios = collect(array_filter([$domicilioPrincipal]))->values();
        }

        $selectedDomicilioId = (int) old('selected_domicilio_id', $domicilioPrincipal?->id ?? 0);
        $domicilioMode = old('domicilio_mode', $domicilios->isNotEmpty() ? 'existing' : 'new');

        if ($isAdmin && $domicilios->isNotEmpty()) {
            $domicilioMode = 'existing';
            $selectedDomicilioId = (int) ($domicilioPrincipal?->id ?? 0);
        }

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

        $canAddDomicilio = ! $isAdmin && $domicilios->count() < 4;
        $canDeleteDomicilio = ! $isAdmin && $domicilios->count() > 0;

        return view('pages.mis-datos', compact(
            'usuario',
            'domicilios',
            'domicilioPrincipal',
            'domicilioSeleccionado',
            'selectedDomicilioId',
            'domicilioMode',
            'domicilioForm',
            'canAddDomicilio',
            'canDeleteDomicilio',
            'isAdmin'
        ));
    }

    public function update(Request $request)
    {
        $usuario = Usuario::with(['domicilios' => function ($query) {
            $query->where('activo', true)->orderByDesc('es_principal')->latest('id');
        }])->findOrFail(session('usuario_id'));

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-\']+$/u'],
            'apellido' => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-\']+$/u'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario->id),
            ],
            'dni' => ['required', 'string', 'max:10', 'regex:/^\d{6,10}$/'],
            'telefono' => ['required', 'string', 'max:30', 'regex:/^[0-9+\s\-()\.]+$/'],
            'domicilio_mode' => ['required', 'in:existing,new'],
            'selected_domicilio_id' => ['nullable', 'integer', Rule::exists('domicilios', 'id')->where(function ($query) use ($usuario) {
                $query->where('usuario_id', $usuario->id)->where('activo', true);
            })],
            'calle' => ['required', 'string', 'max:120', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-\']+$/u'],
            'numero' => ['required', 'string', 'max:40', 'regex:/^[A-Za-z0-9\s\/\-]+$/'],
            'piso_departamento' => ['nullable', 'string', 'max:80'],
            'ciudad' => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-\']+$/u'],
            'provincia' => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-\']+$/u'],
            'codigo_postal' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\s\-]+$/'],
            'referencia' => ['nullable', 'string', 'max:255'],
        ], [
            'nombre.required' => 'Debes ingresar tu nombre.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios, guiones y apóstrofos.',
            'apellido.required' => 'Debes ingresar tu apellido.',
            'apellido.regex' => 'El apellido solo puede contener letras, espacios, guiones y apóstrofos.',
            'email.required' => 'Debes ingresar un correo.',
            'email.email' => 'Debes ingresar un correo válido.',
            'email.max' => 'El correo no puede superar los 255 caracteres.',
            'dni.required' => 'Debes ingresar tu DNI.',
            'dni.regex' => 'El DNI debe tener entre 6 y 10 dígitos.',
            'telefono.required' => 'Debes ingresar un teléfono.',
            'telefono.regex' => 'El teléfono contiene caracteres inválidos.',
            'domicilio_mode.required' => 'Debes seleccionar el modo de domicilio.',
            'domicilio_mode.in' => 'Modo de domicilio inválido.',
            'selected_domicilio_id.exists' => 'Selecciona un domicilio válido.',
            'calle.required' => 'Debes ingresar la calle.',
            'calle.regex' => 'La calle contiene caracteres inválidos.',
            'numero.required' => 'Debes ingresar el número.',
            'numero.regex' => 'El número de domicilio contiene caracteres inválidos.',
            'ciudad.required' => 'Debes ingresar la ciudad.',
            'ciudad.regex' => 'La ciudad contiene caracteres inválidos.',
            'provincia.required' => 'Debes ingresar la provincia.',
            'provincia.regex' => 'La provincia contiene caracteres inválidos.',
            'codigo_postal.required' => 'Debes ingresar el código postal.',
            'codigo_postal.regex' => 'El código postal contiene caracteres inválidos.',
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
        ]);
        $usuario->save();

        $selectedDomicilioId = (int) ($datos['selected_domicilio_id'] ?? 0);
        $domicilioMode = $datos['domicilio_mode'];
        $domicilioPrincipal = $this->obtenerDomicilioPrincipal($usuario);
        $isAdmin = $this->esAdmin($usuario);

        if ($isAdmin && $usuario->domicilios->isNotEmpty()) {
            $domicilioMode = 'existing';
            $selectedDomicilioId = (int) ($domicilioPrincipal?->id ?? $usuario->domicilios->first()->id);
        }

        if ($domicilioMode === 'existing') {
            $domicilioSeleccionado = $usuario->domicilios->firstWhere('id', $selectedDomicilioId);

            if (! $domicilioSeleccionado) {
                return back()
                    ->withErrors(['domicilio' => 'Selecciona un domicilio valido para actualizar.'])
                    ->withInput();
            }
        } else {
            if ($isAdmin && $usuario->domicilios->isNotEmpty()) {
                return back()
                    ->withErrors(['domicilio' => 'Los administradores solo pueden tener un domicilio activo.'])
                    ->withInput();
            }

            if (! $isAdmin && $usuario->domicilios->count() >= 4) {
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
                'activo' => true,
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
            'activo' => true,
        ])->save();

        $usuario->load(['domicilios' => function ($query) {
            $query->where('activo', true)->orderByDesc('es_principal')->latest('id');
        }]);

        session([
            'usuario_nombre' => $usuario->nombre,
            'usuario_email' => $usuario->email,
        ]);

        return redirect()
            ->route('mis-datos')
            ->with('success', 'Tus datos fueron actualizados correctamente.');
    }

    public function bajaDomicilio(Domicilio $domicilio)
    {
        $usuarioId = (int) session('usuario_id');
        $usuario = Usuario::with(['domicilios' => function ($query) {
            $query->where('activo', true)->orderByDesc('es_principal')->latest('id');
        }])->findOrFail($usuarioId);

        abort_if($domicilio->usuario_id !== $usuarioId, 404);

        if ($this->esAdmin($usuario)) {
            return redirect()
                ->route('mis-datos')
                ->with('warning', 'Los administradores deben mantener un unico domicilio activo.');
        }

        if (! $domicilio->activo) {
            return redirect()
                ->route('mis-datos')
                ->with('warning', 'Ese domicilio ya estaba dado de baja.');
        }

        $eraPrincipal = (bool) $domicilio->es_principal;

        $domicilio->forceFill([
            'activo' => false,
            'es_principal' => false,
        ])->save();

        if ($eraPrincipal) {
            $nuevoPrincipal = Domicilio::query()
                ->where('usuario_id', $usuarioId)
                ->where('activo', true)
                ->orderByDesc('es_principal')
                ->latest('id')
                ->first();

            if ($nuevoPrincipal) {
                $nuevoPrincipal->forceFill(['es_principal' => true])->save();
            }
        }

        return redirect()
            ->route('mis-datos')
            ->with('success', 'El domicilio fue dado de baja correctamente.');
    }

    private function obtenerDomicilioPrincipal(Usuario $usuario): ?Domicilio
    {
        return $usuario->domicilioPrincipal();
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

    private function esAdmin(Usuario $usuario): bool
    {
        return $usuario->role === 'admin';
    }
}
