@extends('layouts.app')

@section('contenido')
<section class="page-section">
    <div class="container">
        <div class="page-hero">
            <span class="home-kicker">Primer contacto</span>
            <h1>Consultas</h1>
            <p>
                Dejamos una presentaci&oacute;n simple para que el usuario identifique r&aacute;pido d&oacute;nde iniciar
                una consulta comercial o t&eacute;cnica.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <article class="page-card h-100">
                    <h2>&iquest;En qu&eacute; podemos ayudarte?</h2>
                    <ul class="page-list">
                        <li>Cotizaciones y disponibilidad de productos.</li>
                        <li>Orientaci&oacute;n seg&uacute;n rubro o tipo de trabajo.</li>
                        <li>Consultas sobre marcas, l&iacute;neas y usos recomendados.</li>
                        <li>Coordinaci&oacute;n de contacto comercial posterior.</li>
                    </ul>
                </article>
            </div>
            <div class="col-lg-5">
                <article class="page-card page-card-dark h-100">
                    <h2>Canales sugeridos</h2>
                    <p>Pod&eacute;s derivar al cliente a contacto directo o sumar m&aacute;s adelante un formulario real.</p>
                    <div class="hero-actions mt-3">
                        <a href="{{ route('contacto') }}" class="btn btn-warning">Ver contacto</a>
                        <a href="{{ route('catalogo') }}" class="btn btn-outline-light">Explorar cat&aacute;logo</a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
