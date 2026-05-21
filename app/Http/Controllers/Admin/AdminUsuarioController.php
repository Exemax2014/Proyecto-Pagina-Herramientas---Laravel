<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AdminUsuarioController extends Controller
{
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

        $esRoot = session('usuario_email') === 'admin@hierroforja.com';

        return view('admin.usuarios.index', compact(
            'administradores',
            'compradores',
            'buscarAdmin',
            'buscarComprador',
            'esRoot'
        ));
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
        $usuario->update([
            'activo' => false,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario dado de baja correctamente.');
    }
}