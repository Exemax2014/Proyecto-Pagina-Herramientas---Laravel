<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid d-flex align-items-center justify-content-between">

        <!-- IZQUIERDA -->
        <div class="d-flex align-items-center gap-4">

            <!-- Logo Principal -->
            <a class="navbar-brand fw-bold m-0" href="{{ url('/') }}">
                HIERRO & FORJA
            </a>

            <!-- BUSCADOR (tablet y desktop) -->
            <form class="d-none d-md-flex">
                <input class="form-control form-control-sm" type="search" placeholder="Buscar...">
            </form>

        </div>

        <!-- CENTRO (links en grande) -->
        <div class="d-none d-sm-flex">
            <ul class="navbar-nav flex-row gap-2">
                <li class="nav-item"><a class="nav-link" href="{{ url('/comercializacion') }}">Comercialización</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/quienes-somos') }}">Quiénes Somos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/terminos') }}">Términos</a></li>
            </ul>
        </div>

        <!-- DERECHA -->
        <div class="d-flex align-items-center gap-2">

            <!-- LOGIN -->
            <a href="#" class="text-white fs-5">
                <i class="bi bi-person-circle"></i>
            </a>

            <!-- HAMBURGUESA (único botón) -->
            <button class="btn text-white p-1 d-flex d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
                <i class="bi bi-list fs-5"></i>
            </button>

        </div>
    </div>

    <!-- MENÚ DESPLEGABLE -->
    <div class="collapse bg-dark" id="menuNav">

        <!-- LINKS (solo mobile) -->
        <ul class="navbar-nav text-center p-3 d-flex d-sm-none">
            <li class="nav-item"><a class="nav-link" href="{{ url('/comercializacion') }}">Comercialización</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('/quienes-somos') }}">Quiénes Somos</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('/terminos') }}">Términos</a></li>
        </ul>

        <!-- BUSCADOR (solo celular) -->
        <div class="p-3 d-flex d-md-none">
            <input class="form-control" type="search" placeholder="Buscar...">
        </div>

    </div>
</nav>