<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // =========================================
    // LOGIN
    // =========================================

    public function mostrarLogin()
    {
        return view('pages.login');
    }

    public function procesarLogin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$/'],
            'password' => 'required|min:4',
        ], [
            'email.required' => 'Debes ingresar un correo.',
            'email.regex'    => 'El correo debe tener formato ejemplo@dominio.com.',
            'password.required' => 'Debes ingresar una contraseña.',
            'password.min'   => 'Mínimo 4 caracteres.',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()
                ->withErrors(['email' => 'Correo o contraseña incorrectos.'])
                ->withInput(['email' => $request->email]);
        }

        if (!$usuario->activo) {
            return back()
                ->withErrors(['email' => 'Este usuario está dado de baja. Contactá al administrador.'])
                ->withInput(['email' => $request->email]);
        }

        // Guardar usuario en sesión
        Session::put('usuario_id',     $usuario->id);
        Session::put('usuario_nombre', $usuario->nombre);
        Session::put('usuario_email',  $usuario->email);
        Session::put('usuario_role',   $usuario->role);

        // Redirigir según rol
        if ($usuario->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    // =========================================
    // REGISTRO
    // =========================================

    public function mostrarRegistro()
    {
        return view('pages.registro');
    }

    public function procesarRegistro(Request $request)
    {
        $request->validate([
            'nombre'                => ['required', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'apellido'              => ['required', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'email'                 => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$/', 'unique:usuarios,email'],
            'password'              => 'required|min:4',
            'password_confirmation' => 'required|same:password',
        ], [
            'nombre.required'   => 'Debes ingresar tu nombre.',
            'nombre.regex'      => 'El nombre solo puede contener letras y espacios.',
            'apellido.required' => 'Debes ingresar tu apellido.',
            'apellido.regex'    => 'El apellido solo puede contener letras y espacios.',
            'email.required'    => 'Debes ingresar un correo.',
            'email.regex'       => 'El correo debe tener formato ejemplo@dominio.com.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.required' => 'Debes ingresar una contraseña.',
            'password.min'      => 'Mínimo 4 caracteres.',
            'password_confirmation.same' => 'Las contraseñas no coinciden.',
        ]);

        Usuario::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'comprador',
            'activo'   => true,
        ]);

        return redirect()->route('login')->with('registro_ok', true);
    }

    // =========================================
    // LOGOUT
    // =========================================

    public function logout()
    {
        Session::flush();
        return redirect()->route('home');
    }
}