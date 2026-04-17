<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('login');
    }

    public function procesarLogin(Request $request)
    {
        // Validación visual simple
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4',
        ], [
            'email.required' => 'Debes ingresar un correo electrónico.',
            'email.email' => 'Debes ingresar un correo válido.',
            'password.required' => 'Debes ingresar una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
        ]);

        // Por ahora no guarda ni consulta base de datos
        // Simplemente redirige al inicio si completó bien
        return redirect()->route('home');
    }
}