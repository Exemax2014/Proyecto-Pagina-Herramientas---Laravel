@extends('layouts.app')

@section('title', 'Detalle del producto | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleProducto.css') }}">
@endpush

@section('contenido')
<section class="page-section product-page">
    <div class="container">

        <!-- ================= BREADCRUMB ================= -->
        <nav class="product-breadcrumb" id="productBreadcrumb"></nav>

        <!-- ================= DETALLE PRINCIPAL ================= -->
        <div class="product-layout" id="productDetailWrap">

            <!-- ===== GALERIA ===== -->
            <div class="product-gallery">
                <div class="page-card product-main-image-wrap">
                    <img id="productMainImage" src="" alt="" class="product-main-image">
                    <span id="productBadge" class="product-badge d-none"></span>
                </div>

                <div class="product-thumbs" id="productThumbs"></div>
            </div>

            <!-- ===== INFORMACION ===== -->
            <div class="product-info">
                <div class="product-head">
                    <span class="product-brand" id="productBrand"></span>
                    <h1 class="product-title" id="productTitle"></h1>
                    <p class="product-short" id="productShort"></p>
                </div>

                <div class="product-price-block">
                    <small id="productOldPrice" class="product-old-price d-none"></small>
                    <strong id="productPrice" class="product-price"></strong>
                </div>

                <div class="product-meta-grid">
                    <div class="page-card product-meta-card">
                        <span class="product-meta-label">Categoría</span>
                        <strong id="productCategory"></strong>
                    </div>

                    <div class="page-card product-meta-card">
                        <span class="product-meta-label">Marca</span>
                        <strong id="productBrandMeta"></strong>
                    </div>

                    <div class="page-card product-meta-card">
                        <span class="product-meta-label">Energía</span>
                        <strong id="productEnergy"></strong>
                    </div>

                    <div class="page-card product-meta-card">
                        <span class="product-meta-label">Ventas</span>
                        <strong id="productSales"></strong>
                    </div>
                </div>

                <div class="page-card product-description-card">
                    <h2>Descripción</h2>
                    <p id="productDescription"></p>
                </div>

                <div class="product-actions">
                    <div class="product-qty-box">
                        <button type="button" class="product-qty-btn" id="qtyMinus" aria-label="Restar cantidad">
                            <i class="bi bi-dash"></i>
                        </button>
                        <span id="productQty">1</span>
                        <button type="button" class="product-qty-btn" id="qtyPlus" aria-label="Sumar cantidad">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>

                    <button type="button" class="btn btn-warning product-main-btn" id="productAddToCartBtn">
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

            <div class="product-specs-grid" id="productSpecs"></div>
        </section>

        <!-- ================= RELACIONADOS ================= -->
        <section class="product-section">
            <div class="section-heading">
                <span class="home-kicker">Sugerencias</span>
                <h2>Productos relacionados</h2>
            </div>

            <div class="product-related-grid" id="relatedProducts"></div>
        </section>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/catalogo-productos.js') }}"></script>
<script src="{{ asset('js/producto-detalle.js') }}"></script>
@endpush