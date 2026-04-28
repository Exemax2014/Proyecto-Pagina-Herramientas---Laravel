@extends('layouts.app')

@section('title', 'Términos y condiciones | Hierro & Forja')

@section('contenido')
<section class="page-section terms-page">
    <div class="container">

        <!-- =========== HERO PRINCIPAL ============= -->
        <div class="row align-items-center g-5 terms-hero-row">
            <div class="col-lg-8">
                <div class="page-hero terms-hero terms-hero-enhanced">
                    <span class="terms-hero-badge">
                        <i class="bi bi-shield-check"></i>
                        Marco legal e información comercial
                    </span>

                    <h1>Términos y condiciones</h1>

                    <div class="terms-hero-accent"></div>

                    <p>
                        En esta sección se establecen las condiciones generales de uso del sitio, las pautas comerciales
                        aplicables a las compras, los criterios de garantía y las reglamentaciones internas que organizan
                        la relación entre Hierro &amp; Forja y sus clientes. El objetivo es brindar información clara,
                        ordenada y accesible antes de avanzar con cualquier operación comercial.
                    </p>

                    <div class="terms-hero-trust">
                        <div class="terms-hero-trust-item">
                            <i class="bi bi-check2-circle"></i>
                            <span>Información clara</span>
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
                            <h3>Garantía</h3>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">02</span>
                        <div>
                            <h3>Compra</h3>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">03</span>
                        <div>
                            <h3>Uso del sitio</h3>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">04</span>
                        <div>
                            <h3>Reglamentaciones</h3>
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
                        <i class="bi bi-exclamation-diamond-fill"></i>
                        Información importante
                    </span>

                    <p>
                        El acceso, navegación y uso del sitio implica el conocimiento de estas condiciones generales.
                        Hierro &amp; Forja podrá actualizar, modificar o ampliar los presentes términos cuando resulte
                        necesario por cambios comerciales, operativos, técnicos o normativos. Las condiciones vigentes
                        serán las publicadas en esta sección al momento de la consulta o utilización del sitio.
                    </p>
                </article>
            </div>
        </div>

        <!-- =========== CARDS PRINCIPALES EN COLUMNA =========== -->
        <div class="terms-stack">

            <!-- ===== GARANTÍA ===== -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-shield-check"></i>
                    </span>

                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Respaldo</span>
                        <h2>Garantía</h2>
                    </div>
                </div>

                <p>
                    Los productos comercializados por la empresa cuentan con la garantía legal correspondiente y,
                    cuando corresponda, con las condiciones específicas informadas por fabricantes, importadores o
                    proveedores. La garantía tiene como finalidad respaldar fallas de origen o inconvenientes propios
                    del producto, siempre que se haya utilizado de manera adecuada.
                </p>

                <div class="terms-points">
                    <div class="terms-point">
                        La validez de la garantía depende del uso correcto del producto, respetando su finalidad,
                        capacidad de trabajo, indicaciones técnicas y condiciones normales de funcionamiento.
                    </div>

                    <div class="terms-point">
                        No se consideran cubiertos los daños producidos por golpes, caídas, mal uso, instalaciones
                        incorrectas, intervenciones no autorizadas, sobrecarga o desgaste impropio por utilización indebida.
                    </div>

                    <div class="terms-point">
                        La empresa podrá solicitar comprobante de compra, fotografías, descripción del inconveniente
                        o revisión del producto para iniciar la gestión correspondiente.
                    </div>

                    <div class="terms-point">
                        Los tiempos de revisión, reparación, cambio o respuesta pueden variar según la categoría del
                        producto y las políticas aplicables del fabricante o proveedor.
                    </div>
                </div>
            </article>

            <!-- ===== COMPRA ===== -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-bag-check"></i>
                    </span>

                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Proceso</span>
                        <h2>Compra</h2>
                    </div>
                </div>

                <p>
                    Toda compra realizada a través del sitio o por canales comerciales de la empresa queda sujeta a
                    disponibilidad de stock, confirmación del pedido, validación de datos y aceptación de las condiciones
                    comerciales informadas. La publicación de un producto no implica reserva automática ni confirmación
                    definitiva de la operación.
                </p>

                <div class="terms-points">
                    <div class="terms-point">
                        Los precios, promociones, medios de pago y condiciones comerciales pueden actualizarse sin previo
                        aviso, especialmente ante cambios de stock, proveedor, costos o campañas vigentes.
                    </div>

                    <div class="terms-point">
                        La empresa podrá confirmar, modificar o rechazar pedidos cuando exista falta de stock, errores de
                        carga, inconsistencias en la información publicada o imposibilidad operativa de cumplimiento.
                    </div>

                    <div class="terms-point">
                        La confirmación comercial final se considera válida una vez aceptado el pedido por los canales
                        correspondientes y verificadas las condiciones de pago, entrega o retiro.
                    </div>

                    <div class="terms-point">
                        Las imágenes, descripciones y datos técnicos publicados tienen carácter orientativo y buscan facilitar
                        la elección del producto, sin reemplazar la confirmación comercial o técnica cuando sea necesaria.
                    </div>
                </div>
            </article>

            <!-- ===== USO DEL SITIO ===== -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-globe2"></i>
                    </span>

                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Condiciones</span>
                        <h2>Uso del sitio</h2>
                    </div>
                </div>

                <p>
                    El acceso al sitio y la utilización de sus contenidos, formularios, medios de contacto y recursos
                    informativos implica un uso responsable, lícito y acorde a las finalidades comerciales previstas.
                    El sitio está pensado para informar, orientar consultas y facilitar el contacto con la empresa.
                </p>

                <div class="terms-points">
                    <div class="terms-point">
                        El usuario deberá navegar y utilizar el sitio conforme a la legislación vigente, evitando acciones
                        que puedan afectar su funcionamiento, seguridad, contenidos o disponibilidad.
                    </div>

                    <div class="terms-point">
                        No se admite el uso indebido de formularios, datos de contacto, imágenes, textos, información comercial
                        o cualquier contenido publicado por la empresa.
                    </div>

                    <div class="terms-point">
                        Hierro &amp; Forja podrá actualizar secciones, recursos, productos, precios, textos o condiciones del
                        sitio cuando lo considere necesario para mejorar la experiencia o adecuar la información.
                    </div>

                    <div class="terms-point">
                        La continuidad en la navegación implica conocimiento de las condiciones publicadas y aceptación del
                        uso del sitio bajo criterios responsables y comerciales.
                    </div>
                </div>
            </article>

            <!-- ===== REGLAMENTACIONES INTERNAS ===== -->
            <article class="page-card terms-card">
                <div class="terms-card-headline">
                    <span class="terms-card-icon">
                        <i class="bi bi-journal-check"></i>
                    </span>

                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker">Normativa</span>
                        <h2>Reglamentaciones</h2>
                    </div>
                </div>

                <p>
                    Hierro &amp; Forja opera bajo criterios internos de organización comercial, validación de pedidos,
                    administración de stock, atención al cliente y coordinación de entregas. Estas reglas permiten mantener
                    orden operativo, claridad en la comunicación y una mejor respuesta ante cada solicitud.
                </p>

                <div class="terms-points">
                    <div class="terms-point">
                        Los pedidos se gestionan según disponibilidad, orden de confirmación, validación del pago y condiciones
                        previamente acordadas con el cliente.
                    </div>

                    <div class="terms-point">
                        Las reservas, retiros y entregas deben respetar los horarios, plazos, modalidades y canales informados
                        por la empresa durante la coordinación comercial.
                    </div>

                    <div class="terms-point">
                        La empresa podrá reorganizar procedimientos internos, medios de contacto, procesos de confirmación o
                        modalidades de entrega para optimizar el servicio.
                    </div>

                    <div class="terms-point">
                        Las comunicaciones oficiales serán las emitidas por canales propios o autorizados de Hierro &amp; Forja,
                        especialmente para confirmar pedidos, pagos, disponibilidad o condiciones comerciales.
                    </div>
                </div>
            </article>
        </div>
    </div>

    <!-- =========== BLOQUE LEGAL FINAL A LO ANCHO COMPLETO =========== -->
    <div class="terms-legal-section">
        <div class="terms-legal-inner">
            <article class="page-card page-card-dark terms-legal-card terms-fullwidth-card">
                <div class="terms-card-headline terms-card-headline-dark">
                    <span class="terms-card-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </span>

                    <div class="terms-card-title-group">
                        <span class="terms-card-kicker terms-card-kicker-dark">Uso y aceptación</span>
                        <h2>Uso del sitio y condiciones generales</h2>
                    </div>
                </div>

                <p>
                    El usuario se compromete a utilizar este sitio de forma responsable, lícita y acorde a las finalidades
                    informativas y comerciales previstas por la empresa. El uso del sitio, sus formularios y medios de contacto
                    implica aceptar que la información publicada puede ser actualizada para mantenerla alineada a la operación
                    real de Hierro &amp; Forja.
                </p>

                <div class="terms-points terms-points-dark">
                    <div class="terms-point">
                        No se admite el uso del sitio para actividades fraudulentas, engañosas, abusivas o contrarias a la
                        normativa vigente.
                    </div>

                    <div class="terms-point">
                        La información publicada tiene carácter comercial e institucional, y puede actualizarse cuando resulte
                        necesario por cambios de stock, precios, medios de pago, disponibilidad o procesos internos.
                    </div>

                    <div class="terms-point">
                        La empresa podrá modificar contenidos, condiciones, precios, medios de contacto y modalidades operativas
                        sin previo aviso, procurando mantener la información lo más clara y actualizada posible.
                    </div>

                    <div class="terms-point">
                        La continuidad en el uso del sitio implica conocimiento y aceptación de las presentes condiciones generales,
                        junto con las actualizaciones que puedan incorporarse a futuro.
                    </div>
                </div>
            </article>
        </div>
    </div>

</section>
@endsection