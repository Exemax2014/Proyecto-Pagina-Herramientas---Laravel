@extends('layouts.app')

@section('contenido')
<section class="page-section">
    <div class="container">
        <div class="page-hero">
            <span class="home-kicker">Condiciones generales</span>
            <h1>T&eacute;rminos</h1>
            <p>
                Esta vista deja preparado un espacio institucional para publicar condiciones de uso, pol&iacute;ticas
                comerciales y aclaraciones relevantes del sitio.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <article class="page-card h-100">
                    <h2>Uso del sitio</h2>
                    <p>La navegaci&oacute;n tiene fines informativos y comerciales dentro del marco definido por la empresa.</p>
                </article>
            </div>
            <div class="col-lg-4">
                <article class="page-card h-100">
                    <h2>Informaci&oacute;n</h2>
                    <p>Los contenidos podr&aacute;n ampliarse o ajustarse a medida que se agreguen productos y servicios.</p>
                </article>
            </div>
            <div class="col-lg-4">
                <article class="page-card h-100">
                    <h2>Actualizaciones</h2>
                    <p>La estructura ya queda lista para reemplazar estos textos por contenido legal definitivo.</p>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
