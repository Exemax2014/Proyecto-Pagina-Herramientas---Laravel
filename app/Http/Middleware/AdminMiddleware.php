<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si no hay usuario logueado, lo mandamos al login
        if (! session()->has('usuario_id')) {
            return redirect()
                ->route('login')
                ->with('error', 'Primero tenés que iniciar sesión.');
        }

        // Si está logueado pero no es admin, lo mandamos al inicio
        if (session('usuario_role') !== 'admin') {
            return redirect('/')
                ->with('error', 'No tenés permisos para acceder al panel administrador.');
        }

        return $next($request);
    }
}