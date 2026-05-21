<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminProductoController;
use App\Http\Controllers\Admin\AdminUsuarioController;

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

Route::get('/catalogo', [ProductoController::class, 'index'])->name('catalogo');
Route::get('/catalogo/filtrar', [ProductoController::class, 'filtrar'])->name('catalogo.filtrar');
Route::get('/producto/{id}', [ProductoController::class, 'show'])->name('producto');

Route::get('/carrito', function () {
    return view('pages.carrito');
})->name('carrito');

/* =========================================
   LOGIN
   ========================================= */

/* Muestra el formulario */
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');

/* Procesa el formulario */
Route::post('/login', [AuthController::class, 'procesarLogin'])->name('login.procesar');

/* =========================================
   REGISTRO
   ========================================= */

/* Muestra el formulario de registro */
Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');

/* Procesa el formulario */
Route::post('/registro', [AuthController::class, 'procesarRegistro'])->name('registro.procesar');

/* =========================================
   LOGOUT
   ========================================= */
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* =========================================
   ETIQUETA "PAGOS Y ENVIOS "
   ========================================= */
Route::get('/pagos-envios', function () {
    return view('pages.pagos-envios');
});

/* =========================================
   PANEL ADMINISTRADOR
   ========================================= */
use App\Http\Controllers\Admin\AdminController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/productos', [AdminProductoController::class, 'index'])
            ->name('productos.index');

        Route::get('/productos/crear', [AdminProductoController::class, 'create'])
            ->name('productos.create');   
            
        Route::post('/productos', [AdminProductoController::class, 'store'])
            ->name('productos.store');

        Route::get('/productos/{producto}/editar', [AdminProductoController::class, 'edit'])
            ->name('productos.edit');    

        Route::patch('/productos/{producto}', [AdminProductoController::class, 'update'])
            ->name('productos.update');    
            
        Route::patch('/productos/{producto}/desactivar', [AdminProductoController::class, 'desactivar'])
            ->name('productos.desactivar');

        Route::patch('/productos/{producto}/activar', [AdminProductoController::class, 'activar'])
            ->name('productos.activar');

        
        
        Route::get('/usuarios', [AdminUsuarioController::class, 'index'])
            ->name('usuarios.index');

        Route::patch('/usuarios/{usuario}/activar', [AdminUsuarioController::class, 'activar'])
            ->name('usuarios.activar');

        Route::patch('/usuarios/{usuario}/desactivar', [AdminUsuarioController::class, 'desactivar'])
            ->name('usuarios.desactivar');    
        
        
});