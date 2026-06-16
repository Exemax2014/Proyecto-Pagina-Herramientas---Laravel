@extends('layouts.app')

@section('title', 'Quiénes somos | Hierro & Forja')

@section('contenido')
<section class="page-section terms-page">
    <div class="container">

        <!-- =========== HERO PRINCIPAL ============= -->
        <div class="row align-items-center g-5 terms-hero-row">
            <div class="col-lg-8">
                <div class="page-hero terms-hero terms-hero-enhanced">
                    <span class="terms-hero-badge">
                        <i class="bi bi-building"></i>
                        Nuestra identidad
                    </span>

                    <h1>Quiénes somos</h1>

                    <div class="terms-hero-accent"></div>

                    <p>
                        Somos una empresa enfocada en la provisión de herramientas, insumos y soluciones para quienes trabajan
                        en obra, taller, mantenimiento, herrería, carpintería y construcción. Nuestra propuesta combina productos
                        confiables, atención directa y una forma de venta clara, pensada para que cada cliente pueda elegir mejor,
                        comprar con seguridad y recibir acompañamiento antes y después de cada operación.
                    </p>

                    <div class="terms-hero-trust">
                        <div class="terms-hero-trust-item">
                            <i class="bi bi-check2-circle"></i>
                            <span>Atención directa</span>
                        </div>
                        <div class="terms-hero-trust-item">
                            <i class="bi bi-award"></i>
                            <span>Productos confiables</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- =========== PANEL RESUMEN LATERAL ============= -->
            <div class="col-lg-4 terms-summary-column">
                <div class="hero-panel terms-hero-panel terms-summary-panel">
                    <p class="panel-label">Secciones de Pagina</p>

                    <div class="hero-panel-item">
                        <span class="panel-number">01</span>
                        <div><h3>Identidad</h3></div>
                    </div>
                    <div class="hero-panel-item">
                        <span class="panel-number">02</span>
                        <div><h3>Misión y visión</h3></div>
                    </div>
                    <div class="hero-panel-item">
                        <span class="panel-number">03</span>
                        <div><h3>Equipo</h3></div>
                    </div>
                    <div class="hero-panel-item">
                        <span class="panel-number">04</span>
                        <div><h3>Valores</h3></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========== CONTENIDO PRINCIPAL =========== -->
        <div class="terms-stack">

            <!-- ===== EQUIPO ===== -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-people"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Personas</span>
                        <h2>Nuestro equipo</h2>
                    </div>
                </div>

                <p>
                    Nuestro equipo es el punto de contacto entre el cliente y la solución que necesita. No nos limitamos a
                    mostrar productos: escuchamos la consulta, interpretamos el tipo de trabajo y orientamos la compra para
                    que cada decisión tenga sentido técnico, comercial y práctico.
                </p>


                <!-- CARRUSEL EQUIPO -->
                <div id="carruselEquipo" class="carousel slide terms-carousel mt-3" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carruselEquipo" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Equipo"></button>
                        <button type="button" data-bs-target="#carruselEquipo" data-bs-slide-to="1" aria-label="Dirección"></button>
                    </div>
                    <div class="carousel-inner rounded-3 overflow-hidden">
                        <div class="carousel-item active">
                            <img src="{{ asset('img/carrusel/foto-equipo.jpg.png') }}" class="d-block w-100 carousel-img" alt="Equipo de Hierro y Forja">
                            <div class="carousel-caption d-none d-md-block"><h5>Nuestro equipo</h5></div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('img/carrusel/foto-jefes.jpg.png') }}" class="d-block w-100 carousel-img" alt="Dirección de Hierro y Forja">
                            <div class="carousel-caption d-none d-md-block"><h5>Dirección y coordinación</h5></div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carruselEquipo" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carruselEquipo" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </article>

            <!-- ===== IDENTIDAD ===== -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-hammer"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Empresa</span>
                        <h2>Nuestra identidad</h2>
                    </div>
                </div>

                <p>
                    Nuestra identidad se construye sobre una idea simple: acompañar el trabajo real con productos adecuados,
                    atención seria y una experiencia de compra sin vueltas. Buscamos que cada cliente encuentre herramientas
                    útiles, insumos confiables y una respuesta comercial clara según el tipo de tarea que necesita resolver.
                </p>

                
            </article>

            <!-- ===== MISIÓN Y VISIÓN ===== -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-bullseye"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Propósito</span>
                        <h2>Misión y visión</h2>
                    </div>
                </div>

                <p>
                    Nuestra misión es facilitar el acceso a herramientas e insumos confiables para personas y equipos que
                    necesitan trabajar con continuidad, precisión y respaldo. Nuestra visión es consolidarnos como una opción
                    comercial de referencia, reconocida por la atención personalizada, la seriedad en la venta y la capacidad
                    de adaptarnos a distintas necesidades del mercado profesional.
                </p>

                
            </article>

            <!-- ===== VALORES ===== -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-shield-check"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Cultura</span>
                        <h2>Valores</h2>
                    </div>
                </div>

                <p>
                    Los valores de Hierro &amp; Forja definen la manera en que trabajamos cada consulta, venta y entrega.
                    Creemos que una relación comercial sólida se construye con información clara, cumplimiento, responsabilidad
                    y una atención que priorice la confianza por encima de la venta rápida.
                </p>

                
            </article>

            <!-- ===== NUESTRO ESPACIO ===== -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-images"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Galería</span>
                        <h2>Nuestro espacio</h2>
                    </div>
                </div>

                <p>
                    Nuestro espacio de trabajo acompaña la atención comercial, la organización de productos y la coordinación
                    de consultas, retiros y entregas. Es el punto desde donde buscamos brindar una experiencia clara, ordenada
                    y coherente con la necesidad de cada cliente.
                </p>

                <!-- CARRUSEL ESPACIO -->
                <div id="carruselEmpresa" class="carousel slide terms-carousel" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carruselEmpresa" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Local"></button>
                        <button type="button" data-bs-target="#carruselEmpresa" data-bs-slide-to="1" aria-label="Ubicación"></button>
                    </div>
                    <div class="carousel-inner rounded-3 overflow-hidden">
                        <div class="carousel-item active">
                            <img src="{{ asset('img/carrusel/foto-negocio.jpg.png') }}" class="d-block w-100 carousel-img" alt="Local de Hierro y Forja">
                            <div class="carousel-caption d-none d-md-block"><h5>Nuestro local</h5></div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('img/carrusel/foto-ubicacion.jpg.png') }}" class="d-block w-100 carousel-img" alt="Ubicación de Hierro y Forja">
                            <div class="carousel-caption d-none d-md-block"><h5>Nuestra ubicación</h5></div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carruselEmpresa" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carruselEmpresa" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </article>

        </div>
    </div>
</section>
@endsection