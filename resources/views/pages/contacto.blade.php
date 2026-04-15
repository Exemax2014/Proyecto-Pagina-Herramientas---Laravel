@extends('layouts.app')

@section('contenido')
<section class="page-section">
    <div class="container">
        <div class="page-hero">
            <span class="home-kicker">Canales de atenci&oacute;n</span>
            <h1>Contacto</h1>
            <p>
                Dejamos una estructura simple para que el sitio ya cuente con un espacio claro de contacto,
                preparado para sumar datos definitivos cuando los tengan.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <article class="page-card h-100">
                    <h2>Informaci&oacute;n b&aacute;sica</h2>
                    <div class="contact-list">
                        <div>
                            <span class="contact-label">Correo</span>
                            <p>ventas@hierroyforja.com</p>
                        </div>
                        <div>
                            <span class="contact-label">Tel&eacute;fono</span>
                            <p>+54 11 0000 0000</p>
                        </div>
                        <div>
                            <span class="contact-label">Direcci&oacute;n</span>
                            <p>Buenos Aires, Argentina</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-lg-6">
                <article class="page-card page-card-dark h-100">
                    <h2>Atenci&oacute;n comercial</h2>
                    <p>
                        Si necesit&aacute;s una cotizaci&oacute;n o quer&eacute;s avanzar con un pedido, tambi&eacute;n pod&eacute;s usar la
                        secci&oacute;n de consultas para dejar un mensaje organizado.
                    </p>
                    <a href="{{ route('consultas') }}" class="btn btn-outline-warning mt-2">Ir a consultas</a>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
