<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="navbar-main w-100">

            <!-- =========================================
                 LOGO + BUSCADOR DESKTOP
                 ========================================= -->
            <div class="navbar-left">
                <a class="navbar-brand" href="{{ route('home') }}">
                    HIERRO &amp; FORJA
                </a>

                @unless(request()->routeIs('catalogo'))
                <form class="navbar-search navbar-search-desktop" id="navbarSearchFormDesktop" role="search" autocomplete="off">
                    <input
                        class="form-control form-control-sm"
                        id="navbarSearchInputDesktop"
                        type="search"
                        placeholder="Buscar..."
                        aria-label="Buscar"
                    >
                    <div class="navbar-search-results" id="navbarSearchResultsDesktop"></div>
                </form>
                @endunless
            </div>

            <!-- =========================================
                 LINKS DESKTOP
                 Orden completo en pantallas grandes:
                 Inicio - Catálogo - Quiénes Somos - Comercialización - Términos - Contacto
                 ========================================= -->
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

            <!-- =========================================
                 ICONOS + BOTON HAMBURGUESA
                 ========================================= -->
            <div class="navbar-actions">
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

        <!-- =========================================
             MENU DESPLEGABLE RESPONSIVE
             Orden final cuando desaparecen todos los links:
             Inicio - Catálogo - Quiénes Somos - Comercialización - Términos - Contacto - Buscar
             ========================================= -->
        <div class="collapse navbar-collapse navbar-mobile-panel w-100" id="menuNav">

            <!-- LINKS PRINCIPALES MOBILE -->
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

            <!-- LINKS SECUNDARIOS MOBILE -->
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

            <!-- BUSCADOR MOBILE -->
            @unless(request()->routeIs('catalogo'))
            <form class="navbar-search navbar-search-mobile mobile-search-block" id="navbarSearchFormMobile" role="search" autocomplete="off">
                <input
                    class="form-control"
                    id="navbarSearchInputMobile"
                    type="search"
                    placeholder="Buscar..."
                    aria-label="Buscar"
                >
                <div class="navbar-search-results" id="navbarSearchResultsMobile"></div>
            </form>
            @endunless

        </div>
    </div>
</nav>