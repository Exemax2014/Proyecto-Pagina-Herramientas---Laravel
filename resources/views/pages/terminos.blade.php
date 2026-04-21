@extends('layouts.app')

@section('title', 'Términos y condiciones | Hierro & Forja')

@section('contenido')
<section class="page-section terms-page">
    <div class="container">

        <!-- =========== HERO PRINCIPAL ============= -->
        <div class="row align-items-center g-5 terms-hero-row">
            <div class="col-lg-7">
                <!---- cabecera institucional con mayor identidad visual ---->
                <div class="page-hero terms-hero terms-hero-enhanced">
                    <span class="terms-hero-badge">
                        <i class="bi bi-shield-check"></i>
                        Marco legal e información comercial
                    </span>

                    <h1>Términos y condiciones</h1>

                    <div class="terms-hero-accent"></div>

                    <p>
                        En esta sección se detallan las condiciones generales de uso del sitio, las pautas comerciales,
                        las condiciones necesarias para concretar compras y las reglamentaciones internas aplicables
                        a la relación entre la empresa y sus clientes.
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

            <div class="col-lg-5">
                <!---- panel destacado de referencia legal ---->
                <div class="hero-panel terms-hero-panel">
                    <p class="panel-label">Resumen formal</p>

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
                <!---- introduccion institucional destacada ---->
                <article class="page-card terms-highlight-card">
                    <span class="terms-badge">
                        <i class="bi bi-exclamation-diamond-fill"></i>
                        Información importante
                    </span>
                    <p>
                        El acceso, navegación y uso del sitio implica la aceptación de estas condiciones generales.
                        Hierro &amp; Forja podrá actualizar, modificar o ampliar los presentes términos cuando resulte
                        necesario para adecuarlos a cambios comerciales, operativos o normativos.
                    </p>
                </article>
            </div>
        </div>

        <!-- =========== CARDS PRINCIPALES EN COLUMNA =========== -->
        <div class="terms-stack">

            <!---- garantia ---->
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
                    Los productos comercializados por la empresa cuentan con la garantía legal correspondiente
                    y, en los casos que aplique, con las condiciones informadas por cada fabricante o proveedor.
                </p>

                <div class="terms-points">
                    <div class="terms-point">La validez de la garantía depende del uso correcto del producto.</div>
                    <div class="terms-point">No cubre daños por golpes, mal uso, instalaciones incorrectas o desgaste impropio.</div>
                    <div class="terms-point">La empresa podrá solicitar comprobante de compra para gestionar revisiones o reclamos.</div>
                    <div class="terms-point">Los tiempos y alcances pueden variar según la categoría del producto.</div>
                </div>
            </article>

            <!---- compra ---->
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
                    Toda compra realizada a través del sitio o por canales comerciales de la empresa queda
                    sujeta a disponibilidad de stock, confirmación del pedido y validación interna.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Los precios y promociones pueden actualizarse sin previo aviso.</div>
                    <div class="terms-point">La empresa podrá confirmar o rechazar pedidos ante errores o falta de stock.</div>
                    <div class="terms-point">La confirmación comercial final se considera válida una vez aceptado el pedido.</div>
                    <div class="terms-point">Las imágenes y descripciones tienen carácter orientativo.</div>
                </div>
            </article>

            <!---- uso del sitio ---->
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
                    El acceso al sitio y la utilización de sus contenidos, formularios, medios de contacto
                    y recursos informativos implica un uso responsable, lícito y acorde a las condiciones vigentes.
                </p>

                <div class="terms-points">
                    <div class="terms-point">El usuario deberá navegar y utilizar el sitio conforme a la legislación vigente.</div>
                    <div class="terms-point">No se admite el uso indebido de formularios, medios de contacto o contenidos publicados.</div>
                    <div class="terms-point">La empresa podrá actualizar secciones, información y recursos del sitio cuando resulte necesario.</div>
                    <div class="terms-point">La continuidad en la navegación implica conocimiento de las condiciones publicadas.</div>
                </div>
            </article>

            <!---- reglamentaciones internas ---->
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
                    atención al cliente y administración de stock para brindar seguridad y claridad operativa.
                </p>

                <div class="terms-points">
                    <div class="terms-point">Los pedidos se gestionan según disponibilidad y orden de confirmación.</div>
                    <div class="terms-point">Las reservas, retiros y entregas deben respetar las condiciones informadas.</div>
                    <div class="terms-point">La empresa podrá reorganizar procedimientos internos para optimizar el servicio.</div>
                    <div class="terms-point">Las comunicaciones oficiales serán las emitidas por canales propios de la empresa.</div>
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
                    El usuario se compromete a utilizar este sitio de forma responsable, lícita y acorde a las
                    finalidades informativas y comerciales previstas por la empresa.
                </p>

                <div class="terms-points terms-points-dark">
                    <div class="terms-point">No se admite el uso del sitio para actividades fraudulentas, engañosas o contrarias a la normativa vigente.</div>
                    <div class="terms-point">La información publicada tiene carácter comercial e institucional y puede actualizarse cuando resulte necesario.</div>
                    <div class="terms-point">La empresa podrá modificar contenidos, condiciones, precios, medios de contacto y modalidades operativas sin previo aviso.</div>
                    <div class="terms-point">La continuidad en el uso del sitio implica conocimiento y aceptación de las presentes condiciones.</div>
                </div>
            </article>
        </div>
    </div>

</section>
@endsection