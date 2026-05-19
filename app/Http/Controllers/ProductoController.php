<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();

        return view('pages.catalogo', compact('categorias', 'marcas'));
    }

    public function filtrar(Request $request)
    {
        $query = Producto::with(['imagenPrincipal', 'categoria', 'marca'])
            ->where('activo', true);

        // Filtro por categorías
        if ($request->filled('categorias')) {
            $query->whereHas('categoria', function ($q) use ($request) {
                $q->whereIn('slug', $request->categorias);
            });
        }

        // Filtro por marcas
        if ($request->filled('marcas')) {
            $query->whereHas('marca', function ($q) use ($request) {
                $q->whereIn('nombre', $request->marcas);
            });
        }

        // Filtro por energía
        if ($request->filled('energia')) {
            $query->where('energia', $request->energia);
        }

        // Filtro por precio máximo
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        // Filtro por búsqueda de nombre
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Ordenamiento
        switch ($request->get('sort')) {
            case 'price-asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'best-sellers':
                $query->orderBy('ventas', 'desc');
                break;
            case 'name-asc':
                $query->orderBy('nombre', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('nombre', 'desc');
                break;
            default:
                $query->orderBy('id', 'asc');
        }

        $productos = $query->paginate(12);

        return response()->json([
            'productos' => $productos->map(function ($p) {
                return [
                    'id'             => $p->id,
                    'nombre'         => $p->nombre,
                    'descripcion'    => $p->descripcion,
                    'precio'         => $p->precio,
                    'precioAnterior' => $p->precio_anterior,
                    'ventas'         => $p->ventas,
                    'energia'        => $p->energia,
                    'etiqueta'       => $p->etiqueta,
                    'etiquetaClase'  => $p->etiqueta_clase,
                    'categoria'      => $p->categoria->slug,
                    'marca'          => $p->marca->nombre,
                    'imagen'         => $p->imagenPrincipal?->url ?? '/img/productos/default.jpg',
                ];
            }),
            'total'        => $productos->total(),
            'pagina_actual' => $productos->currentPage(),
            'total_paginas' => $productos->lastPage(),
        ]);
    }

    public function show($id)
    {
        $producto = Producto::with(['imagenes', 'categoria', 'marca'])
            ->where('activo', true)
            ->findOrFail($id);

        return view('pages.producto', compact('producto'));
    }
}