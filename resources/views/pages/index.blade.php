@extends('layouts.app')

@section('title', 'Inicio | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleIndex.css') }}">
<link rel="stylesheet" href="{{ asset('css/product-cards.css') }}">
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
        <div class="home-offers-head">
            <span class="home-kicker">
                <i class="bi bi-tag-fill"></i> Ofertas destacadas
            </span>

            <div class="home-offers-controls">
                <button type="button" class="home-offers-arrow" id="offersPrev" aria-label="Ver ofertas anteriores">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <button type="button" class="home-offers-arrow" id="offersNext" aria-label="Ver más ofertas">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="home-offers-carousel" id="homeOffers"></div>
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

@php
    $catalogoProductosHome = $productosHome->map(function ($producto) {
        return [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'precio' => (float) $producto->precio,
            'precioAnterior' => $producto->precio_anterior !== null ? (float) $producto->precio_anterior : null,
            'ventas' => (int) $producto->ventas,
            'energia' => $producto->energia,
            'etiqueta' => $producto->etiqueta,
            'etiquetaClase' => $producto->etiqueta_clase,
            'categoria' => $producto->categoria?->slug,
            'categoriaNombre' => $producto->categoria?->nombre,
            'marca' => $producto->marca?->nombre,
            'imagen' => $producto->imagenPrincipal?->url ?? '/img/productos/default.jpg',
        ];
    })->values();
@endphp

@push('scripts')
<script>
    window.routeCatalogoBase = "{{ route('catalogo') }}";
    window.routeProductoBase = "{{ url('/producto') }}";
    window.catalogoProductos = @json($catalogoProductosHome);
</script>

<script src="{{ asset('js/index.js') }}"></script>
@endpush