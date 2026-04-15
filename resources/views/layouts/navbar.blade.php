<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">

        <div class="navbar-main w-100">

            <!-- IZQUIERDA -->
            <div class="navbar-left">
                <a class="navbar-brand fw-bold m-0" href="{{ route('home') }}">
                    HIERRO &amp; FORJA
                </a>

                <form class="navbar-search navbar-search-desktop" role="search">
                    <input class="form-control form-control-sm"
                           type="search"
                           placeholder="Buscar..."
                           aria-label="Buscar">
                </form>
            </div>

            <!-- CENTRO -->
            <ul class="navbar-nav navbar-desktop-links">
                <li class="nav-item nav-main">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Inicio
                    </a>
                </li>

                <li class="nav-item nav-main">
                    <a class="nav-link {{ request()->routeIs('quienes-somos') ? 'active' : '' }}" href="{{ route('quienes-somos') }}">
                        Qui&eacute;nes Somos
                    </a>
                </li>

                <li class="nav-item nav-main">
                    <a class="nav-link {{ request()->routeIs('comercializacion') ? 'active' : '' }}" href="{{ route('comercializacion') }}">
                        Comercializaci&oacute;n
                    </a>
                </li>

                <li class="nav-item nav-secondary">
                    <a class="nav-link {{ request()->routeIs('contacto') ? 'active' : '' }}" href="{{ route('contacto') }}">
                        Contacto
                    </a>
                </li>

                <li class="nav-item nav-secondary">
                    <a class="nav-link {{ request()->routeIs('terminos') ? 'active' : '' }}" href="{{ route('terminos') }}">
                        T&eacute;rminos
                    </a>
                </li>

                <li class="nav-item nav-main">
                    <a class="nav-link {{ request()->routeIs('catalogo') ? 'active' : '' }}" href="{{ route('catalogo') }}">
                        Cat&aacute;logo
                    </a>
                </li>

                <li class="nav-item nav-secondary">
                    <a class="nav-link {{ request()->routeIs('consultas') ? 'active' : '' }}" href="{{ route('consultas') }}">
                        Consultas
                    </a>
                </li>
            </ul>

            <!-- DERECHA -->
            <div class="navbar-actions">
                <a href="{{ route('catalogo') }}" class="text-white fs-5 navbar-icon-link" aria-label="Catálogo">
                    <i class="bi bi-cart"></i>
                </a>

                <div class="dropdown">
                    <button class="btn text-white fs-5 p-0 navbar-user-trigger"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            aria-label="Opciones de usuario">
                        <i class="bi bi-person-circle"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end navbar-user-menu">
                        <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                        <li><a class="dropdown-item" href="{{ route('registro') }}">Registro</a></li>
                    </ul>
                </div>

                <button class="navbar-toggler border-0 shadow-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#menuNav"
                        aria-controls="menuNav"
                        aria-expanded="false"
                        aria-label="Abrir menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>

        <!-- PANEL DESPLEGABLE - Boton de Hamburguesa -->
        <div class="collapse navbar-collapse navbar-mobile-panel w-100" id="menuNav">

            <!-- BUSCADOR MOBILE -->
            <form class="navbar-search navbar-search-mobile mobile-search-block" role="search">
                <input class="form-control"
                    type="search"
                    placeholder="Buscar..."
                    aria-label="Buscar">
            </form>

            <!-- LINKS SECUNDARIOS -->
            <ul class="navbar-nav navbar-mobile-links mobile-secondary-links">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contacto') }}">Contacto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('terminos') }}">Términos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('consultas') }}">Consultas</a>
                </li>
            </ul>

            <!-- LINKS PRINCIPALES -->
            <ul class="navbar-nav navbar-mobile-links mobile-main-links">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('quienes-somos') }}">Quiénes Somos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('comercializacion') }}">Comercialización</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('catalogo') }}">Catálogo</a>
                </li>
            </ul>

        </div>

    </div>
</nav>