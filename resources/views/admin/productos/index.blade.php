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

    <div class="admin-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($productos as $producto)
                        @php
                            $imagen = $producto->imagenPrincipal?->url
                                ? asset($producto->imagenPrincipal->url)
                                : asset('img/producto-sin-imagen.png');
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

                                @if($producto->etiqueta)
                                    <div class="small text-muted">
                                        Etiqueta: {{ $producto->etiqueta }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                            </td>

                            <td>
                                {{ $producto->marca->nombre ?? 'Sin marca' }}
                            </td>

                            <td>
                                <strong>
                                    ${{ number_format($producto->precio, 0, ',', '.') }}
                                </strong>

                                @if($producto->precio_anterior)
                                    <div class="small text-muted text-decoration-line-through">
                                        ${{ number_format($producto->precio_anterior, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>

                            <td>
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

                            <td>
                                @if($producto->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <div class="btn-group">
                                    <a 
                                        href="{{ route('producto', $producto->id) }}" 
                                        class="btn btn-sm btn-outline-dark"
                                        target="_blank"
                                    >
                                        Ver
                                    </a>

                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        Editar
                                    </a>

                                    <button type="button" class="btn btn-sm btn-outline-danger">
                                        Eliminar
                                    </button>
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

    <div class="mt-4">
        {{ $productos->links() }}
    </div>

@endsection