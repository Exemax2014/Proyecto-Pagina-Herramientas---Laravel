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
        $with = ['imagenPrincipal', 'categoria', 'marca'];

        if (Producto::etiquetasDisponibles()) {
            $with[] = 'etiquetaManual';
        }

        $query = Producto::with($with)
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
                    'precio'         => (float) $p->precio,
                    'precioAnterior' => $p->precio_anterior,
                    'descuentoPorcentaje' => $p->porcentaje_descuento,
                    'ventas'         => $p->ventas,
                    'energia'        => $p->energia,
                    'etiquetas'      => is_array($p->etiquetas_visuales ?? null) ? $p->etiquetas_visuales : [],
                    'categoria'      => $p->categoria?->slug ?? 'sin-categoria',
                    'marca'          => $p->marca?->nombre ?? 'Sin marca',
                    'imagen'         => $p->imagenPrincipal?->url ?? '/img/producto-sin-imagen.svg',
                ];
            }),
            'total'        => $productos->total(),
            'pagina_actual' => $productos->currentPage(),
            'total_paginas' => $productos->lastPage(),
        ]);
    }

    public function show($id){
        $with = ['imagenes', 'categoria', 'marca'];

        if (Producto::etiquetasDisponibles()) {
            $with[] = 'etiquetaManual';
        }

        $producto = Producto::with($with)
            ->where('activo', true)
            ->findOrFail($id);

        $relacionados = Producto::with(['imagenPrincipal', 'marca'])
            ->where('activo', true)
            ->where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->limit(4)
            ->get();

        return view('pages.producto', compact('producto', 'relacionados'));
    }
}
