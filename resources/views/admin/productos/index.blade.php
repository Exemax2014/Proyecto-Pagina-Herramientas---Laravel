@extends('layouts.admin')

@section('title', 'Productos | Panel Admin')
@section('page-title', 'Productos')

@section('contenido')

    <div class="admin-card mb-4">
        <div class="admin-products-toolbar">
            
            <form method="GET" action="{{ route('admin.productos.index') }}" class="admin-products-search">
                <input 
                    type="text" 
                    name="buscar" 
                    class="form-control" 
                    placeholder="Buscar por nombre, categoría o marca..."
                    value="{{ $buscar }}"
                >

                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-search"></i>
                    Buscar
                </button>
            </form>

            <a href="{{ route('admin.productos.create') }}" class="btn btn-warning admin-product-create-btn">
                <i class="bi bi-plus-circle"></i>
                Nuevo producto
            </a>

        </div>
    </div>

    <div class="admin-results-summary mb-3">
        @if($buscar)
            <span>
                Resultados para: <strong>"{{ $buscar }}"</strong>
            </span>

            <span>
                {{ $productos->total() }} producto{{ $productos->total() !== 1 ? 's' : '' }} encontrado{{ $productos->total() !== 1 ? 's' : '' }}
            </span>

            <a href="{{ route('admin.productos.index') }}" class="btn btn-sm btn-outline-secondary">
                Limpiar búsqueda
            </a>
        @else
            <span>
                Mostrando <strong>{{ $productos->total() }}</strong> productos cargados.
            </span>
        @endif
    </div>

    <div class="admin-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 admin-products-table">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Imagen</th>
                        <th class="text-center">Producto</th>
                        <th class="text-center">Categoría</th>
                        <th class="text-center">Marca</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($productos as $producto)
                        @php
                            $imagen = $producto->imagenPrincipal?->url
                                ? asset($producto->imagenPrincipal->url)
                                : asset('img/producto-sin-imagen.svg');
                        @endphp

                        <tr>
                            <td style="width: 90px;">
                                <img 
                                    src="{{ $imagen }}" 
                                    alt="{{ $producto->nombre }}"
                                    class="admin-table-img"
                                >
                            </td>

                            <td>
                                <strong>{{ $producto->nombre }}</strong>

                                @if(!empty($producto->etiquetas_visuales))
                                    <div class="small text-muted">
                                        Etiquetas:
                                        {{ collect($producto->etiquetas_visuales)->pluck('texto')->join(' / ') }}
                                    </div>
                                @endif
                            </td>

                            <td class="text-center">
                                {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                            </td>

                            <td>
                                {{ $producto->marca->nombre ?? 'Sin marca' }}
                            </td>

                            <td class="text-center">
                                <strong>
                                    ${{ number_format($producto->precio, 0, ',', '.') }}
                                </strong>

                                @if($producto->precio_anterior)
                                    <div class="small text-muted text-decoration-line-through">
                                        ${{ number_format($producto->precio_anterior, 0, ',', '.') }}
                                    </div>
                                @endif

                                @if($producto->porcentaje_descuento)
                                    <div class="small fw-bold text-warning">
                                        {{ $producto->porcentaje_descuento }}% OFF
                                    </div>
                                @endif
                            </td>

                            <td class="text-center">            
                                @if($producto->stock <= 0)
                                    <span class="badge bg-danger">Sin stock</span>
                                @elseif($producto->stock <= 3)
                                    <span class="badge bg-warning text-dark">
                                        Stock bajo: {{ $producto->stock }}
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        {{ $producto->stock }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">        
                                @if($producto->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="admin-actions">
                                    <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-sm btn-outline-primary admin-action-btn">
                                        Editar
                                    </a>

                                    @if($producto->activo)
                                        <form 
                                            action="{{ route('admin.productos.desactivar', $producto) }}" 
                                            method="POST"
                                            class="admin-action-form"
                                            onsubmit="return confirm('¿Seguro que querés dar de baja este producto?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-outline-danger admin-action-btn">
                                                Dar de baja
                                            </button>
                                        </form>
                                    @else
                                        <form 
                                            action="{{ route('admin.productos.activar', $producto) }}" 
                                            method="POST"
                                            class="admin-action-form"
                                            onsubmit="return confirm('¿Querés dar de alta este producto?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-outline-success admin-action-btn">
                                                Dar de alta
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                No se encontraron productos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination-wrapper mt-4">
        {{ $productos->links('pagination::bootstrap-5') }}
    </div>

@endsection
