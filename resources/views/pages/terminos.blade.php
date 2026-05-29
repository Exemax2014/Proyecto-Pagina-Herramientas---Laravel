@extends('layouts.app')

@section('title', 'Términos y condiciones | Hierro & Forja')

@section('contenido')

    <!-- Estilos específicos para la página de términos, heredando patrones de contacto -->
    <style>
        /* ===== TARJETAS DE PUNTOS LEGALES CON HOVER LIFT ===== */
        .terms-point-card {
            padding: 1.5rem;
            border-radius: 12px;
            background: #f4ead8;
            border: 1px solid #e6dcc8;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .terms-point-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 32px rgba(139, 69, 19, 0.12);
            border-color: #d4a574;
            background: #fbf3e5;
        }

        .terms-point-card strong {
            color: #8b4513;
            font-size: 0.95rem;
            display: block;
        }

        .terms-point-card p {
            color: #555;
            font-size: 0.95rem;
            margin: 0;
            line-height: 1.5;
        }

        /* ===== SECCIONES ALTERNADAS ===== */
        .terms-section-light,
        .terms-section-dark {
            padding: 4rem 0;
            background: #f7efe0;
        }

        .terms-section-heading h2 {
            font-size: 2.2rem;
            margin-bottom: 0.8rem;
        }

        .terms-heading-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 0.75rem;
        }

        .terms-section-icon {
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #d4a574, #c89660);
            border-radius: 12px;
            color: #fff;
            flex-shrink: 0;
        }

        .terms-section-icon i {
            font-size: 1.2rem;
        }

        .terms-tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .terms-tag {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #f1e5d2;
            color: #8b4513;
            font-size: 0.82rem;
            font-weight: 600;
            border: 1px solid #e1d7c5;
        }

        /* ===== GRID DE PUNTOS ===== */
        .terms-points-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        /* ===== SECCIÓN LEGAL FINAL ===== */
        .terms-legal-section {
            background: #1a1a1a;
            padding: 3rem 0;
            margin-top: 3rem;
        }

        .terms-legal-card {
            background: #222;
            border: 1px solid #333;
            color: #f0f0f0;
        }

        .terms-legal-card h2 {
            color: #ffffff;
        }

        .terms-legal-card .terms-point-card {
            background: #2a2a2a;
            border-color: #3a3a3a;
            color: #d0d0d0;
        }

        .terms-legal-card .terms-point-card:hover {
            background: #333;
            border-color: #d4a574;
            box-shadow: 0 16px 32px rgba(212, 165, 116, 0.2);
        }

        .terms-legal-card .terms-point-card strong {
            color: #d4a574;
        }

        .terms-legal-card .terms-point-card p {
            color: #c0c0c0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .terms-section-light,
            .terms-section-dark {
                padding: 2.5rem 0;
            }

            .section-heading h2 {
                font-size: 1.75rem;
            }

            .terms-points-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- HERO SECTION - Mismo patrón que contacto.blade.php -->
    <section class="hero-home contact-hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-8">
                    <h1 class="hero-title">Términos y condiciones</h1>
                    <h2>Información clara y respaldo comercial.</h2>

                    <p class="hero-copy">
                        En esta sección se establecen las condiciones generales de uso del sitio, las pautas comerciales
                        aplicables a las compras, los criterios de garantía y las reglamentaciones internas que organizan
                        la relación entre Hierro &amp; Forja y sus clientes. El objetivo es brindar información clara,
                        ordenada y accesible antes de avanzar con cualquier operación comercial.
                    </p>

                    <!-- Badges descriptivos -->
                    <div class="hero-actions" style="gap: 1rem; display: flex; flex-wrap: wrap; margin-top: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #f0f0f0; border-radius: 8px; font-size: 0.9rem; color: #333;">
                            <i class="bi bi-check2-circle" style="color: #d4a574;"></i>
                            <span>Información clara</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #f0f0f0; border-radius: 8px; font-size: 0.9rem; color: #333;">
                            <i class="bi bi-award" style="color: #d4a574;"></i>
                            <span>Respaldo comercial</span>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral indexador - Mismo patrón que contacto -->
                <div class="col-lg-4 terms-summary-column">
                    <div class="hero-panel contact-hero-panel terms-summary-panel">
                        <p class="panel-label">Secciones de página</p>

                        <div class="hero-panel-item">
                            <span class="panel-number">01</span>
                            <div>
                                <h3><a href="#garantia" style="color: inherit; text-decoration: none;">Garantía</a></h3>
                            </div>
                        </div>

                        <div class="hero-panel-item">
                            <span class="panel-number">02</span>
                            <div>
                                <h3><a href="#compra" style="color: inherit; text-decoration: none;">Compra</a></h3>
                            </div>
                        </div>

                        <div class="hero-panel-item">
                            <span class="panel-number">03</span>
                            <div>
                                <h3><a href="#uso-sitio" style="color: inherit; text-decoration: none;">Uso del sitio</a></h3>
                            </div>
                        </div>

                        <div class="hero-panel-item">
                            <span class="panel-number">04</span>
                            <div>
                                <h3><a href="#reglamentaciones" style="color: inherit; text-decoration: none;">Reglamentaciones</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BLOQUE DESTACADO INTRODUCTORIO -->
    <section class="home-section terms-section-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <article class="page-card contact-info-card" style="padding: 2rem; border-left: 4px solid #d4a574;">
                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <i class="bi bi-exclamation-triangle" style="color: #d4a574; font-size: 1.5rem; flex-shrink: 0;"></i>
                            <div>
                                <h3 style="margin-top: 0; color: #8b4513;">Información importante</h3>
                                <p style="margin: 0; color: #555;">
                                    El acceso, navegación y uso del sitio implica el conocimiento de estas condiciones generales.
                                    Hierro &amp; Forja podrá actualizar, modificar o ampliar los presentes términos cuando resulte
                                    necesario. Las condiciones vigentes serán las publicadas en esta sección al momento de la consulta.
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECCIÓN 01: GARANTÍA ===== -->
    <section id="garantia" class="home-section terms-section-light">
        <div class="container">
            <div class="section-heading contact-section-heading">
                <span class="home-kicker">Respaldo</span>

                <div class="terms-heading-title">
                    <div class="terms-section-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h2>Garantía</h2>
                </div>

            </div>

            <!-- Grid de puntos como tarjetas -->
            <div class="terms-points-grid">
                <div class="terms-point-card">
                    <strong>Uso correcto del producto</strong>
                    <p>
                        La validez de la garantía depende del uso correcto del producto, respetando su finalidad,
                        capacidad de trabajo, indicaciones técnicas y condiciones normales de funcionamiento.
                    </p>
                </div>

                <div class="terms-point-card">
                    <strong>Exclusiones por mal uso</strong>
                    <p>
                        No se consideran cubiertos los daños producidos por golpes, caídas, mal uso, instalaciones
                        incorrectas, intervenciones no autorizadas, sobrecarga o desgaste impropio.
                    </p>
                </div>

                <div class="terms-point-card">
                    <strong>Trámite de garantía</strong>
                    <p>
                        La empresa podrá solicitar comprobante de compra, fotografías, descripción del inconveniente
                        o revisión del producto para iniciar la gestión correspondiente.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECCIÓN 02: COMPRA ===== -->
    <section id="compra" class="home-section terms-section-dark">
        <div class="container">
            <div class="section-heading contact-section-heading">
                <span class="home-kicker">Proceso</span>

                <div class="terms-heading-title">
                    <div class="terms-section-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <h2>Compra</h2>
                </div>

            </div>

            <!-- Grid de puntos como tarjetas -->
            <div class="terms-points-grid">
                <div class="terms-point-card">
                    <strong>Actualización de precios</strong>
                    <p>
                        Los precios, promociones, medios de pago y condiciones comerciales pueden actualizarse sin previo
                        aviso, especialmente ante cambios de stock, proveedor, costos o campañas vigentes.
                    </p>
                </div>

                <div class="terms-point-card">
                    <strong>Confirmación o rechazo</strong>
                    <p>
                        La empresa podrá confirmar, modificar o rechazar pedidos cuando exista falta de stock, errores de
                        carga, inconsistencias en la información o imposibilidad operativa de cumplimiento.
                    </p>
                </div>

                <div class="terms-point-card">
                    <strong>Confirmación final</strong>
                    <p>
                        La confirmación comercial final se considera válida una vez aceptado el pedido por los canales
                        correspondientes y verificadas las condiciones de pago, entrega o retiro.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECCIÓN 03: USO DEL SITIO ===== -->
    <section id="uso-sitio" class="home-section terms-section-light">
        <div class="container">
            <div class="section-heading contact-section-heading">
                <span class="home-kicker">Condiciones</span>

                <div class="terms-heading-title">
                    <div class="terms-section-icon">
                        <i class="bi bi-globe2"></i>
                    </div>
                    <h2>Uso del sitio</h2>
                </div>
            </div>

            <!-- Grid de puntos como tarjetas -->
            <div class="terms-points-grid">
                <div class="terms-point-card">
                    <strong>Navegación responsable</strong>
                    <p>
                        El usuario deberá navegar y utilizar el sitio conforme a la legislación vigente, evitando acciones
                        que puedan afectar su funcionamiento, seguridad, contenidos o disponibilidad.
                    </p>
                </div>

                <div class="terms-point-card">
                    <strong>Prohibiciones de uso</strong>
                    <p>
                        No se admite el uso indebido de formularios, datos de contacto, imágenes, textos, información comercial
                        o cualquier contenido publicado por la empresa.
                    </p>
                </div>

                <div class="terms-point-card">
                    <strong>Actualización de contenidos</strong>
                    <p>
                        Hierro &amp; Forja podrá actualizar secciones, recursos, productos, precios, textos o condiciones del
                        sitio cuando lo considere necesario para mejorar la experiencia o adecuar la información.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECCIÓN 04: REGLAMENTACIONES ===== -->
    <section id="reglamentaciones" class="home-section terms-section-dark">
        <div class="container">
            <div class="section-heading contact-section-heading">
                <span class="home-kicker">Normativa</span>

                <div class="terms-heading-title">
                    <div class="terms-section-icon">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <h2>Reglamentaciones</h2>
                </div>

            </div>

            <!-- Grid de puntos como tarjetas -->
            <div class="terms-points-grid">
                <div class="terms-point-card">
                    <strong>Gestión de pedidos</strong>
                    <p>
                        Los pedidos se gestionan según disponibilidad, orden de confirmación, validación del pago y condiciones
                        previamente acordadas con el cliente.
                    </p>
                </div>

                <div class="terms-point-card">
                    <strong>Retiros y entregas</strong>
                    <p>
                        Las reservas, retiros y entregas deben respetar los horarios, plazos, modalidades y canales informados
                        por la empresa durante la coordinación comercial.
                    </p>
                </div>

                <div class="terms-point-card">
                    <strong>Reorganización de procesos</strong>
                    <p>
                        La empresa podrá reorganizar procedimientos internos, medios de contacto, procesos de confirmación o
                        modalidades de entrega para optimizar el servicio.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECCIÓN LEGAL FINAL - Patrón oscuro como "Contacto online" ===== -->
    <section class="terms-legal-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <article class="page-card terms-legal-card">
                        <div style="display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 2rem;">
                            <div style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #d4a574, #c89660); border-radius: 12px; flex-shrink: 0;">
                                <i class="bi bi-file-earmark-text" style="color: #fff; font-size: 1.8rem;"></i>
                            </div>
                            <div>
                                <span class="home-kicker" style="color: #d4a574;">Uso y aceptación</span>
                                <h2 style="margin-top: 0.5rem;">Uso del sitio y condiciones generales</h2>
                            </div>
                        </div>

                        <p>
                            El usuario se compromete a utilizar este sitio de forma responsable, lícita y acorde a las finalidades
                            informativas y comerciales previstas por la empresa. El uso del sitio, sus formularios y medios de contacto
                            implica aceptar que la información publicada puede ser actualizada para mantenerla alineada a la operación
                            real de Hierro &amp; Forja.
                        </p>

                        <div class="terms-points-grid">
                            <div class="terms-point-card">
                                <strong>Prohibición de actividades fraudulentas</strong>
                                <p>
                                    No se admite el uso del sitio para actividades fraudulentas, engañosas, abusivas o contrarias a la
                                    normativa vigente.
                                </p>
                            </div>

                            <div class="terms-point-card">
                                <strong>Información comercial actualizable</strong>
                                <p>
                                    La información publicada tiene carácter comercial e institucional, y puede actualizarse cuando resulte
                                    necesario por cambios de stock, precios, medios de pago o procesos internos.
                                </p>
                            </div>

                            <div class="terms-point-card">
                                <strong>Modificación de contenidos</strong>
                                <p>
                                    La empresa podrá modificar contenidos, condiciones, precios, medios de contacto y modalidades operativas
                                    sin previo aviso, procurando mantener la información lo más clara y actualizada posible.
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

@endsection