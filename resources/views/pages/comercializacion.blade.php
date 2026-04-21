@extends('layouts.app')

@section('title', 'Comercialización | Hierro & Forja')

@section('contenido')
<section class="page-section">
    <div class="container">
        <div class="page-hero">
            <span class="home-kicker">Cobertura comercial</span>
            <h1>Comercializaci&oacute;n</h1>
            <p>
                Centralizamos una propuesta orientada a talleres, obras y clientes que necesitan herramientas
                de uso intensivo con respuesta &aacute;gil y acompa&ntilde;amiento comercial.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <article class="page-card h-100">
                    <h2>Pagos y envíos</h2>
                    <p>
                        Consultá todos los medios de pago, formas de envío y horarios de entrega disponibles.
                    </p>
                    <a href="{{ url('/pagos-envios') }}" class="btn btn-dark mt-3"> 
                        Ver información
                    </a>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="page-card h-100">
                    <h2>Asesoramiento</h2>
                    <p>Orientaci&oacute;n sobre l&iacute;neas de producto, usos recomendados y alternativas equivalentes.</p>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="page-card h-100">
                    <h2>Coordinaci&oacute;n</h2>
                    <p>Seguimiento ordenado de pedidos para facilitar entregas y reposiciones.</p>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="page-card h-100">
                    <h2>Expansi&oacute;n</h2>
                    <p>Base visual preparada para sumar marcas, rubros y acciones comerciales futuras.</p>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
