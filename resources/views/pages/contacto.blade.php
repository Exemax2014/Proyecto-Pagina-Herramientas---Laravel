@extends('layouts.app')

@section('title', 'Contacto | Hierro & Forja')

@section('contenido')
    @php
        $usuarioContacto = session('usuario_id') ? \App\Models\Usuario::find(session('usuario_id')) : null;
    @endphp

    <section class="hero-home contact-hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-8">
                    <h1 class="hero-title">Contacto</h1>
                    <h2>Estamos para responder cada consulta.</h2>

                    <p class="hero-copy">
                        En Hierro &amp; Forja trabajamos con atención comercial directa para ayudarte a resolver consultas,
                        solicitar cotizaciones, coordinar pedidos y recibir orientación sobre herramientas e insumos.
                        Podés comunicarte por nuestros canales online, acercarte al local o dejarnos tu mensaje desde
                        el formulario.
                    </p>

                    <div class="hero-actions">
                        <a href="#contacto-formulario" class="btn btn-warning btn-lg px-4">Enviar consulta</a>
                        <a href="{{ route('catalogo') }}" class="btn btn-outline-dark btn-lg px-4">Ver catálogo</a>
                    </div>
                </div>

                <div class="col-lg-4 terms-summary-column">
                    <div class="hero-panel contact-hero-panel terms-summary-panel">
                        <p class="panel-label">Secciones de página</p>

                        <div class="hero-panel-item">
                            <span class="panel-number">01</span>
                            <div>
                                <h3>Contacto físico</h3>
                            </div>
                        </div>

                        <div class="hero-panel-item">
                            <span class="panel-number">02</span>
                            <div>
                                <h3>Contacto online</h3>
                            </div>
                        </div>

                        <div class="hero-panel-item">
                            <span class="panel-number">03</span>
                            <div>
                                <h3>Formulario</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section contact-section-light">
        <div class="container">
            <div class="section-heading contact-section-heading">
                <span class="home-kicker">Atención presencial</span>
                <h2>Contacto físico en local</h2>

                <p>
                    Nuestro punto de atención presencial está pensado para consultas comerciales, coordinación de retiros,
                    seguimiento de pedidos y asesoramiento sobre productos. Si necesitás resolver una compra puntual,
                    confirmar disponibilidad o coordinar una entrega, podés acercarte o contactarnos previamente para
                    organizar la atención.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <article class="home-card contact-info-card">
                        <i class="bi bi-geo-alt"></i>
                        <h3>Ubicación</h3>
                        <p>
                            Buenos Aires, Argentina. Punto de referencia para atención presencial, consultas comerciales
                            y coordinación de operaciones.
                        </p>
                    </article>
                </div>

                <div class="col-md-6 col-xl-3">
                    <article class="home-card contact-info-card">
                        <i class="bi bi-clock"></i>
                        <h3>Horarios</h3>
                        <p>
                            Lunes a viernes de 8:00 a 18:00 hs y sábados de 8:00 a 13:00 hs. Recomendamos consultar
                            previamente por disponibilidad de productos.
                        </p>
                    </article>
                </div>

                <div class="col-md-6 col-xl-3">
                    <article class="home-card contact-info-card">
                        <i class="bi bi-box-seam"></i>
                        <h3>Retiros</h3>
                        <p>
                            Coordinamos retiros y entregas de pedidos previamente confirmados, evitando demoras y
                            asegurando una atención más ordenada.
                        </p>
                    </article>
                </div>

                <div class="col-md-6 col-xl-3">
                    <article class="home-card contact-info-card">
                        <i class="bi bi-people"></i>
                        <h3>Atención directa</h3>
                        <p>
                            Brindamos asesoramiento para particulares, profesionales, talleres y clientes que necesitan
                            una respuesta clara antes de comprar.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-section-dark">
        <div class="contact-dark-inner">
            <div class="contact-dark-card">
                <div class="row align-items-center g-5">
                    <div class="col-lg-5">
                        <div class="section-heading contact-section-heading">
                            <span class="home-kicker contact-dark-kicker">Atención remota</span>
                            <h2>Contacto online</h2>

                            <p>
                                Nuestros canales online permiten resolver consultas rápidas, pedidos de cotización,
                                seguimiento comercial y coordinación de productos sin necesidad de acercarte al local.
                            </p>

                            <p class="contact-online-extra">
                                Para recibir una respuesta más precisa, indicá qué producto necesitás, cantidad aproximada,
                                uso previsto y si necesitás retiro, entrega o asesoramiento previo.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="contact-online-stack">
                            <a href="tel:+541100000000" class="feature-item contact-online-card">
                                <h3>Teléfono</h3>
                                <span class="contact-online-link">+54 11 0000 0000</span>
                            </a>

                            <a href="https://wa.me/5491100000000"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="feature-item contact-online-card">
                                <h3>WhatsApp</h3>
                                <span class="contact-online-link">+54 9 11 0000 0000</span>
                            </a>

                            <a href="mailto:ventas@hierroyforja.com" class="feature-item contact-online-card">
                                <h3>Correo</h3>
                                <span class="contact-online-link">ventas@hierroyforja.com</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contacto-formulario" class="home-section contact-form-section">
        <div class="container">
            <div class="contact-form-wrap">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-5">
                        <div class="contact-form-intro-block">
                            <h2>Formulario de consulta</h2>

                            <p>
                                Completá tus datos y contanos qué necesitás. Este formulario está pensado para consultas
                                generales, pedidos de cotización, dudas sobre productos, disponibilidad, medios de pago
                                o coordinación de entrega. Cuanto más clara sea la información enviada, más precisa podrá
                                ser la respuesta comercial.
                            </p>

                            <div class="contact-form-support">
                                <div>
                                    <strong>Respuesta estimada</strong>
                                    <span>Dentro del horario comercial, según volumen de consultas.</span>
                                </div>

                                <div>
                                    <strong>Canal de retorno</strong>
                                    <span>Correo, teléfono o contacto directo según los datos ingresados.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <article class="page-card contact-form-card">
                            @if(session('success'))
                                <div class="alert alert-success mb-4">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <strong>Hay errores en el formulario:</strong>

                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form id="contactoForm" class="contact-form" action="{{ route('contacto.store') }}" method="POST">
                                @csrf

                                @if($usuarioContacto)
                                    <div class="contact-form-support mb-4">
                                        <div>
                                            <strong>Nombre</strong>
                                            <span>{{ $usuarioContacto->nombre }} {{ $usuarioContacto->apellido }}</span>
                                        </div>

                                        <div>
                                            <strong>Correo</strong>
                                            <span>{{ $usuarioContacto->email }}</span>
                                        </div>

                                        <div>
                                            <strong>Teléfono</strong>
                                            <span>{{ $usuarioContacto->telefono ?: 'No cargado' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="contact-form-field">
                                        <label for="nombre_completo" class="contact-form-label">Nombre completo</label>
                                        <input
                                            type="text"
                                            id="nombre_completo"
                                            name="nombre_completo"
                                            class="form-control contact-form-input @error('nombre_completo') is-invalid @enderror"
                                            placeholder="Ingresá tu nombre y apellido"
                                            value="{{ old('nombre_completo') }}"
                                            required
                                        >

                                        @error('nombre_completo')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="contact-form-field">
                                        <label for="correo" class="contact-form-label">Correo electrónico</label>
                                        <input
                                            type="email"
                                            id="correo"
                                            name="correo"
                                            class="form-control contact-form-input @error('correo') is-invalid @enderror"
                                            placeholder="Ingresá tu correo"
                                            value="{{ old('correo') }}"
                                            required
                                        >

                                        @error('correo')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="contact-form-field">
                                        <label for="telefono" class="contact-form-label">Teléfono</label>
                                        <input
                                            type="text"
                                            id="telefono"
                                            name="telefono"
                                            class="form-control contact-form-input @error('telefono') is-invalid @enderror"
                                            placeholder="Ingresá tu teléfono"
                                            value="{{ old('telefono') }}"
                                            required
                                        >

                                        @error('telefono')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                @endif

                                <div class="contact-form-field">
                                    <label for="consulta" class="contact-form-label">Motivo o consulta</label>
                                    <textarea
                                        id="consulta"
                                        name="consulta"
                                        class="form-control contact-form-textarea @error('consulta') is-invalid @enderror"
                                        rows="5"
                                        placeholder="Escribí tu consulta"
                                        required
                                    >{{ old('consulta') }}</textarea>

                                    @error('consulta')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

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
@endsection
