<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hierro & Forja')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ICONO-HIERRO&FORJA.png') }}">

    <!-- FUENTES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">

    <!-- ICONOS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS PROPIO -->
    <link rel="stylesheet" href="{{ asset('css/styleGeneral.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleNavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleFooter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleVistas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleCatalogo.css') }}">

    @stack('styles')
</head>

<body class="site-body" data-usuario-logueado="{{ session('usuario_id') ? '1' : '0' }}">
    <!-- NAVBAR -->
    @include('layouts.navbar')

    <!-- CONTENIDO -->
    <main class="site-main">
        @yield('contenido')
    </main>

    <!-- FOOTER -->
    @include('layouts.footer')

    <!-- TOAST GLOBAL -->
    <div id="siteToast" class="site-toast"></div>

    <script>
        window.usuarioLogueado = @json((bool) session('usuario_id'));
        window.hfUserRole = @json(session('usuario_role'));
        window.hfCartConfig = {
            loggedIn: @json((bool) session('usuario_id')),
            isAdmin: @json(session('usuario_role') === 'admin'),
            loginUrl: @json(route('login')),
            endpoints: {
                obtener: @json(route('carrito.obtener')),
                agregar: @json(route('carrito.agregar')),
                actualizar: @json(route('carrito.actualizar', ['item' => '__ITEM__'])),
                migrar: @json(route('carrito.migrar')),
                confirmar: @json(route('carrito.confirmar')),
                itemBase: @json(url('/carrito/item')),
            }
        };
    </script>

    <!-- JS GLOBAL DEL CARRITO -->
    <script src="{{ asset('js/carrito-utils.js') }}?v={{ filemtime(public_path('js/carrito-utils.js')) }}"></script>

    <!-- BUSCADOR DEL NAVBAR -->
    <script src="{{ asset('js/navbar-search.js') }}?v={{ filemtime(public_path('js/navbar-search.js')) }}"></script>

    <!-- FUNCIÓN TOAST GLOBAL -->
    <script>
        window.showToast = function (message, position = 'bottom') {
            const toast = document.getElementById('siteToast');
            if (!toast) return;

            const validPosition = position === 'top' ? 'top' : 'bottom';

            toast.classList.remove('site-toast--top', 'site-toast--bottom', 'is-visible');
            toast.classList.add(`site-toast--${validPosition}`);

            toast.textContent = message;

            void toast.offsetWidth;

            toast.classList.add('is-visible');

            clearTimeout(window.__toastTimer);

            window.__toastTimer = setTimeout(() => {
                toast.classList.remove('is-visible');
            }, 2600);
        };
    </script>

    <!-- JS DE BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
    @if(session('registro_ok'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.showToast('¡Te registraste correctamente!', 'top');
            });
        </script>
    @endif
</body>
</html>
