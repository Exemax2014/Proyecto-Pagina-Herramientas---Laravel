<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="navbar-main w-100">

            <div class="navbar-left">
                <a class="navbar-brand" href="{{ route('home') }}">
                    HIERRO &amp; FORJA
                </a>

                <div class="navbar-search-slot">
                    @unless(request()->routeIs('catalogo'))
                        <form
                            class="navbar-search navbar-search-desktop"
                            id="navbarSearchFormDesktop"
                            role="search"
                            method="GET"
                            action="{{ route('catalogo') }}"
                            autocomplete="off"
                        >
                            <input
                                class="form-control form-control-sm"
                                id="navbarSearchInputDesktop"
                                name="search"
                                type="search"
                                placeholder="Buscar..."
                                aria-label="Buscar"
                                value="{{ request('search') }}"
                            >
                        </form>
                    @endunless
                </div>
            </div>

            <ul class="navbar-nav navbar-desktop-links">
                <li class="nav-item nav-main">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Inicio
                    </a>
                </li>

                <li class="nav-item nav-main">
                    <a class="nav-link {{ request()->routeIs('catalogo') ? 'active' : '' }}" href="{{ route('catalogo') }}">
                        Cat&aacute;logo
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
                    <a class="nav-link {{ request()->routeIs('terminos') ? 'active' : '' }}" href="{{ route('terminos') }}">
                        T&eacute;rminos
                    </a>
                </li>

                <li class="nav-item nav-secondary">
                    <a class="nav-link {{ request()->routeIs('contacto') ? 'active' : '' }}" href="{{ route('contacto') }}">
                        Contacto
                    </a>
                </li>
            </ul>

            @php
                $usuarioEmail = session('usuario_email');
                $usuarioNombre = trim((string) session('usuario_nombre'));
                $partesNombre = preg_split('/\s+/', $usuarioNombre, -1, PREG_SPLIT_NO_EMPTY);
                $primerNombre = $partesNombre[0] ?? $usuarioNombre;
                $nombreMostrar = $usuarioEmail === 'admin@hierroforja.com'
                    ? 'UserRoot'
                    : $primerNombre;
            @endphp

            <div class="navbar-actions">
                <button class="navbar-toggler border-0 shadow-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#menuNav"
                        aria-controls="menuNav"
                        aria-expanded="false"
                        aria-label="Abrir menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a href="{{ route('carrito') }}" class="text-white fs-5 navbar-icon-link navbar-cart-link position-relative" aria-label="Carrito">
                    <i class="bi bi-cart"></i>
                    <span data-cart-count class="navbar-cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill">
                        0
                    </span>
                </a>

                <div class="dropdown">
                    <button class="btn text-white fs-5 navbar-user-trigger"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            aria-label="Opciones de usuario">
                        <i class="bi bi-person-circle"></i>

                        @if(session('usuario_id'))
                            <span class="navbar-user-name">{{ $nombreMostrar }}</span>
                        @endif
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end navbar-user-menu">
                        @if(session('usuario_id'))
                            @if(session('usuario_role') === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Ir al panel admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('mis-datos') }}">Mis datos</a></li>
                                <li><a class="dropdown-item" href="{{ route('mis-compras.index') }}">Mis compras</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        Cerrar sesi&oacute;n
                                    </button>
                                </form>
                            </li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                            <li><a class="dropdown-item" href="{{ route('registro') }}">Registro</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse navbar-mobile-panel w-100" id="menuNav">
            <ul class="navbar-nav navbar-mobile-links mobile-main-links">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalogo') ? 'active' : '' }}" href="{{ route('catalogo') }}">
                        Cat&aacute;logo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('quienes-somos') ? 'active' : '' }}" href="{{ route('quienes-somos') }}">
                        Qui&eacute;nes Somos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('comercializacion') ? 'active' : '' }}" href="{{ route('comercializacion') }}">
                        Comercializaci&oacute;n
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav navbar-mobile-links mobile-secondary-links">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('terminos') ? 'active' : '' }}" href="{{ route('terminos') }}">
                        T&eacute;rminos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contacto') ? 'active' : '' }}" href="{{ route('contacto') }}">
                        Contacto
                    </a>
                </li>
            </ul>

            @unless(request()->routeIs('catalogo'))
                <form
                    class="navbar-search navbar-search-mobile mobile-search-block"
                    id="navbarSearchFormMobile"
                    role="search"
                    method="GET"
                    action="{{ route('catalogo') }}"
                    autocomplete="off"
                >
                    <input
                        class="form-control"
                        id="navbarSearchInputMobile"
                        name="search"
                        type="search"
                        placeholder="Buscar..."
                        aria-label="Buscar"
                        value="{{ request('search') }}"
                    >
                </form>
            @endunless

        </div>
    </div>
</nav>
