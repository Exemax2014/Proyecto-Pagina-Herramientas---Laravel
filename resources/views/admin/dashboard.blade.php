@extends('layouts.admin')

@section('title', 'Panel Principal | Hierro & Forja')
@section('page-title', 'Panel Principal')

@section('contenido')

    <div class="admin-dashboard-stack">

        <section class="admin-dashboard-section">
            <div class="admin-section-head">
                <div>
                    <span class="admin-section-kicker">Productos</span>
                    <h2>Estado del catalogo</h2>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div>
                            <h3>{{ $totalProductos }}</h3>
                            <p>Total de productos</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <h3>{{ $productosActivos }}</h3>
                            <p>Productos activos</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-slash-circle"></i>
                        </div>
                        <div>
                            <h3>{{ $productosInactivos }}</h3>
                            <p>Productos inactivos</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h3>{{ $productosSinStock }}</h3>
                            <p>Productos sin stock</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-battery-half"></i>
                        </div>
                        <div>
                            <h3>{{ $productosBajoStock }}</h3>
                            <p>Stock bajo (hasta 5)</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-tags"></i>
                        </div>
                        <div>
                            <h3>{{ $productosEnOferta }}</h3>
                            <p>Productos en oferta</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="admin-dashboard-section">
            <div class="admin-section-head">
                <div>
                    <span class="admin-section-kicker">Usuarios</span>
                    <h2>Resumen de cuentas</h2>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h3>{{ $totalUsuarios }}</h3>
                            <p>Usuarios registrados</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h3>{{ $compradoresRegistrados }}</h3>
                            <p>Compradores registrados</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <h3>{{ $administradoresRegistrados }}</h3>
                            <p>Administradores registrados</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="admin-dashboard-section">
            <div class="admin-section-head">
                <div>
                    <span class="admin-section-kicker">Catalogo</span>
                    <h2>Indicadores generales</h2>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-grid-1x2"></i>
                        </div>
                        <div>
                            <h3>{{ $totalCategorias }}</h3>
                            <p>Total de categorias</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <div>
                            <h3>{{ $totalMarcas }}</h3>
                            <p>Total de marcas</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div>
                            <h3>{{ $ventasTotales }}</h3>
                            <p>Ventas totales</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <h3>${{ number_format($valorEstimadoStock, 0, ',', '.') }}</h3>
                            <p>Valor estimado de stock</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-4 align-items-stretch">
            <div class="col-12 col-xl-7">
                <div class="admin-card h-100">
                    <div class="admin-section-head mb-3">
                        <div>
                            <span class="admin-section-kicker">Ventas</span>
                            <h2>Top 5 productos mas vendidos</h2>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Categoria</th>
                                    <th>Precio</th>
                                    <th>Ventas</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($masVendidos as $index => $producto)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $producto->nombre }}</strong></td>
                                        <td>{{ $producto->categoria->nombre ?? 'Sin categoria' }}</td>
                                        <td>${{ number_format($producto->precio, 0, ',', '.') }}</td>
                                        <td>{{ $producto->ventas }}</td>
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
                                            Todavia no hay productos cargados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="admin-dashboard-side-column">
                    <div class="admin-card admin-dashboard-side-card">
                        <div class="admin-section-head mb-3">
                            <div>
                                <span class="admin-section-kicker">Novedades</span>
                                <h2>Productos mas recientes</h2>
                            </div>
                        </div>

                        <div class="admin-mini-list">
                            @forelse($productosRecientes as $producto)
                                <div class="admin-mini-item">
                                    <div>
                                        <strong>{{ $producto->nombre }}</strong>
                                        <div class="small text-muted">
                                            {{ $producto->categoria->nombre ?? 'Sin categoria' }}
                                            @if($producto->marca)
                                                · {{ $producto->marca->nombre }}
                                            @endif
                                        </div>
                                    </div>

                                    <span class="badge {{ $producto->activo ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-muted mb-0">Todavia no hay productos recientes para mostrar.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
