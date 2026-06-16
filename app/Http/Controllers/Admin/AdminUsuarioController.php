<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUsuarioController extends Controller
{
    private const ROOT_EMAIL = 'admin@hierroforja.com';

    public function index(Request $request)
    {
        $buscarAdmin = $request->input('buscar_admin');
        $buscarComprador = $request->input('buscar_comprador');

        $administradores = Usuario::query()
            ->with(['domicilios' => function ($query) {
                $query->orderByDesc('es_principal')->latest('id');
            }])
            ->where('role', 'admin')
            ->when($buscarAdmin, function ($query) use ($buscarAdmin) {
                $query->where(function ($q) use ($buscarAdmin) {
                    $q->where('nombre', 'like', "%{$buscarAdmin}%")
                        ->orWhere('apellido', 'like', "%{$buscarAdmin}%")
                        ->orWhere('email', 'like', "%{$buscarAdmin}%")
                        ->orWhere('dni', 'like', "%{$buscarAdmin}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(6, ['*'], 'admin_page')
            ->withQueryString();

        $compradores = Usuario::query()
            ->with(['domicilios' => function ($query) {
                $query->orderByDesc('es_principal')->latest('id');
            }])
            ->where('role', 'comprador')
            ->when($buscarComprador, function ($query) use ($buscarComprador) {
                $query->where(function ($q) use ($buscarComprador) {
                    $q->where('nombre', 'like', "%{$buscarComprador}%")
                        ->orWhere('apellido', 'like', "%{$buscarComprador}%")
                        ->orWhere('email', 'like', "%{$buscarComprador}%")
                        ->orWhere('dni', 'like', "%{$buscarComprador}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(6, ['*'], 'comprador_page')
            ->withQueryString();

        $esRoot = session('usuario_email') === self::ROOT_EMAIL;

        return view('admin.usuarios.index', compact(
            'administradores',
            'compradores',
            'buscarAdmin',
            'buscarComprador',
            'esRoot'
        ));
    }

    public function createAdmin()
    {
        return view('admin.usuarios.create-admin');
    }

    public function storeAdmin(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email'),
            ],
            'password' => ['required', 'string', 'min:4'],
            'dni' => ['required', 'string', 'max:20'],
            'telefono' => ['required', 'string', 'max:20'],
            'calle' => ['required', 'string', 'max:120'],
            'numero' => ['required', 'string', 'max:40'],
            'piso_departamento' => ['nullable', 'string', 'max:80'],
            'ciudad' => ['required', 'string', 'max:100'],
            'provincia' => ['required', 'string', 'max:100'],
            'codigo_postal' => ['required', 'string', 'max:20'],
            'referencia' => ['nullable', 'string', 'max:255'],
        ]);

        $usuario = Usuario::create([
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'dni' => $datos['dni'] ?? null,
            'telefono' => $datos['telefono'] ?? null,
            'role' => 'admin',
            'activo' => true,
        ]);

        $domicilioData = [
            'calle' => trim((string) ($datos['calle'] ?? '')),
            'numero' => trim((string) ($datos['numero'] ?? '')),
            'piso_departamento' => trim((string) ($datos['piso_departamento'] ?? '')),
            'ciudad' => trim((string) ($datos['ciudad'] ?? '')),
            'provincia' => trim((string) ($datos['provincia'] ?? '')),
            'codigo_postal' => trim((string) ($datos['codigo_postal'] ?? '')),
            'referencia' => trim((string) ($datos['referencia'] ?? '')),
        ];

        $domicilioActivo = $usuario->domicilios()
            ->where('activo', true)
            ->orderByDesc('es_principal')
            ->latest('id')
            ->first();

        if ($domicilioActivo) {
            $domicilioActivo->fill([
                'calle' => $domicilioData['calle'],
                'numero' => $domicilioData['numero'],
                'piso_departamento' => $domicilioData['piso_departamento'] ?: null,
                'ciudad' => $domicilioData['ciudad'],
                'provincia' => $domicilioData['provincia'],
                'codigo_postal' => $domicilioData['codigo_postal'] ?: null,
                'referencia' => $domicilioData['referencia'] ?: null,
                'es_principal' => true,
                'activo' => true,
            ])->save();
        } else {
            $domicilioActivo = $usuario->domicilios()->create([
                'calle' => $domicilioData['calle'],
                'numero' => $domicilioData['numero'],
                'piso_departamento' => $domicilioData['piso_departamento'] ?: null,
                'ciudad' => $domicilioData['ciudad'],
                'provincia' => $domicilioData['provincia'],
                'codigo_postal' => $domicilioData['codigo_postal'] ?: null,
                'referencia' => $domicilioData['referencia'] ?: null,
                'es_principal' => true,
                'activo' => true,
            ]);
        }

        $usuario->domicilios()
            ->where('id', '!=', $domicilioActivo->id)
            ->update([
                'activo' => false,
                'es_principal' => false,
            ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Administrador creado correctamente.');
    }

    public function activar(Usuario $usuario)
    {
        // Prevenir que un usuario se active/desactive a si mismo
        if ($usuario->id === session('usuario_id')) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('error', 'No podes modificar tu propio usuario.');
        }

        $usuario->update([
            'activo' => true,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario dado de alta correctamente.');
    }

    public function desactivar(Usuario $usuario)
    {
        // Prevenir que el root sea dado de baja
        if ($usuario->email === self::ROOT_EMAIL) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('error', 'El administrador root no puede ser dado de baja.');
        }

        // Prevenir que un usuario se desactive a si mismo
        if ($usuario->id === session('usuario_id')) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('error', 'No podes modificar tu propio usuario.');
        }

        $usuario->update([
            'activo' => false,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario dado de baja correctamente.');
    }
}
