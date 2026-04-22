@extends('layouts.app')

@section('title', 'Comercialización | Hierro & Forja')

@section('contenido')
<section class="page-section terms-page">
    <div class="container">

        <!-- =========== HERO PRINCIPAL ============= -->
        <div class="row align-items-center g-5 terms-hero-row">
            <div class="col-lg-7">
                <div class="page-hero terms-hero terms-hero-enhanced">
                    <span class="terms-hero-badge">
                        <i class="bi bi-shop"></i>
                        Cobertura comercial
                    </span>

                    <h1>Comercialización</h1>

                    <div class="terms-hero-accent"></div>

                    <p>
                        Centralizamos una propuesta orientada a talleres, obras y clientes que necesitan
                        herramientas de uso intensivo con respuesta ágil y acompañamiento comercial.
                    </p>

                    <div class="terms-hero-trust">
                        <div class="terms-hero-trust-item">
                            <i class="bi bi-check2-circle"></i>
                            <span>Atención personalizada</span>
                        </div>
                        <div class="terms-hero-trust-item">
                            <i class="bi bi-award"></i>
                            <span>Respaldo comercial</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-panel terms-hero-panel">
                    <p class="panel-label">Resumen comercial</p>

                    <div class="hero-panel-item">
                        <span class="panel-number">01</span>
                        <div>
                            <h3>Pagos y envíos</h3>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">02</span>
                        <div>
                            <h3>Asesoramiento</h3>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">03</span>
                        <div>
                            <h3>Coordinación</h3>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">04</span>
                        <div>
                            <h3>Expansión</h3>
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
                        Información comercial
                    </span>
                    <p>
                        Hierro &amp; Forja centraliza su operación comercial para brindar una experiencia
                        de compra clara, ágil y adaptada a cada cliente, desde talleres independientes
                        hasta empresas y obras de gran escala.
                    </p>
                </article>
            </div>
        </div>

        <!-- =========== CARDS PRINCIPALES =========== -->
        <div class="terms-stack">

            <!-- PAGOS Y ENVÍOS -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-credit-card"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Medios de pago</span>
                        <h2>Pagos y envíos</h2>
                    </div>
                </div>

                <p>
                    Disponemos de múltiples medios de pago y opciones de envío adaptadas a cada cliente
                    y situación comercial.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Efectivo en local o contra entrega según modalidad acordada.</div>
                    <div class="terms-point">Transferencia bancaria con confirmación por comprobante.</div>
                    <div class="terms-point">Tarjetas de débito y crédito en cuotas según banco y promoción vigente.</div>
                    <div class="terms-point">Mercado Pago para pagos digitales y compras remotas.</div>
                    <div class="terms-point">Envíos a domicilio, por correo o retiro en local según disponibilidad.</div>
                </div>
            </article>

            <!-- ASESORAMIENTO -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-person-check"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Orientación</span>
                        <h2>Asesoramiento</h2>
                    </div>
                </div>

                <p>
                    Brindamos orientación personalizada sobre productos, usos recomendados y alternativas
                    equivalentes según cada necesidad.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Atención directa para consultas técnicas y comerciales.</div>
                    <div class="terms-point">Recomendaciones según tipo de trabajo, material y frecuencia de uso.</div>
                    <div class="terms-point">Alternativas equivalentes ante falta de stock o necesidades específicas.</div>
                    <div class="terms-point">Soporte postventa para dudas sobre productos adquiridos.</div>
                </div>
            </article>

            <!-- COORDINACIÓN -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-truck"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Logística</span>
                        <h2>Coordinación</h2>
                    </div>
                </div>

                <p>
                    Organizamos pedidos y entregas de forma eficiente para garantizar tiempos
                    y disponibilidad en cada operación.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Gestión de pedidos según orden de confirmación y stock disponible.</div>
                    <div class="terms-point">Coordinación de retiros y entregas con tiempos acordados.</div>
                    <div class="terms-point">Seguimiento de pedidos para clientes con compras recurrentes.</div>
                    <div class="terms-point">Prioridad operativa para cuentas y volúmenes comerciales mayores.</div>
                </div>
            </article>

            <!-- EXPANSIÓN -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Crecimiento</span>
                        <h2>Expansión</h2>
                    </div>
                </div>

                <p>
                    Nuestra estructura está preparada para incorporar nuevas marcas, líneas de productos
                    y acciones comerciales a futuro.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Incorporación progresiva de nuevas marcas y categorías de productos.</div>
                    <div class="terms-point">Desarrollo de acuerdos comerciales con proveedores estratégicos.</div>
                    <div class="terms-point">Ampliación de canales de venta y distribución según demanda.</div>
                    <div class="terms-point">Adaptación continua a las necesidades del mercado profesional.</div>
                </div>
            </article>

        </div>
    </div>

    <!-- =========== BLOQUE FINAL A LO ANCHO COMPLETO =========== -->
    <div class="terms-legal-section">
        <div class="terms-legal-inner">
            <article class="page-card page-card-dark terms-legal-card terms-fullwidth-card">
                <div class="terms-card-headline terms-card-headline-dark">
                    <span class="terms-card-icon">
                        <i class="bi bi-handshake"></i>
                    </span>
                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker terms-card-kicker-dark">Compromiso comercial</span>
                        <h2>Nuestra propuesta de valor</h2>
                    </div>
                </div>

                <p>
                    Hierro &amp; Forja trabaja con una estructura orientada a clientes profesionales que
                    necesitan herramientas confiables, atención directa y condiciones comerciales claras.
                </p>

                <div class="terms-points terms-points-dark">
                    <div class="terms-point">Productos seleccionados para uso profesional e intensivo.</div>
                    <div class="terms-point">Atención comercial directa sin intermediarios.</div>
                    <div class="terms-point">Condiciones de pago y entrega adaptadas a cada cliente.</div>
                    <div class="terms-point">Estructura preparada para crecer junto a nuestros clientes.</div>
                </div>
            </article>
        </div>
    </div>

</section>
@endsection