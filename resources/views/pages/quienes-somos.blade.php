@extends('layouts.app')

@section('title', 'Quiénes somos | Hierro & Forja')

@section('contenido')
<section class="page-section terms-page">
    <div class="container">

        <!-- =========== HERO PRINCIPAL ============= -->
        <div class="row align-items-center g-5 terms-hero-row">
            <div class="col-lg-7">
                <div class="page-hero terms-hero terms-hero-enhanced">
                    <span class="terms-hero-badge">
                        <i class="bi bi-building"></i>
                        Nuestra identidad
                    </span>

                    <h1>Quiénes somos</h1>

                    <div class="terms-hero-accent"></div>

                    <p>
                        Somos un equipo orientado a la provisión de herramientas e insumos para trabajo
                        profesional, con foco en atención directa, respuesta rápida y productos confiables.
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

            <div class="col-lg-5">
                <div class="hero-panel terms-hero-panel">
                    <p class="panel-label">Nuestros pilares</p>

                    <div class="hero-panel-item">
                        <span class="panel-number">01</span>
                        <div>
                            <h3>Identidad</h3>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">02</span>
                        <div>
                            <h3>Misión y visión</h3>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">03</span>
                        <div>
                            <h3>Equipo</h3>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">04</span>
                        <div>
                            <h3>Valores</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== BLOQUE DESTACADO INTRODUCTORIO =========== -->
        <div class="row g-4 mt-1">
            <div class="col-12">
                <article class="page-card terms-highlight-card">
                    <span class="terms-badge">
                        <i class="bi bi-info-circle-fill"></i>
                        Sobre nosotros
                    </span>
                    <p>
                        En Hierro &amp; Forja buscamos que cada cliente encuentre una propuesta clara, seria
                        y útil. Trabajamos sobre una base de productos que responden bien en obra, taller y
                        mantenimiento, con una comunicación cercana para que la compra sea más simple.
                    </p>
                </article>
            </div>
        </div>

        <!-- =========== CARDS PRINCIPALES =========== -->
        <div class="terms-stack">

            <!-- IDENTIDAD -->
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
                    Una empresa pensada para acompañar el trabajo real, con productos que responden
                    en obra, taller y mantenimiento profesional.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Presencia comercial clara y profesional desde el primer contacto.</div>
                    <div class="terms-point">Selección de herramientas orientadas a durabilidad y rendimiento.</div>
                    <div class="terms-point">Atención personalizada para cada tipo de cliente y necesidad.</div>
                    <div class="terms-point">Base preparada para seguir creciendo con nuevas categorías y servicios.</div>
                </div>
            </article>

            <!-- MISIÓN Y VISIÓN -->
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
                    Nuestra misión y visión definen el rumbo comercial de Hierro &amp; Forja
                    y el compromiso con cada uno de nuestros clientes.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Misión: brindar herramientas e insumos de calidad que acompañen el trabajo profesional.</div>
                    <div class="terms-point">Visión: ser una referencia en el rubro por atención personalizada y confianza.</div>
                    <div class="terms-point">Mejora continua en productos, servicios y canales de atención al cliente.</div>
                    <div class="terms-point">Soluciones prácticas, duraderas y accesibles para cada tipo de trabajo.</div>
                </div>
            </article>

            <!-- EQUIPO -->
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
                    Contamos con un equipo comprometido con brindar soluciones rápidas y efectivas,
                    acompañando a cada cliente en su proceso de compra y uso de herramientas.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Atención directa sin intermediarios para una respuesta más ágil.</div>
                    <div class="terms-point">Conocimiento técnico para orientar cada consulta de forma precisa.</div>
                    <div class="terms-point">Compromiso con la satisfacción del cliente en cada operación.</div>
                    <div class="terms-point">Equipo preparado para crecer junto a la demanda del mercado.</div>
                </div>
            </article>

            <!-- VALORES -->
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
                    Los valores que guían nuestra operación definen cómo nos relacionamos con
                    clientes, proveedores y el mercado en general.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Honestidad comercial en precios, tiempos y condiciones informadas.</div>
                    <div class="terms-point">Responsabilidad en cada pedido, entrega y compromiso asumido.</div>
                    <div class="terms-point">Confianza como base de cada relación comercial duradera.</div>
                    <div class="terms-point">Profesionalismo en la imagen, atención y calidad de los productos ofrecidos.</div>
                </div>
            </article>

        </div>
    </div>
</section>
@endsection