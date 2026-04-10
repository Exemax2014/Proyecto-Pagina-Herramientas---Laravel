<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hierro & Forja</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">

    <!-- Iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS propio -->
    <!--  <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">  -->
</head>

<body>

    <!-- NAVBAR -->
    @include('layouts.navbar')

    <!-- CONTENIDO -->
    <main class="container mt-5 pt-5">
        @yield('contenido')
    </main>

    <!-- FOOTER -->
    @include('layouts.footer')
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>