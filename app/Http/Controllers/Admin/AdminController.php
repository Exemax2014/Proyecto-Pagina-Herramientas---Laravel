<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Usuario;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProductos = Producto::count();
        $productosActivos = Producto::where('activo', true)->count();
        $productosInactivos = Producto::where('activo', false)->count();
        $productosSinStock = Producto::where('stock', 0)->count();
        $productosBajoStock = Producto::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->count();
        $productosEnOferta = Producto::whereNotNull('precio_anterior')
            ->whereColumn('precio_anterior', '>', 'precio')
            ->count();

        $totalUsuarios = Usuario::count();
        $compradoresRegistrados = Usuario::where('role', 'comprador')->count();
        $administradoresRegistrados = Usuario::where('role', 'admin')->count();

        $totalCategorias = Categoria::count();
        $totalMarcas = Marca::count();

        $ventasTotales = Producto::sum('ventas');
        $valorEstimadoStock = Producto::selectRaw('COALESCE(SUM(precio * stock), 0) as total')
            ->value('total');

        $masVendidos = Producto::with('categoria')
            ->orderByDesc('ventas')
            ->orderBy('nombre')
            ->limit(5)
            ->get();

        $productosRecientes = Producto::with(['categoria', 'marca'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProductos',
            'productosActivos',
            'productosInactivos',
            'productosSinStock',
            'productosBajoStock',
            'productosEnOferta',
            'totalUsuarios',
            'compradoresRegistrados',
            'administradoresRegistrados',
            'totalCategorias',
            'totalMarcas',
            'ventasTotales',
            'valorEstimadoStock',
            'masVendidos',
            'productosRecientes'
        ));
    }
}
