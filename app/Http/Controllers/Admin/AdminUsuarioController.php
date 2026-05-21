<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminUsuarioController extends Controller
{
    private const ROOT_EMAIL = 'admin@hierroforja.com';

    public function index(Request $request)
    {
        $buscarAdmin = $request->input('buscar_admin');
        $buscarComprador = $request->input('buscar_comprador');

        $administradores = Usuario::query()
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
            'dni' => ['nullable', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'provincia' => ['nullable', 'string', 'max:100'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],
        ]);

        Usuario::create([
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'dni' => $datos['dni'] ?? null,
            'telefono' => $datos['telefono'] ?? null,
            'direccion' => $datos['direccion'] ?? null,
            'ciudad' => $datos['ciudad'] ?? null,
            'provincia' => $datos['provincia'] ?? null,
            'codigo_postal' => $datos['codigo_postal'] ?? null,
            'role' => 'admin',
            'activo' => true,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Administrador creado correctamente.');
    }

    public function edit(Usuario $usuario)
    {
        $esMismoUsuario = session('usuario_id') == $usuario->id;

        if ($usuario->role !== 'admin' || ! $esMismoUsuario) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('error', 'Solo podés modificar los datos del administrador conectado.');
        }

        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $esMismoUsuario = session('usuario_id') == $usuario->id;

        if ($usuario->role !== 'admin' || ! $esMismoUsuario) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('error', 'Solo podés modificar los datos del administrador conectado.');
        }

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
            'direccion' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'provincia' => ['nullable', 'string', 'max:100'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $usuario->fill([
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'email' => $datos['email'],
            'dni' => $datos['dni'] ?? null,
            'telefono' => $datos['telefono'] ?? null,
            'direccion' => $datos['direccion'] ?? null,
            'ciudad' => $datos['ciudad'] ?? null,
            'provincia' => $datos['provincia'] ?? null,
            'codigo_postal' => $datos['codigo_postal'] ?? null,
        ]);

        if (! empty($datos['password'])) {
            $usuario->password = $datos['password'];
        }

        $usuario->save();

        session([
            'usuario_nombre' => $usuario->nombre,
            'usuario_email' => $usuario->email,
            'usuario_role' => $usuario->role,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Tus datos fueron actualizados correctamente.');
    }

    public function activar(Usuario $usuario)
    {
        $usuario->update([
            'activo' => true,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario dado de alta correctamente.');
    }

    public function desactivar(Usuario $usuario)
    {
        if ($usuario->email === self::ROOT_EMAIL) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('error', 'El administrador root no puede ser dado de baja.');
        }

        $usuario->update([
            'activo' => false,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario dado de baja correctamente.');
    }
}