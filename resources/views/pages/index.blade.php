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
        <div class="home-brands-list brands-count-{{ $marcasHome->count() }}">
            @foreach($marcasHome as $index => $marca)
                <a href="{{ route('catalogo', ['marca' => $marca->nombre]) }}" class="home-brand-link brand-slot-{{ $index + 1 }}">
                    <span class="home-brand">{{ $marca->nombre }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ================= CATEGORIAS ================= -->
<section class="home-categories">
    <div class="container">
        <div class="section-heading">
            <span class="home-kicker"><i class="bi bi-grid-1x2-fill"></i>  Explorar categorías</span>
            <p>Accedé al catálogo según el área de trabajo y entrá directamente con la categoría ya seleccionada.</p>
        </div>

        <div class="home-categories-grid">
            @foreach($categoriasHome as $categoria)
                <a
                    href="{{ route('catalogo', ['categoria' => $categoria->slug]) }}"
                    class="category-card {{ $categoria->clase }}"
                    style="--category-image: url('{{ $categoria->imagen }}')"
                >
                    <span>{{ $categoria->nombre }}</span>
                </a>
            @endforeach
        </div>
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

        <div class="home-offers-carousel" id="homeOffers">
            @foreach($ofertasHome as $producto)
                @php
                    $imagen = $producto->imagenPrincipal?->url
                        ? asset($producto->imagenPrincipal->url)
                        : asset('img/producto-sin-imagen.png');
                @endphp

                <article
                    class="page-card product-card home-product-card"
                    data-product-link="{{ route('producto', $producto->id) }}"
                    role="link"
                    tabindex="0"
                    aria-label="Ver detalle de {{ $producto->nombre }}"
                >
                    <div class="product-card-media">
                        <img src="{{ $imagen }}" alt="{{ $producto->nombre }}">

                        @if($producto->etiqueta)
                            <span class="product-card-badge {{ $producto->etiqueta_clase }}">
                                {{ $producto->etiqueta }}
                            </span>
                        @endif

                        <button
                            class="product-card-action home-cart-btn"
                            type="button"
                            aria-label="Agregar {{ $producto->nombre }} al carrito"
                            data-product-id="{{ $producto->id }}"
                            data-product-nombre="{{ $producto->nombre }}"
                            data-product-marca="{{ $producto->marca?->nombre }}"
                            data-product-categoria="{{ $producto->categoria?->slug }}"
                            data-product-energia="{{ $producto->energia }}"
                            data-product-precio="{{ (float) $producto->precio }}"
                            data-product-imagen="{{ $imagen }}"
                        >
                            <i class="bi bi-cart-plus"></i>
                        </button>
                    </div>

                    <div class="product-card-body">
                        <span class="product-card-brand">{{ $producto->marca?->nombre }}</span>
                        <h3>{{ $producto->nombre }}</h3>
                        <p>{{ $producto->descripcion }}</p>

                        <div class="product-card-footer">
                            <div class="product-card-price">
                                @if($producto->precio_anterior)
                                    <small>${{ number_format($producto->precio_anterior, 0, ',', '.') }}</small>
                                @endif

                                <strong>${{ number_format($producto->precio, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
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
<script src="{{ asset('js/index.js') }}"></script>
@endpush
