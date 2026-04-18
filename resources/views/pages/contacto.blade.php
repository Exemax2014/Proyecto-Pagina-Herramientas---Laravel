@extends('layouts.app')

@section('contenido')
<section class="hero-home contact-hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- ======== HERO: TEXTO PRINCIPAL ======== -->
            <div class="col-lg-7">
                <span class="home-kicker">Canales de atención</span>
                <h1 class="hero-title">Estamos para responder cada consulta.</h1>
                <p class="hero-copy">
                    En Hierro &amp; Forja trabajamos con atención comercial directa para ayudarte con consultas generales,
                    pedidos de cotización, seguimiento y orientación sobre productos. Podés comunicarte por nuestros
                    canales online, acercarte al local o dejarnos tu mensaje desde el formulario.
                </p>

                <div class="hero-actions">
                    <a href="#contacto-formulario" class="btn btn-warning btn-lg px-4">Enviar consulta</a>
                    <a href="{{ route('catalogo') }}" class="btn btn-outline-dark btn-lg px-4">Ver catálogo</a>
                </div>

            </div>

            <!-- ======== HERO: PANEL LATERAL ======== -->
            <div class="col-lg-5">
                <div class="hero-panel contact-hero-panel">
                    <p class="panel-label">Atención disponible</p>

                    <div class="hero-panel-item">
                        <span class="panel-number">01</span>
                        <div>
                            <h3>Contacto físico</h3>
                            <p>Atención en local para coordinación, asesoramiento y seguimiento comercial.</p>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">02</span>
                        <div>
                            <h3>Contacto online</h3>
                            <p>WhatsApp, teléfono y correo para consultas rápidas y cotizaciones.</p>
                        </div>
                    </div>

                    <div class="hero-panel-item">
                        <span class="panel-number">03</span>
                        <div>
                            <h3>Formulario</h3>
                            <p>Un espacio directo dentro de la página para dejar tu mensaje sin salir del sitio.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======== CONTACTO FISICO ======== -->
<section class="home-section contact-section-light">
    <div class="container">
        <div class="section-heading contact-section-heading">
            <span class="home-kicker">Atención presencial</span>
            <h2>Contacto físico en local</h2>
            <p>
                Un espacio pensado para atención comercial, coordinación de retiros y consultas vinculadas
                a productos, disponibilidad y acompañamiento en compras.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <article class="home-card contact-info-card ">
                    <i class="bi bi-geo-alt"></i>
                    <h3>Ubicación</h3>
                    <p>Buenos Aires, Argentina. Punto de referencia para atención y coordinación comercial.</p>
                </article>
            </div>

            <div class="col-md-6 col-xl-3">
                <article class="home-card contact-info-card">
                    <i class="bi bi-clock"></i>
                    <h3>Horarios</h3>
                    <p>Lunes a viernes de 8:00 a 18:00 hs y sábados de 8:00 a 13:00 hs.</p>
                </article>
            </div> 

            <div class="col-md-6 col-xl-3">
                <article class="home-card contact-info-card">
                    <i class="bi bi-box-seam"></i>
                    <h3>Retiros</h3>
                    <p>Coordinación de retiros y entregas previamente confirmadas con el equipo comercial.</p>
                </article>
            </div>

            <div class="col-md-6 col-xl-3">
                <article class="home-card contact-info-card">
                    <i class="bi bi-people"></i>
                    <h3>Atención directa</h3>
                    <p>Asesoramiento para particulares, profesionales y clientes que necesitan una respuesta clara.</p>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- ========= CONTACTO ONLINE ======== -->
<section class="home-section home-section-dark contact-section-dark">
    <div class="container">
        <div class="row align-items-start g-5">

            <!-- COLUMNA IZQUIERDA: TEXTO -->
            <div class="col-lg-5">
                <div class="section-heading contact-section-heading">
                    <span class="home-kicker contact-dark-kicker">Atención remota</span>
                    <h2>Contacto online</h2>
                    <p>
                        Canales directos para consultas, cotizaciones y seguimiento comercial.
                    </p>

                    <p class="contact-online-extra">
                        Si necesitás ampliar la información, más abajo encontrás el formulario de consulta.
                        Esperamos tu mensaje.
                    </p>
                </div>
            </div>

            <!-- COLUMNA DERECHA: CARDS EN COLUMNA -->
            <div class="col-lg-7">
                <div class="contact-online-stack">

                    <article class="feature-item contact-online-card">
                        <h3>Teléfono</h3>
                        <a href="tel:+541100000000" class="contact-online-link">
                            +54 11 0000 0000
                        </a>
                    </article>

                    <article class="feature-item contact-online-card">
                        <h3>WhatsApp</h3>
                        <a href="https://wa.me/5491100000000"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="contact-online-link">
                            +54 9 11 0000 0000
                        </a>
                    </article>

                    <article class="feature-item contact-online-card">
                        <h3>Correo</h3>
                        <a href="mailto:ventas@hierroyforja.com" class="contact-online-link">
                            ventas@hierroyforja.com
                        </a>
                    </article>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- ======== FORMULARIO DE CONSULTA ======== -->
<section id="contacto-formulario" class="home-section contact-form-section">
    <div class="container">
        <div class="contact-form-wrap">
            <div class="row g-4 align-items-center">
                <!-- TEXTO DEL FORMULARIO -->
                <div class="col-lg-5">
                    <div class="contact-form-intro-block">
                        <span class="home-kicker">Escribinos</span>
                        <h2>Formulario de consulta</h2>
                        <p>
                            Completá tus datos y dejá tu mensaje. Este formulario está pensado para mostrar
                            la experiencia de contacto dentro del sitio y brindar una interacción clara,
                            directa y simple para el usuario.
                        </p>
                        <div class="contact-form-support">
                            <div>
                                <strong>Respuesta estimada</strong>
                                <span>Dentro del horario comercial</span>
                            </div>
                            <div>
                                <strong>Canal de retorno</strong>
                                <span>Correo o contacto directo</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM -->
                <div class="col-lg-7">
                    <article class="page-card contact-form-card">
                        <form id="contactoForm" class="contact-form">
                            <!-- NOMBRE COMPLETO -->
                            <div class="contact-form-field">
                                <label for="nombre_completo" class="contact-form-label">Nombre completo</label>
                                <input
                                    type="text"
                                    id="nombre_completo"
                                    name="nombre_completo"
                                    class="form-control contact-form-input"
                                    placeholder="Ingresá tu nombre y apellido"
                                    required
                                >
                            </div>

                            <!-- CORREO -->
                            <div class="contact-form-field">
                                <label for="correo" class="contact-form-label">Correo electrónico</label>
                                <input
                                    type="email"
                                    id="correo"
                                    name="correo"
                                    class="form-control contact-form-input"
                                    placeholder="Ingresá tu correo"
                                    required
                                >
                            </div>

                            <!-- MOTIVO O CONSULTA -->
                            <div class="contact-form-field">
                                <label for="consulta" class="contact-form-label">Motivo o consulta</label>
                                <textarea
                                    id="consulta"
                                    name="consulta"
                                    class="form-control contact-form-textarea"
                                    rows="5"
                                    placeholder="Escribí tu consulta"
                                    required
                                ></textarea>
                            </div>

                            <!-- BOTON -->
                            <div class="contact-form-actions">
                                <button type="submit" class="btn btn-warning contact-form-btn">
                                    Enviar consulta
                                </button>
                            </div>
                        </form>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======== SCRIPT DE ENVIO VISUAL  ======== -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('contactoForm');

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            alert('Gracias, hemos recibido su consulta y le responderemos a la brevedad.');
            form.reset();
        });
    });
</script>
@endsection