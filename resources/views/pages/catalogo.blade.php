@extends('layouts.app')

@section('title', 'Catálogo | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleCatalogo.css') }}">
<link rel="stylesheet" href="{{ asset('css/product-cards.css') }}">
@endpush

@section('contenido')
<section class="page-section catalog-page">
    <div class="container">

        <!-- ================= HERO DE CATALOGO ================= -->
        <div class="catalog-hero catalog-hero-compact">
            <div class="catalog-hero-inline">
                <span class="home-kicker">Catálogo comercial</span>
                <p class="catalog-hero-copy">Herramientas, insumos y soluciones para trabajo técnico, comercial e industrial.</p>
            </div>
        </div>

        <!-- ================= SHELL PRINCIPAL ================= -->
        <div class="catalog-shell">
            <!-- ===== CONTENIDO GENERAL ===== -->
            <div class="catalog-content">

                <div> <!-- ===== SIDEBAR FILTROS DESKTOP / DROPDOWN RESPONSIVE ===== -->
                    <aside class="catalog-sidebar" id="catalogSidebar">
                        <div class="page-card catalog-filter-card">
                            <div class="catalog-filter-head">
                                <h2>Filtros</h2>
                                <button type="button" class="catalog-filter-close" id="catalogFilterClose" aria-label="Cerrar filtros">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <div class="catalog-filter-columns">
                                <div class="catalog-filter-group">
                                    <h3>Categoría</h3>

                                    <label class="catalog-check">
                                        <input type="checkbox" class="filter-category" value="construccion">
                                        <span>Construcción</span>
                                    </label>

                                    <label class="catalog-check">
                                        <input type="checkbox" class="filter-category" value="herreria">
                                        <span>Herrería</span>
                                    </label>

                                    <label class="catalog-check">
                                        <input type="checkbox" class="filter-category" value="carpinteria">
                                        <span>Carpintería</span>
                                    </label>

                                    <label class="catalog-check">
                                        <input type="checkbox" class="filter-category" value="durlok">
                                        <span>Durlok</span>
                                    </label>

                                    <label class="catalog-check">
                                        <input type="checkbox" class="filter-category" value="pintureria">
                                        <span>Pinturería</span>
                                    </label>

                                    <label class="catalog-check">
                                        <input type="checkbox" class="filter-category" value="ferreteria">
                                        <span>Ferretería</span>
                                    </label>
                                </div>

                                <div class="catalog-filter-group">
                                    <h3>Marca</h3>
                                    <div id="brandFilters"></div>
                                </div>

                                <div class="catalog-filter-group">
                                    <h3>Tipo de energía</h3>

                                    <label class="catalog-radio">
                                        <input type="radio" name="energy" value="" checked>
                                        <span>Todas</span>
                                    </label>

                                    <label class="catalog-radio">
                                        <input type="radio" name="energy" value="manual">
                                        <span>Manual</span>
                                    </label>

                                    <label class="catalog-radio">
                                        <input type="radio" name="energy" value="electrica">
                                        <span>Eléctrica</span>
                                    </label>

                                    <label class="catalog-radio">
                                        <input type="radio" name="energy" value="inalambrica">
                                        <span>Inalámbrica</span>
                                    </label>
                                </div>
                            </div>

                            <div class="catalog-filter-group catalog-filter-group-price">
                                <h3>Precio máximo</h3>
                                <input
                                    type="range"
                                    id="catalogPriceRange"
                                    min="0"
                                    max="300000"
                                    step="5000"
                                    value="300000"
                                    class="form-range"
                                >
                                <div class="catalog-price-values">
                                    <span>$0</span>
                                    <strong id="catalogPriceValue">$300.000</strong>
                                </div>
                            </div>

                            <div class="catalog-filter-actions">
                                <button type="button" class="btn btn-outline-dark" id="resetCatalogFilters">
                                    Limpiar filtros
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
                
                <!-- ===== COLUMNA DERECHA: BUSCADOR + PRODUCTOS ===== -->
                <div class="catalog-results-column">
                    <!-- ================= BARRA SUPERIOR ================= -->
                    <div class="catalog-toolbar">
                        <button type="button" class="btn btn-outline-dark catalog-filter-toggle" id="catalogFilterToggle">
                            <i class="bi bi-sliders"></i>
                            Filtros
                        </button>

                        <div class="catalog-search-wrap">
                            <i class="bi bi-search"></i>
                            <input
                                type="text"
                                id="catalogSearch"
                                class="catalog-search-input"
                                placeholder="Buscar productos..."
                            >
                        </div>

                        <select id="catalogSortMarket" class="form-select catalog-sort-select">
                            <option value="default">Precios</option>
                            <option value="price-asc">Precio: menor a mayor</option>
                            <option value="price-desc">Precio: mayor a menor</option>
                            <option value="best-sellers">Más vendidos</option>
                        </select>

                        <select id="catalogSortName" class="form-select catalog-sort-select">
                            <option value="default">Nombres</option>
                            <option value="name-asc">A - Z</option>
                            <option value="name-desc">Z - A</option>
                        </select>
                    </div>

                    <!-- ===== GRILLA ===== -->
                    <div class="catalog-main">
                        <div class="catalog-grid" id="catalogGrid"></div>

                        <div class="catalog-empty-state d-none" id="catalogEmptyState">
                            <div class="page-card catalog-empty-card">
                                <i class="bi bi-search"></i>
                                <h3>No encontramos productos</h3>
                                <p>Probá cambiando los filtros o limpiando la búsqueda.</p>
                            </div>
                        </div>

                        <nav class="catalog-pagination"></nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    window.routeProductoBase = "{{ url('/producto') }}";
</script>
<script src="{{ asset('js/catalogo-productos.js') }}"></script>
<script src="{{ asset('js/catalogo.js') }}"></script>
@endpush