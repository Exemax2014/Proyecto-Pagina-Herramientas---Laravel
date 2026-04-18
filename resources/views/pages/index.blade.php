@extends('layouts.app')

@section('contenido')
<section class="hero-home">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="home-kicker">Acero, potencia y precisi&oacute;n</span>
                <h1 class="hero-title">Herramientas que bancan el ritmo real del taller y la obra.</h1>
                <p class="hero-copy">
                    En Hierro &amp; Forja reunimos equipamiento confiable para construcci&oacute;n, herrer&iacute;a y trabajos de alta exigencia.
                    Calidad profesional, asesoramiento directo y soluciones pensadas para durar.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('catalogo') }}" class="btn btn-warning btn-lg px-4">Ver cat&aacute;logo</a>
                    <a href="{{ route('contacto') }}#contacto-formulario" class="btn btn-outline-light btn-lg px-4">Hacer una consulta</a>
                </div>

                <div class="hero-metrics">
                    <div>
                        <strong>+120</strong>
                        <span>productos de alto rendimiento</span>
                    </div>
                    <div>
                        <strong>Entrega</strong>
                        <span>r&aacute;pida y coordinada</span>
                    </div>
                    <div>
                        <strong>Soporte</strong>
                        <span>antes y despu&eacute;s de la compra</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-panel">
                    <p class="panel-label">Selecci&oacute;n destacada</p>
                    <div class="hero-panel-item">
                        <span class="panel-number">01</span>
                        <div>
                            <h3>Construcci&oacute;n</h3>
                            <p>Herramientas robustas para obra, montaje y trabajo intensivo.</p>
                        </div>
                    </div>
                    <div class="hero-panel-item">
                        <span class="panel-number">02</span>
                        <div>
                            <h3>Herrer&iacute;a</h3>
                            <p>Equipos y accesorios preparados para corte, ajuste y forja.</p>
                        </div>
                    </div>
                    <div class="hero-panel-item">
                        <span class="panel-number">03</span>
                        <div>
                            <h3>Carpinter&iacute;a</h3>
                            <p>Precisi&oacute;n, terminaci&oacute;n limpia y mejor control en cada pieza.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="home-section">
    <div class="container">
        <div class="section-heading">
            <span class="home-kicker">Nuestras categor&iacute;as</span>
            <h2>Una base s&oacute;lida para cada tipo de trabajo</h2>
            <p>Elegimos l&iacute;neas que respondan bien en rendimiento, durabilidad y seguridad de uso.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <article class="home-card">
                    <i class="bi bi-building-gear"></i>
                    <h3>Obra</h3>
                    <p>Soluciones para construcci&oacute;n, montaje y mantenimiento con exigencia diaria.</p>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="home-card">
                    <i class="bi bi-hammer"></i>
                    <h3>Taller</h3>
                    <p>Herramientas y accesorios para herrer&iacute;a, ajustes y fabricaci&oacute;n met&aacute;lica.</p>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="home-card">
                    <i class="bi bi-tools"></i>
                    <h3>Precisi&oacute;n</h3>
                    <p>Equipamiento para cortes limpios, terminaciones finas y mayor control.</p>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="home-card">
                    <i class="bi bi-shield-check"></i>
                    <h3>Seguridad</h3>
                    <p>Protecci&oacute;n y respaldo para que el trabajo fuerte se haga con confianza.</p>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="home-section home-section-dark">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <span class="home-kicker">Por qu&eacute; elegirnos</span>
                <h2>Vendemos herramientas, pero tambi&eacute;n criterio para comprar bien.</h2>
                <p class="mb-4">
                    La idea no es solo mostrar productos. Queremos que quien llegue a la p&aacute;gina entienda r&aacute;pido
                    qu&eacute; ofrecemos, para qu&eacute; sirve y por qu&eacute; vale la pena elegirnos.
                </p>
                <a href="{{ route('quienes-somos') }}" class="btn btn-outline-warning">Conocer la empresa</a>
            </div>

            <div class="col-lg-6">
                <div class="feature-stack">
                    <div class="feature-item">
                        <h3>Atenci&oacute;n cercana</h3>
                        <p>Te ayudamos a elegir seg&uacute;n el uso real, no solo por precio.</p>
                    </div>
                    <div class="feature-item">
                        <h3>Imagen profesional</h3>
                        <p>Una portada clara, fuerte y confiable para transmitir seriedad desde el primer vistazo.</p>
                    </div>
                    <div class="feature-item">
                        <h3>Base para crecer</h3>
                        <p>Esta estructura ya queda lista para sumar cat&aacute;logo, ofertas o marcas destacadas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
