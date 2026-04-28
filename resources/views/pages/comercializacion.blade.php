@extends('layouts.app')

@section('title', 'Comercialización | Hierro & Forja')

@section('contenido')
<section class="page-section terms-page">
    <div class="container">

        <!-- =========== HERO PRINCIPAL ============= -->
        <div class="row align-items-center g-5 terms-hero-row">
            <div class="col-lg-8">
                <div class="page-hero terms-hero terms-hero-enhanced">
                    <span class="terms-hero-badge">
                        <i class="bi bi-shop"></i>
                        Cobertura comercial
                    </span>

                    <h1>Comercialización</h1>

                    <div class="terms-hero-accent"></div>

                    <p>
                        Centralizamos una propuesta comercial pensada para talleres, obras, profesionales y clientes que necesitan
                        herramientas e insumos confiables, con condiciones claras, atención directa y acompañamiento en cada etapa
                        de la compra. Nuestro objetivo es que cada operación sea simple, ordenada y adaptada al tipo de trabajo
                        que el cliente necesita resolver.
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

            <!-- =========== PANEL RESUMEN LATERAL ============= -->
            <div class="col-lg-4 terms-summary-column">
                <div class="hero-panel terms-hero-panel terms-summary-panel">
                    <p class="panel-label">Secciones de página</p>

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
                        Hierro &amp; Forja organiza su comercialización para brindar una experiencia de compra clara y práctica,
                        tanto para clientes particulares como para profesionales, talleres y empresas. Trabajamos con medios de pago,
                        coordinación de entregas, asesoramiento y seguimiento comercial para que cada pedido avance con información
                        precisa y sin complicaciones innecesarias.
                    </p>
                </article>
            </div>
        </div>

        <!-- =========== CARDS PRINCIPALES =========== -->
        <div class="terms-stack">

            <!-- ===== PAGOS Y ENVÍOS ===== -->
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
                    Disponemos de alternativas de pago y modalidades de entrega pensadas para facilitar la compra según el tipo
                    de cliente, el volumen del pedido y la disponibilidad operativa. Cada operación se confirma con condiciones
                    claras para evitar confusiones sobre importes, tiempos o formas de entrega.
                </p>

                <div class="terms-points">
                    <div class="terms-point">
                        Aceptamos efectivo en local o contra entrega cuando la operación lo permita, siempre bajo coordinación previa
                        y con confirmación de las condiciones acordadas.
                    </div>

                    <div class="terms-point">
                        La transferencia bancaria permite validar pagos de forma ordenada mediante comprobante, especialmente en
                        pedidos coordinados a distancia o compras de mayor volumen.
                    </div>

                    <div class="terms-point">
                        Las tarjetas de débito y crédito se gestionan según disponibilidad, promociones vigentes y condiciones
                        informadas al momento de confirmar la operación.
                    </div>

                    <div class="terms-point">
                        Mercado Pago permite resolver pagos digitales y compras remotas con mayor practicidad para clientes que no
                        pueden acercarse físicamente al local.
                    </div>

                    <div class="terms-point">
                        Los envíos, retiros o entregas se coordinan según zona, disponibilidad de stock, tipo de producto y modalidad
                        comercial acordada con el cliente.
                    </div>
                </div>
            </article>

            <!-- ===== ASESORAMIENTO ===== -->
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
                    El asesoramiento forma parte central de nuestra propuesta comercial. Buscamos que cada cliente pueda elegir
                    con criterio, comparando alternativas reales y entendiendo qué producto se adapta mejor al uso previsto,
                    al presupuesto disponible y al nivel de exigencia del trabajo.
                </p>

                <div class="terms-points">
                    <div class="terms-point">
                        Atendemos consultas técnicas y comerciales para orientar la elección según rubro, material, frecuencia de uso
                        y necesidad puntual del cliente.
                    </div>

                    <div class="terms-point">
                        Recomendamos productos considerando el tipo de trabajo, evitando respuestas genéricas que no contemplen el
                        contexto real de uso.
                    </div>

                    <div class="terms-point">
                        Cuando un producto no está disponible, buscamos alternativas equivalentes que mantengan una relación razonable
                        entre rendimiento, precio y funcionalidad.
                    </div>

                    <div class="terms-point">
                        El soporte postventa permite resolver dudas posteriores a la compra, especialmente en productos que requieren
                        indicaciones de uso, mantenimiento o compatibilidad.
                    </div>
                </div>
            </article>

            <!-- ===== COORDINACIÓN ===== -->
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
                    La coordinación de pedidos permite ordenar cada operación desde la consulta inicial hasta la entrega o retiro.
                    Este proceso es clave para clientes que necesitan previsibilidad, disponibilidad confirmada y tiempos claros
                    para organizar su trabajo.
                </p>

                <div class="terms-points">
                    <div class="terms-point">
                        Los pedidos se gestionan según orden de confirmación, disponibilidad de stock y condiciones comerciales
                        acordadas previamente.
                    </div>

                    <div class="terms-point">
                        Coordinamos retiros y entregas con horarios y modalidades claras para evitar demoras, confusiones o traslados
                        innecesarios.
                    </div>

                    <div class="terms-point">
                        Para clientes recurrentes, el seguimiento comercial permite mantener una comunicación más ordenada sobre
                        disponibilidad, reposiciones y nuevas solicitudes.
                    </div>

                    <div class="terms-point">
                        Las compras de mayor volumen o cuentas comerciales pueden requerir prioridad operativa, validación previa
                        y coordinación específica según el tipo de producto.
                    </div>
                </div>
            </article>

            <!-- ===== EXPANSIÓN ===== -->
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
                    Nuestra estructura comercial está pensada para crecer de forma ordenada. Buscamos incorporar nuevas líneas,
                    fortalecer vínculos con proveedores y ampliar la capacidad de respuesta sin perder claridad en la atención
                    ni responsabilidad en cada operación.
                </p>

                <div class="terms-points">
                    <div class="terms-point">
                        La incorporación progresiva de nuevas marcas y categorías permite responder mejor a las necesidades reales
                        del mercado profesional y particular.
                    </div>

                    <div class="terms-point">
                        Los acuerdos con proveedores estratégicos ayudan a mejorar disponibilidad, variedad de productos y condiciones
                        comerciales para nuestros clientes.
                    </div>

                    <div class="terms-point">
                        La ampliación de canales de venta permite atender consultas presenciales, online y pedidos coordinados a
                        distancia con mayor eficiencia.
                    </div>

                    <div class="terms-point">
                        La adaptación continua del catálogo y de los procesos internos nos permite acompañar cambios en la demanda,
                        nuevas herramientas y necesidades específicas del rubro.
                    </div>
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
                        <i class="bi bi-briefcase-fill"></i>
                    </span>

                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker terms-card-kicker-dark">Compromiso comercial</span>
                        <h2>Nuestra propuesta de valor</h2>
                    </div>
                </div>

                <p>
                    Hierro &amp; Forja trabaja con una estructura comercial orientada a clientes que necesitan herramientas
                    confiables, atención clara y condiciones adaptadas al tipo de compra. Nuestra propuesta busca simplificar
                    el proceso comercial sin perder seriedad, seguimiento ni respaldo.
                </p>

                <div class="terms-points terms-points-dark">
                    <div class="terms-point">
                        Productos seleccionados para uso profesional, intensivo y cotidiano, considerando rendimiento, durabilidad
                        y aplicación real.
                    </div>

                    <div class="terms-point">
                        Atención comercial directa para reducir intermediarios, mejorar la comunicación y facilitar la toma de decisiones.
                    </div>

                    <div class="terms-point">
                        Condiciones de pago, retiro y entrega adaptadas a cada operación, según disponibilidad, zona y tipo de pedido.
                    </div>

                    <div class="terms-point">
                        Estructura preparada para crecer junto a nuestros clientes, incorporando nuevas categorías, marcas y servicios
                        de forma progresiva.
                    </div>
                </div>
            </article>
        </div>
    </div>

</section>
@endsection