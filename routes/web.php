<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\Admin\AdminCategoriaController;
use App\Http\Controllers\Admin\AdminConsultaController;
use App\Http\Controllers\Admin\AdminMarcaController;
use App\Http\Controllers\Admin\AdminProductoController;
use App\Http\Controllers\Admin\AdminUsuarioController;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;

Route::get('/', function () {
    $marcasHome = Marca::query()
        ->where('mostrar_en_inicio', true)
        ->whereBetween('orden_inicio', [1, 12])
        ->orderBy('orden_inicio')
        ->limit(12)
        ->get();

    $categoriasHome = Categoria::query()
        ->where('mostrar_en_inicio', true)
        ->whereBetween('orden_inicio', [1, 6])
        ->whereHas('productos', function ($query) {
            $query->where('activo', true);
        })
        ->orderBy('orden_inicio')
        ->limit(6)
        ->get()
        ->values()
        ->map(function ($categoria, $index) {
            $clases = [
                'category-card-large',
                'category-card-large',
                'category-card-medium',
                'category-card-medium',
                'category-card-small',
                'category-card-small',
            ];

            $imagen = $categoria->imagen_url
                ? asset($categoria->imagen_url)
                : asset('img/fondo-index.jpg');

            return (object) [
                'id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'slug' => $categoria->slug,
                'imagen' => $imagen,
                'clase' => $clases[$index] ?? 'category-card-small',
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

Route::post('/contacto', [ConsultaController::class, 'store'])->name('contacto.store');

Route::get('/terminos', function () {
    return view('pages.terminos');
})->name('terminos');

Route::get('/catalogo', [ProductoController::class, 'index'])->name('catalogo');
Route::get('/catalogo/filtrar', [ProductoController::class, 'filtrar'])->name('catalogo.filtrar');
Route::get('/producto/{id}', [ProductoController::class, 'show'])->name('producto');

Route::get('/carrito', function () {
    return view('pages.carrito');
})->name('carrito');

Route::get('/mis-datos', [PerfilController::class, 'misDatos'])
    ->middleware('usuario')
    ->name('mis-datos');

Route::patch('/mis-datos', [PerfilController::class, 'update'])
    ->middleware('usuario')
    ->name('mis-datos.update');

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

        Route::get('/categorias', [AdminCategoriaController::class, 'index'])
            ->name('categorias.index');

        Route::get('/categorias/crear', [AdminCategoriaController::class, 'create'])
            ->name('categorias.create');

        Route::post('/categorias', [AdminCategoriaController::class, 'store'])
            ->name('categorias.store');

        Route::get('/categorias/{categoria}/editar', [AdminCategoriaController::class, 'edit'])
            ->name('categorias.edit');

        Route::patch('/categorias/{categoria}', [AdminCategoriaController::class, 'update'])
            ->name('categorias.update');

        Route::get('/marcas', [AdminMarcaController::class, 'index'])
            ->name('marcas.index');

        Route::post('/marcas', [AdminMarcaController::class, 'store'])
            ->name('marcas.store');

        Route::patch('/marcas/inicio', [AdminMarcaController::class, 'updateHomeSelection'])
            ->name('marcas.update-home');

        Route::patch('/marcas/{marca}', [AdminMarcaController::class, 'update'])
            ->name('marcas.update');

        Route::get('/consultas', [AdminConsultaController::class, 'index'])
            ->name('consultas.index');

        Route::patch('/consultas/{consulta}/leida', [AdminConsultaController::class, 'marcarLeida'])
            ->name('consultas.leida');

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
