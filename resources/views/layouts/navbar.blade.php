<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <!-- IZQUIERDA -->
        <div class="d-flex align-items-center gap-4">
            <!-- LOGO PRINCIPAL -->
            <a class="navbar-brand fw-bold m-0" href="{{ url('/') }}">
                HIERRO &amp; FORJA
            </a>
        </div>

        <!-- CENTRO: LINKS EN PANTALLAS MEDIANAS O MAS GRANDES -->
        <div class="d-none d-sm-flex mx-auto">
            <ul class="navbar-nav flex-row gap-3 mb-0">
                <li class="nav-item"><a class="nav-link" href="{{ url('/comercializacion') }}">Comercializaci&oacute;n</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/quienes-somos') }}">Qui&eacute;nes Somos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/terminos') }}">T&eacute;rminos</a></li>
            </ul>
        </div>

        <!-- DERECHA -->
        <div class="d-flex align-items-center gap-3">
            <!-- BUSCADOR: TABLET Y DESKTOP -->
            <form class="d-none d-md-flex">
                <input class="form-control form-control-sm" type="search" placeholder="Buscar...">
            </form>

            <!-- ICONO DE CARRITO -->
            <a href="#" class="text-white fs-5">
                <i class="bi bi-cart"></i>
            </a>

            <!-- ICONO DE USUARIO -->
            <a href="#" class="text-white fs-5">
                <i class="bi bi-person-circle"></i>
            </a>

            <!-- BOTON HAMBURGUESA: SOLO MOBILE -->
            <button class="btn text-white p-1 d-flex d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav" aria-controls="menuNav" aria-expanded="false" aria-label="Abrir menu">
                <i class="bi bi-list fs-5"></i>
            </button>
        </div>
    </div>

    <!-- MENU DESPLEGABLE MOBILE -->
    <div class="collapse bg-dark w-100" id="menuNav">
        <div class="d-flex flex-column align-items-center text-center w-100 py-3">

            <!-- LINKS: SOLO MOBILE -->
            <ul class="navbar-nav d-flex flex-column align-items-center d-sm-none gap-2 w-100">
                <li class="nav-item"><a class="nav-link" href="#">Comercialización</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Quiénes Somos</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Términos</a></li>
            </ul>

            <!-- BUSCADOR SOLO CELULAR -->
            <div class="d-flex justify-content-center w-100 mt-3 d-md-none">
                <input class="form-control w-75" type="search" placeholder="Buscar...">
            </div>
        </div>
    </div>
</nav>
