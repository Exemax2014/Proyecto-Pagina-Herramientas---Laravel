@extends('layouts.admin')

@section('title', 'Dashboard | Hierro & Forja')
@section('page-title', 'Dashboard')

@section('contenido')

    <!-- MÉTRICAS -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-15 text-warning">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <h3>{{ $totalProductos }}</h3>
                    <p>Productos activos</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-15 text-primary">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h3>{{ $totalUsuarios }}</h3>
                    <p>Usuarios registrados</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-danger bg-opacity-15 text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <h3>{{ $sinStock }}</h3>
                    <p>Sin stock</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-15 text-success">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div>
                    <h3>{{ $masVendidos->sum('ventas') }}</h3>
                    <p>Ventas totales</p>
                </div>
            </div>
        </div>
    </div>

    <!-- TOP PRODUCTOS -->
    <div class="admin-card">
        <h2>Top 5 productos más vendidos</h2>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Ventas</th>
                        <th>Stock</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($masVendidos as $index => $producto)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <strong>{{ $producto->nombre }}</strong>
                            </td>

                            <td>
                                {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                            </td>

                            <td>
                                ${{ number_format($producto->precio, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $producto->ventas }}
                            </td>

                            <td>
                                @if($producto->stock === 0)
                                    <span class="badge bg-danger">Sin stock</span>
                                @elseif($producto->stock <= 5)
                                    <span class="badge bg-warning text-dark">{{ $producto->stock }}</span>
                                @else
                                    <span class="badge bg-success">{{ $producto->stock }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                Todavía no hay productos cargados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection