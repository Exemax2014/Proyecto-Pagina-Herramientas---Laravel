<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('usuario_id')) {
            return redirect()
                ->route('login')
                ->with('error', 'Primero tenes que iniciar sesion.');
        }

        return $next($request);
    }
}
