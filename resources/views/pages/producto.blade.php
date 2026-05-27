@extends('layouts.app')

@section('title', $producto->nombre . ' | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleProducto.css') }}">
@endpush

@section('contenido')

@php
    $imagenes = $producto->imagenes->sortBy('orden');
    $imagenPrincipal = $imagenes->firstWhere('es_principal', true) ?? $imagenes->first();

    $imagenPrincipalUrl = $imagenPrincipal
        ? asset($imagenPrincipal->url)
        : asset('img/producto-sin-imagen.png');

    $categoriaNombre = $producto->categoria->nombre ?? 'Sin categoría';
    $marcaNombre = $producto->marca->nombre ?? 'Sin marca';

    $energiaTexto = match($producto->energia) {
        'electrica' => 'Eléctrica',
        'manual' => 'Manual',
        'inalambrica' => 'Inalámbrica',
        default => 'No especificada'
    };
@endphp

<section class="page-section product-page">
    <div class="container">

        <!-- ================= BREADCRUMB ================= -->
        <nav class="product-breadcrumb">
            <a href="{{ url('/') }}">Inicio</a>
            <span>/</span>
            <span>{{ $categoriaNombre }}</span>
            <span>/</span>
            <strong>{{ $producto->nombre }}</strong>
        </nav>

        <!-- ================= DETALLE PRINCIPAL ================= -->
        <div class="product-layout">

            <!-- ===== GALERIA ===== -->
            <div class="product-gallery">
                <div class="page-card product-main-image-wrap">

                    @if($imagenes->count() > 1)
                        <button type="button" class="product-gallery-arrow product-gallery-prev" id="galleryPrev" aria-label="Imagen anterior">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    @endif

                    <img 
                        id="productMainImage"
                        src="{{ $imagenPrincipalUrl }}" 
                        alt="{{ $producto->nombre }}" 
                        class="product-main-image"
                    >

                    @if($imagenes->count() > 1)
                        <button type="button" class="product-gallery-arrow product-gallery-next" id="galleryNext" aria-label="Imagen siguiente">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    @endif

                    @if($producto->etiqueta)
                        <span class="product-badge {{ $producto->etiqueta_clase }}">
                            {{ $producto->etiqueta }}
                        </span>
                    @endif
                </div>

                @if($imagenes->count() > 1)
                    <div class="product-thumbs" id="productThumbs">
                        @foreach($imagenes as $index => $imagen)
                            <button 
                                type="button" 
                                class="product-thumb {{ $index === 0 ? 'active' : '' }}"
                                data-index="{{ $index }}"
                                data-image="{{ asset($imagen->url) }}"
                                aria-label="Ver imagen {{ $index + 1 }}"
                            >
                                <img 
                                    src="{{ asset($imagen->url) }}" 
                                    alt="{{ $producto->nombre }} imagen {{ $index + 1 }}"
                                >
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- ===== INFORMACION ===== -->
            <div class="product-info">
                <div class="product-head">
                    <span class="product-brand">{{ $marcaNombre }}</span>

                    <h1 class="product-title">
                        {{ $producto->nombre }}
                    </h1>

                </div>

                <div class="product-price-block">
                    @if($producto->precio_anterior)
                        <small class="product-old-price">
                            ${{ number_format($producto->precio_anterior, 0, ',', '.') }}
                        </small>
                    @endif

                    <strong class="product-price">
                        ${{ number_format($producto->precio, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="page-card product-description-card">
                    <h2>Descripción</h2>
                    <p>{{ $producto->descripcion }}</p>
                </div>

                <div class="product-actions">
                    <div class="product-qty-box">
                        <button type="button" class="product-qty-btn" aria-label="Restar cantidad">
                            <i class="bi bi-dash"></i>
                        </button>

                        <span id="productQty">1</span>

                        <button type="button" class="product-qty-btn" aria-label="Sumar cantidad">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>

                    <button type="button" class="btn btn-warning product-main-btn">
                        <i class="bi bi-cart-plus"></i>
                        Agregar al carrito
                    </button>
                </div>

                <button type="button" class="btn btn-outline-dark product-secondary-btn">
                    Comprar ahora
                </button>

                <div class="product-benefits">
                    <div class="page-card product-benefit-card">
                        <i class="bi bi-truck"></i>
                        <div>
                            <strong>Envíos</strong>
                            <span>Coordinación según zona y producto</span>
                        </div>
                    </div>

                    <div class="page-card product-benefit-card">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <strong>Garantía</strong>
                            <span>Según marca y proveedor</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= ESPECIFICACIONES ================= -->
        <section class="product-section">
            <div class="section-heading">
                <span class="home-kicker">Ficha técnica</span>
                <h2>Especificaciones del producto</h2>
            </div>

            <div class="product-specs-grid">
                <div class="page-card product-meta-card">
                    <span class="product-meta-label">Stock disponible</span>
                    <strong>{{ $producto->stock }}</strong>
                </div>

                <div class="page-card product-meta-card">
                    <span class="product-meta-label">Tipo de energía</span>
                    <strong>{{ $energiaTexto }}</strong>
                </div>

                <div class="page-card product-meta-card">
                    <span class="product-meta-label">Marca</span>
                    <strong>{{ $marcaNombre }}</strong>
                </div>

                <div class="page-card product-meta-card">
                    <span class="product-meta-label">Categoría</span>
                    <strong>{{ $categoriaNombre }}</strong>
                </div>
            </div>
        </section>

        <!-- ================= RELACIONADOS ================= -->
        @if($relacionados->count() > 0)
        <section class="product-section">
            <div class="section-heading">
                <span class="home-kicker">Sugerencias</span>
                <h2>Productos relacionados</h2>
            </div>

            <div class="product-related-grid">
                @foreach($relacionados as $rel)
                    @php
                        $relImagen = $rel->imagenPrincipal?->url ?? 'img/producto-sin-imagen.png';
                    @endphp
                    <article class="page-card product-related-card">
                        <a href="{{ route('producto', $rel->id) }}">
                            <img src="{{ asset($relImagen) }}" alt="{{ $rel->nombre }}" class="product-related-image">
                            <div class="product-related-body">
                                <span class="product-related-brand">{{ $rel->marca->nombre }}</span>
                                <h3>{{ $rel->nombre }}</h3>
                                <span class="product-related-price">
                                    ${{ number_format($rel->precio, 0, ',', '.') }}
                                </span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script>
    window.productoActual = {
        id: {{ $producto->id }},
        nombre: @json($producto->nombre),
        marca: @json($producto->marca->nombre),
        precio: {{ $producto->precio }},
        precioAnterior: {{ $producto->precio_anterior ?? 'null' }},
        imagen: @json($imagenPrincipalUrl),
        categoria: @json($producto->categoria->slug),
        energia: @json($producto->energia),
        ventas: {{ $producto->ventas }},
        descripcion: @json($producto->descripcion),
        etiqueta: @json($producto->etiqueta),
        etiquetaClase: @json($producto->etiqueta_clase),
        stock: {{ $producto->stock }},
    };

    document.addEventListener('DOMContentLoaded', () => {
        const mainImage = document.getElementById('productMainImage');
        const thumbs = Array.from(document.querySelectorAll('.product-thumb'));
        const prevBtn = document.getElementById('galleryPrev');
        const nextBtn = document.getElementById('galleryNext');
        const qtyEl = document.getElementById('productQty');
        const qtyMinus = document.querySelector('[aria-label="Restar cantidad"]');
        const qtyPlus = document.querySelector('[aria-label="Sumar cantidad"]');
        const addToCartBtn = document.querySelector('.product-main-btn');

        let qty = 1;
        let currentIndex = 0;

        // Galería
        function showImage(index) {
            if (index < 0) index = thumbs.length - 1;
            if (index >= thumbs.length) index = 0;
            currentIndex = index;
            if (thumbs.length > 0) {
                mainImage.src = thumbs[currentIndex].dataset.image;
                thumbs.forEach(t => t.classList.remove('active'));
                thumbs[currentIndex].classList.add('active');
            }
        }

        thumbs.forEach((thumb, index) => {
            thumb.addEventListener('click', () => showImage(index));
        });

        prevBtn?.addEventListener('click', () => showImage(currentIndex - 1));
        nextBtn?.addEventListener('click', () => showImage(currentIndex + 1));

        // Cantidad - actualiza visualmente el contador en tiempo real
        qtyMinus?.addEventListener('click', () => {
            qty = Math.max(1, qty - 1);
            qtyEl.textContent = qty;
        });

        qtyPlus?.addEventListener('click', () => {
            qty = Math.min(window.productoActual.stock, qty + 1);
            qtyEl.textContent = qty;
        });

        // Carrito
        addToCartBtn?.addEventListener('click', () => {
            if (!window.CartUtils) return;
            window.CartUtils.addToCart(window.productoActual, qty);
            window.showToast('Producto agregado al carrito');
        });
    });
</script>
@endpush