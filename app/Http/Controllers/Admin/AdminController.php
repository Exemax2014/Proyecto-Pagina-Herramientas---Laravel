<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Categoria;
use App\Models\Marca;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProductos  = Producto::count();
        $totalUsuarios   = Usuario::where('role', 'comprador')->count();
        $sinStock        = Producto::where('stock', 0)->count();
        $masVendidos     = Producto::orderBy('ventas', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalProductos',
            'totalUsuarios',
            'sinStock',
            'masVendidos'
        ));
    }
}