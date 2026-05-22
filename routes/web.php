<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminProductoController;
use App\Http\Controllers\Admin\AdminUsuarioController;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;

Route::get('/', function () {
    $marcasHome = Marca::query()
        ->whereHas('productos', function ($query) {
            $query->where('activo', true);
        })
        ->orderBy('nombre')
        ->get();

    $categoriasHome = Categoria::query()
        ->whereHas('productos', function ($query) {
            $query->where('activo', true);
        })
        ->get()
        ->sortBy(function ($categoria) {
            $orden = [
                'herreria',
                'carpinteria',
                'construccion',
                'durlok',
                'ferreteria',
                'pintureria',
            ];

            $posicion = array_search($categoria->slug, $orden, true);

            return $posicion === false ? 999 : $posicion;
        })
        ->values()
        ->map(function ($categoria) {
            $imagen = match ($categoria->slug) {
                'herreria' => 'img/categorias/herreria.jpg',
                'carpinteria' => 'img/categorias/carpinteria.jpg',
                'construccion' => 'img/categorias/construccion.jpg',
                'durlok' => 'img/categorias/durlock.jpg',
                'ferreteria' => 'img/categorias/ferreteria.jpg',
                'pintureria' => 'img/categorias/pintura.jpg',
                default => 'img/fondo-index.jpg',
            };

            $clase = match ($categoria->slug) {
                'herreria', 'carpinteria' => 'category-card-large',
                'construccion', 'durlok' => 'category-card-medium',
                default => 'category-card-small',
            };

            return (object) [
                'id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'slug' => $categoria->slug,
                'imagen' => asset($imagen),
                'clase' => $clase,
            ];
        });

    $ofertasHome = Producto::with(['categoria', 'marca', 'imagenPrincipal'])
        ->where('activo', true)
        ->where(function ($query) {
            $query->whereNotNull('precio_anterior')
                ->orWhere('etiqueta', 'Oferta');
        })
        ->orderByDesc('ventas')
        ->orderBy('id')
        ->get();

    return view('pages.index', compact('marcasHome', 'categoriasHome', 'ofertasHome'));
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

        Route::get('/usuarios/crear-admin', [AdminUsuarioController::class, 'createAdmin'])
            ->name('usuarios.create-admin');    

        Route::post('/usuarios/crear-admin', [AdminUsuarioController::class, 'storeAdmin'])
            ->name('usuarios.store-admin');    

        Route::patch('/usuarios/{usuario}/activar', [AdminUsuarioController::class, 'activar'])
            ->name('usuarios.activar');

        Route::patch('/usuarios/{usuario}/desactivar', [AdminUsuarioController::class, 'desactivar'])
            ->name('usuarios.desactivar');    
        
        
});
