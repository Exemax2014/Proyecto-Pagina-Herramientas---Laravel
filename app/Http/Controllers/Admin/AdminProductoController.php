<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Marca;

class AdminProductoController extends Controller
{
    public function index(Request $request){
        $buscar = $request->input('buscar');

        $productos = Producto::with(['categoria', 'marca', 'imagenPrincipal'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhereHas('categoria', function ($q) use ($buscar) {
                        $q->where('nombre', 'like', "%{$buscar}%");
                    })
                    ->orWhereHas('marca', function ($q) use ($buscar) {
                        $q->where('nombre', 'like', "%{$buscar}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.productos.index', compact('productos', 'buscar'));
    }

    public function create(){
        $categorias = Categoria::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();

        return view('admin.productos.create', compact('categorias', 'marcas'));
    }

}