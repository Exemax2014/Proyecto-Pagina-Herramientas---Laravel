<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    public function misDatos()
    {
        $usuario = Usuario::findOrFail(session('usuario_id'));

        return view('pages.mis-datos', compact('usuario'));
    }

    public function update(Request $request)
    {
        $usuario = Usuario::findOrFail(session('usuario_id'));

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
        ]);

        $usuario->fill($datos);
        $usuario->save();

        session([
            'usuario_nombre' => $usuario->nombre,
            'usuario_email' => $usuario->email,
        ]);

        return redirect()
            ->route('mis-datos')
            ->with('success', 'Tus datos fueron actualizados correctamente.');
    }
}
