<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container-fluid">

        <!-- LOGO -->
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            HIERRO & FORJA
        </a>

        <!-- BOTÓN HAMBURGUESA MINI -->
        <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
            <span class="navbar-toggler-icon" style="width: 20px; height: 20px;"></span>
        </button>

        <!-- CONTENIDO -->
        <div class="collapse navbar-collapse" id="menuNav">

            <!-- LINKS (SOLO ESTOS COLAPSAN) -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Inicio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('quienes-somos') ? 'active' : '' }}" href="{{ url('/quienes-somos') }}">Quiénes Somos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('comercializacion') ? 'active' : '' }}" href="{{ url('/comercializacion') }}">Comercialización</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('terminos') ? 'active' : '' }}" href="{{ url('/terminos') }}">Términos</a>
                </li>

            </ul>

        </div>

        <!-- BUSCADOR (fuera del collapse) -->
        <form class="d-none d-md-flex me-3">
            <input class="form-control form-control-sm" type="search" placeholder="Buscar...">
        </form>

        <!-- ICONO USUARIO (SIEMPRE VISIBLE) -->
        <a href="#" class="text-white fs-5">
            <i class="bi bi-person-circle"></i>
        </a>

    </div>
</nav>