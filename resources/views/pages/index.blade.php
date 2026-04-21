@extends('layouts.app')

@section('title', 'Inicio | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleIndex.css') }}">
@endpush

@section('contenido')

<!-- ================= HERO ================= -->
<section class="home-hero">
    <div class="home-hero-overlay"></div>

    <div class="container home-hero-content">
        <span class="home-kicker">Equipamiento profesional</span>

        <h1>HERRAMIENTAS PARA TRABAJO REAL</h1>

        <p>
            Soluciones en ferretería, construcción, herrería, carpintería y trabajo en seco para profesionales exigentes.
        </p>

        <div class="home-hero-actions">
            <a href="{{ route('catalogo') }}" class="btn btn-warning btn-lg px-4">
                Ver catálogo
            </a>

            <a href="{{ route('contacto') }}#contacto-formulario" class="btn btn-outline-light btn-lg px-4">
                Hacer una consulta
            </a>
        </div>
    </div>
</section>

<!-- ================= MARCAS AUTOMATICAS ================= -->
<section class="home-brands">
    <div class="container">
        <div id="homeBrands" class="home-brands-list"></div>
    </div>
</section>

<!-- ================= CATEGORIAS ================= -->
<section class="home-categories">
    <div class="container">
        <div class="section-heading">
            <span class="home-kicker"><i class="bi bi-grid-1x2-fill"></i>  Explorar categorías</span>
            <p>Accedé al catálogo según el área de trabajo y entrá directamente con la categoría ya seleccionada.</p>
        </div>

         <div class="home-categories-grid" id="homeCategoriesGrid"></div>
    </div>
</section>

<!-- ================= OFERTAS DESTACADAS ================= -->
<section class="home-offers">
    <div class="container">
        <span class="home-kicker"><i class="bi bi-tag-fill"></i>  Ofertas Destacadas</span>
        <div class="home-products-grid" id="homeOffers"></div>
    </div>
</section>

<!-- ================= POR QUE ELEGIRNOS ================= -->
<section class="home-section home-section-dark home-benefits-dark">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <span class="home-kicker">Confianza</span>
                <h2>¿Por qué elegirnos?</h2>
                <p class="mb-4">
                    Acompañamos cada compra con variedad, asesoramiento y productos pensados para trabajo real.
                    No se trata solo de vender herramientas, sino de ayudarte a comprar bien según tu necesidad.
                </p>

                <a href="{{ route('quienes-somos') }}" class="btn btn-outline-warning">
                    Conocer la empresa
                </a>
            </div>

            <div class="col-lg-6">
                <div class="feature-stack">
                    <div class="feature-item">
                        <h3>Calidad garantizada</h3>
                        <p>Trabajamos con marcas líderes y productos preparados para uso intensivo.</p>
                    </div>

                    <div class="feature-item">
                        <h3>Envíos y logística</h3>
                        <p>Coordinamos entregas, retiros y pedidos especiales con claridad y respuesta rápida.</p>
                    </div>

                    <div class="feature-item">
                        <h3>Asesoramiento real</h3>
                        <p>Te ayudamos a elegir según tu rubro, necesidad y presupuesto, con criterio práctico.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= CTA COTIZACION FUERTE ================= -->
<div class="home-cta-box">
    <h2 class="home-cta-title">¿Necesitás una compra por volumen?</h2>
    <div class="home-cta-bottom">
        <div class="home-cta-copy">
            <p>
                Nuestro equipo puede ayudarte con presupuestos personalizados, selección de materiales
                y coordinación comercial según el tipo de proyecto.
            </p>
        </div>

        <div class="home-cta-actions">
            <a href="{{ route('contacto') }}#contacto-formulario" class="btn btn-warning btn-cta">
                Contactanos
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    window.routeCatalogoBase = "{{ route('catalogo') }}";
    window.routeProductoBase = "{{ url('/producto') }}";
</script>
<script src="{{ asset('js/catalogo-productos.js') }}"></script>
<script src="{{ asset('js/index.js') }}"></script>
@endpush