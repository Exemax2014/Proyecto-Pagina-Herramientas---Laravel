<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::get('/quienes-somos', function () {
    return view('pages.quienes-somos');
})->name('quienes-somos');

Route::get('/comercializacion', function () {
    return view('pages.comercializacion');
})->name('comercializacion');

Route::get('/contacto', function () {
    return view('pages.contacto');
})->name('contacto');

Route::get('/terminos', function () {
    return view('pages.terminos');
})->name('terminos');

Route::get('/catalogo', function () {
    return view('pages.catalogo');
})->name('catalogo');

Route::get('/producto/{id}', function ($id) {
    return view('pages.producto');
})->name('producto');

Route::get('/carrito', function () {
    return view('pages.carrito');
})->name('carrito');

/* =========================================
   LOGIN
   ========================================= */

/* Muestra el formulario */
Route::get('/login', function () {
    return view('pages.login');
})->name('login');

/* Procesa el formulario (SIN base de datos) */
Route::post('/login', function () {

    /* Validación básica */
    request()->validate([
        'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$/'],
        'password' => 'required|min:4',
    ], [
        'email.required' => 'Debes ingresar un correo.',
        'email.regex' => 'El correo debe tener formato ejemplo@dominio.com.',
        'password.required' => 'Debes ingresar una contraseña.',
        'password.min' => 'Mínimo 4 caracteres.',
    ]);

    /* Redirección directa al inicio */
    return redirect()->route('home');

})->name('login.procesar');

/* =========================================
   REGISTRO
   ========================================= */

Route::post('/registro', function () {

    request()->validate([
        'nombre' => ['required', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
        'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$/'],
        'password' => 'required|min:4',
        'password_confirmation' => 'required|same:password',
    ], [
        'nombre.required' => 'Debes ingresar tu nombre.',
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',

        'email.required' => 'Debes ingresar un correo.',
        'email.regex' => 'El correo debe tener formato ejemplo@dominio.com',

        'password.required' => 'Debes ingresar una contraseña.',
        'password.min' => 'Mínimo 4 caracteres.',

        'password_confirmation.same' => 'Las contraseñas no coinciden.',
    ]);

    return redirect()->route('home');

})->name('registro.procesar');

/* =========================================
   ETIQUETA "PAGOS Y ENVIOS "
   ========================================= */
Route::get('/pagos-envios', function () {
    return view('pages.pagos-envios');
});