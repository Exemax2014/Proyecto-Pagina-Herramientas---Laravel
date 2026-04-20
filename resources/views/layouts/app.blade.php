<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hierro &amp; Forja</title>

    <!-- FUENTES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">

    <!-- ICONOS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS PROPIO -->
    <link rel="stylesheet" href="{{ asset('Css/styleGeneral.css') }}">
    <link rel="stylesheet" href="{{ asset('Css/styleNavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('Css/styleFooter.css') }}">       
    <link rel="stylesheet" href="{{ asset('Css/styleVistas.css') }}"> 
    <link rel="stylesheet" href="{{ asset('Css/styleCatalogo.css') }}">

    @stack('styles')
</head>

@stack('scripts')

<body class="site-body">
    <!-- NAVBAR -->
    @include('layouts.navbar')

    <!-- CONTENIDO -->
    <main class="site-main">
        @yield('contenido')
    </main>

    <!-- FOOTER -->
    @include('layouts.footer')

    <!-- JS DE BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
