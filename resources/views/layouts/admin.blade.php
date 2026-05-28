<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin | Hierro & Forja')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icono-hierro&forja.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('Css/admin.css') }}">

    @stack('styles')
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
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Panel Pincipal
                </a>
            </li>

            <li>
                <a href="{{ route('admin.productos.index') }}"
                   class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Productos
                </a>
            </li>

            <li>
                <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Usuarios
                </a>
            </li>

            <li>
                <a href="{{ route('admin.categorias.index') }}"
                   class="{{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Categorías
                </a>
            </li>

            <li>
                <a href="{{ route('admin.marcas.index') }}"
                   class="{{ request()->routeIs('admin.marcas.*') ? 'active' : '' }}">
                    <i class="bi bi-award"></i> Marcas
                </a>
            </li>

            <li>
                <a href="{{ route('admin.consultas.index') }}"
                   class="{{ request()->routeIs('admin.consultas.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-left-text"></i> Consultas
                </a>
            </li>

            <li>
                <a href="{{ route('admin.pedidos.index') }}"
                   class="{{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> Pedidos
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}">
                    <i class="bi bi-arrow-left-circle"></i> Ver sitio
                </a>
            </li>
        </ul>

        <div class="admin-user">
            <i class="bi bi-person-circle"></i>
            {{ session('usuario_nombre') }}

            <br>

            <a href="{{ route('mis-datos') }}" class="btn btn-sm btn-outline-light mt-2 w-100">
                Mis datos
            </a>

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
            <h1>@yield('page-title', 'Panel administrador')</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('contenido')

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
