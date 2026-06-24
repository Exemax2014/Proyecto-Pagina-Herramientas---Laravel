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
        $redirect = $this->sanitizeRedirect($this->extractRedirect(request()));

        return view('pages.login', compact('redirect'));
    }

    public function procesarLogin(Request $request)
    {
        $redirect = $this->sanitizeRedirect($this->extractRedirect($request));

        $request->validate([
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Debes ingresar un correo.',
            'email.email'    => 'Debes ingresar un correo válido.',
            'email.max'      => 'El correo no puede superar los 255 caracteres.',
            'password.required' => 'Debes ingresar una contraseña.',
            'password.string'   => 'La contraseña debe ser un texto válido.',
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

        if ($redirect && str_starts_with($redirect, '/carrito') && ! $usuario->perfilCheckoutCompleto()) {
            return redirect()
                ->route('mis-datos')
                ->with('warning', 'Completa tus datos para continuar.');
        }

        if ($redirect) {
            return redirect($redirect);
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
            'nombre'                => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-\']+$/u'],
            'apellido'              => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-\']+$/u'],
            'email'                 => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'nombre.required'   => 'Debes ingresar tu nombre.',
            'nombre.string'     => 'El nombre debe ser texto.',
            'nombre.max'        => 'El nombre no puede superar los 100 caracteres.',
            'nombre.regex'      => 'El nombre solo puede contener letras, espacios, guiones y apóstrofos.',
            'apellido.required' => 'Debes ingresar tu apellido.',
            'apellido.string'   => 'El apellido debe ser texto.',
            'apellido.max'      => 'El apellido no puede superar los 100 caracteres.',
            'apellido.regex'    => 'El apellido solo puede contener letras, espacios, guiones y apóstrofos.',
            'email.required'    => 'Debes ingresar un correo.',
            'email.email'       => 'Debes ingresar un correo válido.',
            'email.max'         => 'El correo no puede superar los 255 caracteres.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.required' => 'Debes ingresar una contraseña.',
            'password.string'   => 'La contraseña debe ser texto.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
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

    protected function extractRedirect(Request $request): ?string
    {
        return $request->input('redirect');
    }

    protected function sanitizeRedirect(?string $redirect): ?string
    {
        if (! $redirect || ! is_string($redirect)) {
            return null;
        }

        if (! str_starts_with($redirect, '/')) {
            return null;
        }

        if (str_starts_with($redirect, '//')) {
            return null;
        }

        return preg_match('/^\/[A-Za-z0-9\-._~\/?=&%]*$/', $redirect)
            ? $redirect
            : null;
    }
}
