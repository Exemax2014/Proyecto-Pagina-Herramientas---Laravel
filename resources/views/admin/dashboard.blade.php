<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | Hierro & Forja</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }

        .admin-sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1e1e2e;
            color: #fff;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 0;
        }

        .admin-sidebar .brand {
            font-size: 1.1rem;
            font-weight: 800;
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            letter-spacing: 0.05em;
        }

        .admin-sidebar .brand span {
            display: block;
            font-size: 0.75rem;
            font-weight: 400;
            color: rgba(255,255,255,0.5);
            margin-top: 0.2rem;
        }

        .admin-nav {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
            flex: 1;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.92rem;
            transition: all 0.2s;
        }

        .admin-nav a:hover,
        .admin-nav a.active {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .admin-nav a i {
            font-size: 1.1rem;
            width: 20px;
        }

        .admin-sidebar .admin-user {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 0.82rem;
            color: rgba(255,255,255,0.5);
        }

        .admin-main {
            margin-left: 240px;
            padding: 2rem;
        }

        .admin-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .admin-topbar h1 {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0;
        }

        .stat-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-card h3 {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0;
        }

        .stat-card p {
            margin: 0;
            font-size: 0.82rem;
            color: #6c757d;
        }

        .admin-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        .admin-card h2 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="brand">
            Hierro & Forja
            <span>Panel de administración</span>
        </div>

        <ul class="admin-nav">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-box-seam"></i> Productos
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-people"></i> Usuarios
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-tags"></i> Categorías
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-chat-left-text"></i> Consultas
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-bag-check"></i> Pedidos
                </a>
            </li>
        </ul>

        <div class="admin-user">
            <i class="bi bi-person-circle"></i>
            {{ session('usuario_nombre') }}
            <br>
            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light mt-1 w-100">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="admin-main">

        <div class="admin-topbar">
            <h1>Dashboard</h1>
            <a href="{{ route('home') }}" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-arrow-left"></i> Ver sitio
            </a>
        </div>

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
                    @foreach($masVendidos as $index => $producto)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria->nombre }}</td>
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
                    @endforeach
                </tbody>
            </table>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>