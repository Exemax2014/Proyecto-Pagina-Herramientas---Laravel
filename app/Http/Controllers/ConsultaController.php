<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    public function store(Request $request)
    {
        $usuarioId = session('usuario_id');
        $usuario = $usuarioId ? Usuario::find($usuarioId) : null;

        if ($usuario) {
            $datos = $request->validate([
                'consulta' => ['required', 'string', 'max:5000'],
            ]);

            Consulta::create([
                'usuario_id' => $usuario->id,
                'nombre_completo' => trim($usuario->nombre . ' ' . $usuario->apellido),
                'correo' => $usuario->email,
                'telefono' => $usuario->telefono,
                'consulta' => $datos['consulta'],
            ]);
        } else {
            $datos = $request->validate([
                'nombre_completo' => ['required', 'string', 'max:150'],
                'correo' => ['required', 'email', 'max:255'],
                'telefono' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\s\-()\.]+$/'],
                'consulta' => ['required', 'string', 'max:5000'],
            ]);

            Consulta::create([
                'usuario_id' => null,
                'nombre_completo' => $datos['nombre_completo'],
                'correo' => $datos['correo'],
                'telefono' => $datos['telefono'] ?? null,
                'consulta' => $datos['consulta'],
            ]);
        }

        return redirect()
            ->route('contacto')
            ->with('success', 'Gracias, hemos recibido su consulta y le responderemos a la brevedad.');
    }
}
