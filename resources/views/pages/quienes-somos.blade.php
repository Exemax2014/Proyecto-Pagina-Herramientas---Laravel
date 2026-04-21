@extends('layouts.app')

@section('title', 'Quiénes somos | Hierro & Forja')

@section('contenido')
<section class="page-section">
    <div class="container">

        <!-- HERO -->
        <div class="page-hero">
            <span class="home-kicker">Nuestra identidad</span>
            <h1>Quiénes somos</h1>
            <p>
                Somos un equipo orientado a la provisión de herramientas e insumos para trabajo profesional,
                con foco en atención directa, respuesta rápida y productos confiables.
            </p>
        </div>

        <!-- BLOQUES PRINCIPALES -->
        <div class="row g-4">

            <!-- IZQUIERDA -->
            <div class="col-lg-7">
                <article class="page-card">
                    <h2>Una empresa pensada para acompañar el trabajo real</h2>
                    <p>
                        En Hierro & Forja buscamos que cada cliente encuentre una propuesta clara, seria y útil.
                        Trabajamos sobre una base de productos que responden bien en obra, taller y mantenimiento,
                        con una comunicación cercana para que la compra sea más simple.
                    </p>
                    <p>
                        La idea de esta página es mostrar una presencia profesional desde el inicio y dejar una base
                        lista para seguir creciendo con catálogo, consultas y nuevas secciones comerciales.
                    </p>

                    <!-- NUEVO: MISION Y VISION -->
                    <div class="mt-4">
                        <h3>Nuestra misión</h3>
                        <p>
                            Brindar herramientas e insumos de calidad que acompañen el trabajo profesional,
                            ofreciendo soluciones prácticas, duraderas y accesibles para cada cliente.
                        </p>

                        <h3>Nuestra visión</h3>
                        <p>
                            Ser una referencia en el rubro, destacándonos por la atención personalizada,
                            la confianza y la mejora continua en nuestros productos y servicios.
                        </p>
                    </div>

                    <!-- NUEVO: IMAGEN -->
                    <div class="mt-4">
                        <img src="{{ asset('img/empresa.jpg') }}" 
                             class="img-fluid rounded shadow" 
                             alt="Empresa Hierro y Forja">
                    </div>

                    <!-- NUEVO: EQUIPO -->
                    <div class="mt-4">
                        <h3>Nuestro equipo</h3>
                        <p class="mb-0">
                            Contamos con un equipo comprometido con brindar soluciones rápidas y efectivas,
                            acompañando a cada cliente en su proceso de compra y uso de herramientas.
                        </p>
                    </div>

                </article>
            </div>

            <!-- DERECHA -->
            <div class="col-lg-5">
                <article class="page-card page-card-dark">
                    <h2>Nuestros pilares</h2>
                    <ul class="page-list">
                        <li>Atención personalizada para cada necesidad.</li>
                        <li>Selección de herramientas orientadas a durabilidad.</li>
                        <li>Imagen comercial consistente y profesional.</li>
                        <li>Base preparada para ampliar contenidos más adelante.</li>
                    </ul>
                </article>
            </div>

        </div>
    </div>
</section>
@endsection