@extends('layouts.app')

@section('title', 'Comercialización | Hierro & Forja')

@section('contenido')

    <!-- Estilos específicos para la página de comercialización, heredando patrones de términos -->
    <style>
        /* ===== TARJETAS DE COMERCIO CON HOVER LIFT ===== */
        .comerc-point-card {
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

        .comerc-point-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 32px rgba(139, 69, 19, 0.12);
            border-color: #d4a574;
            background: #fbf3e5;
        }

        .comerc-point-card strong {
            color: #8b4513;
            font-size: 0.95rem;
            display: block;
        }

        .comerc-point-card p {
            color: #555;
            font-size: 0.95rem;
            margin: 0;
            line-height: 1.5;
        }

        .comerc-point-card svg {
            width: 28px;
            height: 28px;
            margin-bottom: 0.5rem;
        }

        /* ===== SECCIONES ALTERNADAS ===== */
        .comerc-section-light,
        .comerc-section-dark {
            padding: 4rem 0;
            background: #f7efe0;
        }

        .comerc-section-dark {
            background: #f7efe0;
        }

        .comerc-section-dark[style*="background: #1a1a1a"] .comerc-point-card {
            background: #2a2a2a;
            border-color: #3a3a3a;
            color: #d0d0d0;
        }

        .comerc-section-dark[style*="background: #1a1a1a"] .comerc-point-card:hover {
            background: #333;
            border-color: #d4a574;
            box-shadow: 0 16px 32px rgba(212, 165, 116, 0.2);
        }

        .comerc-section-dark[style*="background: #1a1a1a"] .comerc-point-card strong {
            color: #d4a574;
        }

        .comerc-section-dark[style*="background: #1a1a1a"] .comerc-point-card p {
            color: #c0c0c0;
        }

        .comerc-section-heading h2 {
            font-size: 2.2rem;
            margin-bottom: 0.8rem;
        }

        .comerc-heading-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 0.75rem;
        }

        .comerc-section-icon {
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

        .comerc-section-icon i {
            font-size: 1.2rem;
        }

        .comerc-tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .comerc-tag {
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
        .comerc-points-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .comerc-subsection-title {
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e6dcc8;
        }

        .comerc-subsection-title .comerc-tag {
            margin-bottom: 0.75rem;
        }

        /* ===== SECCIÓN LEGAL FINAL ===== */
        .comerc-legal-section {
            background: #1a1a1a;
            padding: 3rem 0;
            margin-top: 3rem;
        }

        .comerc-legal-card {
            background: #222;
            border: 1px solid #333;
            color: #f0f0f0;
        }

        .comerc-legal-card h2 {
            color: #ffffff;
        }

        .comerc-legal-card .comerc-point-card {
            background: #2a2a2a;
            border-color: #3a3a3a;
            color: #d0d0d0;
            border-left: 4px solid #d4a574;
        }

        .comerc-legal-card .comerc-point-card:hover {
            background: #333;
            border-color: #d4a574;
            box-shadow: 0 16px 32px rgba(212, 165, 116, 0.2);
        }

        .comerc-legal-card .comerc-point-card strong {
            color: #d4a574;
        }

        .comerc-legal-card .comerc-point-card p {
            color: #c0c0c0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .comerc-section-light,
            .comerc-section-dark {
                padding: 2.5rem 0;
            }

            .comerc-section-heading h2 {
                font-size: 1.75rem;
            }

            .comerc-points-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- HERO SECTION - Mismo patrón que terminos.blade.php -->
    <section class="hero-home contact-hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-8">
                    <h1 class="hero-title">Comercialización</h1>
                    <h2>Atención personalizada y respaldo comercial.</h2>

                    <p class="hero-copy">
                        Centralizamos una propuesta comercial pensada para talleres, obras, profesionales y clientes que necesitan
                        herramientas confiables, con condiciones claras, atención directa y acompañamiento en cada etapa del proceso.
                    </p>
                </div>

                <!-- Panel lateral indexador - Mismo patrón que terminos -->
                <div class="col-lg-4 terms-summary-column">
                    <div class="hero-panel contact-hero-panel terms-summary-panel">
                        <p class="panel-label">Secciones de página</p>

                        <div class="hero-panel-item">
                            <span class="panel-number">01</span>
                            <div>
                                <h3><a href="#pagos-envios" style="color: inherit; text-decoration: none;">Pagos y envíos</a></h3>
                            </div>
                        </div>

                        <div class="hero-panel-item">
                            <span class="panel-number">02</span>
                            <div>
                                <h3><a href="#asesoramiento" style="color: inherit; text-decoration: none;">Asesoramiento</a></h3>
                            </div>
                        </div>

                        <div class="hero-panel-item">
                            <span class="panel-number">03</span>
                            <div>
                                <h3><a href="#coordinacion" style="color: inherit; text-decoration: none;">Coordinación</a></h3>
                            </div>
                        </div>

                        <div class="hero-panel-item">
                            <span class="panel-number">04</span>
                            <div>
                                <h3><a href="#expansion" style="color: inherit; text-decoration: none;">Expansión</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BLOQUE DESTACADO INTRODUCTORIO -->


    <!-- ===== SECCIÓN 01: PAGOS Y ENVÍOS ===== -->
    <section id="pagos-envios" class="home-section comerc-section-light">
        <div class="container">
            <div class="section-heading contact-section-heading">
                <span class="home-kicker">Medios de pago</span>

                <div class="comerc-heading-title">
                    <div class="comerc-section-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <h2>Pagos y envíos</h2>
                </div>

                <div class="comerc-tag-list">
                    <span class="comerc-tag">Múltiples opciones</span>
                    <span class="comerc-tag">Confirmación previa</span>
                </div>
            </div>

            <!-- Grid de puntos como tarjetas -->
            <div class="comerc-points-grid">
                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M12 2v20M2 12h20M6 12h1v5H6zM17 12h1v5h-1zM9 14h6v3H9z"></path>
                    </svg>
                    <strong>Efectivo</strong>
                    <p>Pago en local con confirmación previa.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5M3 5c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2M3 9h18"></path>
                    </svg>
                    <strong>Transferencia</strong>
                    <p>Banco Nación, Galicia, BBVA y principales bancos.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2"></rect>
                        <path d="M2 11h20"></path>
                    </svg>
                    <strong>Tarjetas</strong>
                    <p>Visa, Mastercard, débito. Cuotas según vigencia.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <rect x="4" y="2" width="16" height="20" rx="2"></rect>
                        <path d="M8 6h8M8 10h8M8 14h8M8 18h4"></path>
                    </svg>
                    <strong>MercadoPago</strong>
                    <p>Pagos digitales y compras remotas con link de pago.</p>
                </div>
            </div>

            <!-- Subsección de Envíos -->
            <div class="comerc-subsection-title">
                <div class="comerc-tag-list">
                    <span class="comerc-tag">Logística</span>
                </div>
                <h3 style="margin: 0; font-size: 1.4rem; color: #333;">Envíos</h3>
            </div>

            <div class="comerc-points-grid">
                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M1 4v6h6M23 20v-6h-6"></path>
                        <path d="M20 9H4c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2z"></path>
                    </svg>
                    <strong>Andreani</strong>
                    <p>Envíos a todo el país con seguimiento online.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M12 2l9.46 5.46c.29.17.54.46.54.78v11.68c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V8.24c0-.32.25-.61.54-.78L12 2z"></path>
                        <path d="M12 13v8"></path>
                    </svg>
                    <strong>Cargo Expreso</strong>
                    <p>Servicio regional con entrega coordinada.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M20 10H4c-1.1 0-2 .9-2 2v7c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-7c0-1.1-.9-2-2-2z"></path>
                        <path d="M4 10V6c0-1.1.9-2 2-2h12c1.1 0 2 .9 2 2v4"></path>
                    </svg>
                    <strong>Retiro en local</strong>
                    <p>Retiro con turno previo en punto de atención.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECCIÓN 02: ASESORAMIENTO ===== -->
    <section id="asesoramiento" class="home-section comerc-section-dark" stroke="#d4a574"; color: #fff;">
        <div class="container">
            <div class="section-heading contact-section-heading" style="color: #fff;">
                <span class="home-kicker" style="color: #d4a574;">Orientación</span>

                <div class="comerc-heading-title">
                    <div class="comerc-section-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <h2 style="color: #0d0d0d;">Asesoramiento</h2>
                </div>

                <div class="comerc-tag-list">
                    <span class="comerc-tag" stroke="#d4a574"; color: #d4a574; border-color: #3a3a3a;">Criterio técnico</span>
                    <span class="comerc-tag" stroke="#d4a574" color: #d4a574; border-color: #3a3a3a;">Comparación real</span>
                </div>
            </div>

            <!-- Grid de puntos como tarjetas -->
            <div class="comerc-points-grid">
                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M6 20c0-3.3 2.7-6 6-6s6 2.7 6 6"></path>
                        <path d="M18 8h4M14 5l2 2"></path>
                    </svg>
                    <strong>Consulta técnica</strong>
                    <p>Te ayudamos a elegir según rubro y tarea.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <circle cx="12" cy="12" r="1"></circle>
                        <path d="M3 12c0 2.5 2.2 4.5 5 4.5M21 12c0-2.5-2.2-4.5-5-4.5M9 12c0 1.7 1.3 3 3 3s3-1.3 3-3"></path>
                    </svg>
                    <strong>Comparación</strong>
                    <p>Opciones reales según uso, precio y marca.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h11"></path>
                    </svg>
                    <strong>Alternativas</strong>
                    <p>Buscamos equivalentes funcionales si no hay stock.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <strong>Soporte posventa</strong>
                    <p>Acompañamos dudas después de la compra.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECCIÓN 03: COORDINACIÓN ===== -->
    <section id="coordinacion" class="home-section comerc-section-light">
        <div class="container">
            <div class="section-heading contact-section-heading">
                <span class="home-kicker">Logística interna</span>

                <div class="comerc-heading-title">
                    <div class="comerc-section-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <h2>Coordinación</h2>
                </div>

                <div class="comerc-tag-list">
                    <span class="comerc-tag">Orden operativo</span>
                    <span class="comerc-tag">Previsibilidad</span>
                </div>
            </div>

            <!-- Grid de puntos como tarjetas -->
            <div class="comerc-points-grid">
                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h11"></path>
                    </svg>
                    <strong>Gestión de pedidos</strong>
                    <p>Confirmación, stock y condiciones previas.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <circle cx="12" cy="12" r="1"></circle>
                        <path d="M12 1v6m0 6v6"></path>
                        <path d="M4.22 4.22l4.24 4.24M15.54 15.54l4.24 4.24"></path>
                    </svg>
                    <strong>Retiros y entregas</strong>
                    <p>Horarios y canales informados al coordinar.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <strong>Clientes recurrentes</strong>
                    <p>Seguimiento y reposición ordenada.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M16.5 13.5l-3.5 3.5-3.5-3.5"></path>
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                    </svg>
                    <strong>Compras de volumen</strong>
                    <p>Prioridad operativa y validación previa.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECCIÓN 04: EXPANSIÓN ===== -->
    <section id="expansion" class="home-section comerc-section-dark" style="background: #1a1a1a; color: #fff;">
        <div class="container">
            <div class="section-heading contact-section-heading" style="color: #fff;">
                <span class="home-kicker" style="color: #d4a574;">Crecimiento</span>

                <div class="comerc-heading-title">
                    <div class="comerc-section-icon">
                        <i class="bi bi-arrow-up-right"></i>
                    </div>
                    <h2 style="color: #fff;">Expansión</h2>
                </div>

                <div class="comerc-tag-list">
                    <span class="comerc-tag" style="background: #2a2a2a; color: #d4a574; border-color: #3a3a3a;">Nuevas líneas</span>
                    <span class="comerc-tag" style="background: #2a2a2a; color: #d4a574; border-color: #3a3a3a;">Adaptación continua</span>
                </div>
            </div>

            <!-- Grid de puntos como tarjetas -->
            <div class="comerc-points-grid">
                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M12 5v14M5 12h14"></path>
                    </svg>
                    <strong>Nuevas líneas</strong>
                    <p>Incorporamos categorías según demanda real.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-5-3.87"></path>
                        <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                    </svg>
                    <strong>Proveedores estratégicos</strong>
                    <p>Acuerdos que mejoran disponibilidad.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <strong>Canales de venta</strong>
                    <p>Presencial, online y pedidos coordinados.</p>
                </div>

                <div class="comerc-point-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#d4a574" stroke-width="2">
                        <path d="M1 4v6h6M23 20v-6h-6"></path>
                        <path d="M20.49 9a9 9 0 0 0-18.98 0"></path>
                        <path d="M3.51 15a9 9 0 0 0 18.98 0"></path>
                    </svg>
                    <strong>Adaptación continua</strong>
                    <p>Ajustamos procesos según mercado.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECCIÓN LEGAL FINAL - PROPUESTA DE VALOR ===== -->
    <section class="comerc-legal-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <article class="page-card comerc-legal-card">
                        <div style="display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 2rem;">
                            <div style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #d4a574, #c89660); border-radius: 12px; flex-shrink: 0;">
                                <i class="bi bi-briefcase-fill" style="color: #fff; font-size: 1.8rem;"></i>
                            </div>
                            <div>
                                <span class="home-kicker" style="color: #d4a574;">Compromiso comercial</span>
                                <h2 style="margin-top: 0.5rem;">Nuestra propuesta de valor</h2>
                            </div>
                        </div>

                        <div class="comerc-points-grid">
                            <div class="comerc-point-card">
                                <strong>Productos seleccionados</strong>
                                <p>Para uso profesional e intensivo con rendimiento real.</p>
                            </div>

                            <div class="comerc-point-card">
                                <strong>Atención directa</strong>
                                <p>Sin intermediarios innecesarios, mejorando comunicación.</p>
                            </div>

                            <div class="comerc-point-card">
                                <strong>Condiciones adaptadas</strong>
                                <p>De pago y entrega según cada operación.</p>
                            </div>

                            <div class="comerc-point-card">
                                <strong>Estructura preparada</strong>
                                <p>Para crecer con nuestros clientes.</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

@endsection