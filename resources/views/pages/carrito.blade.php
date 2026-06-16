@extends('layouts.app')

@section('title', 'Carrito | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleCarrito.css') }}">
@endpush

@section('contenido')
<section class="page-section cart-page">
    <div class="container">
        @include('checkout.partials.timeline', ['currentStep' => 'carrito'])

        <div class="cart-layout cart-layout--step-one">
            <div class="cart-main">
                <section class="cart-section">
                    <div class="cart-hero">
                        <h2>PASO 1: Productos del carrito</h2>
                        </div>

                    <div class="alert d-none" id="cartFeedback" role="alert"></div>

                    <div class="cart-items-wrap" id="cartItemsWrap"></div>

                    <div class="page-card cart-empty-state d-none" id="cartEmptyState">
                        <i class="bi bi-cart-x"></i>
                        <h3>Tu carrito está vacío</h3>
                        <p>Cuando agregues productos, los vas a ver listados acá.</p>
                        <a href="{{ route('catalogo') }}" class="btn btn-warning">Ir al catálogo</a>
                    </div>
                </section>
            </div>

            <aside class="cart-sidebar">
                <div class="page-card cart-summary-card cart-summary-card--sticky">
                    <div class="cart-summary-head">
                        <h2>RESUMEN DEL PEDIDO</h2>
                    </div>

                    <div class="cart-summary-lines">
                        <div class="cart-summary-line">
                            <span>Subtotal</span>
                            <strong id="cartSubtotal">$0</strong>
                        </div>

                        <div class="cart-summary-line">
                            <span>Envío estimado</span>
                            <strong id="cartShipping">$0</strong>
                        </div>

                        <div class="cart-summary-line">
                            <span>Descuento</span>
                            <strong id="cartDiscount">$0</strong>
                        </div>
                    </div>

                    <div class="cart-summary-total">
                        <span>Total</span>
                        <strong id="cartTotal">$0</strong>
                    </div>

                    <div class="cart-summary-actions">
                        <button
                            type="button"
                            class="btn btn-warning cart-confirm-btn"
                            id="cartConfirmBtn"
                            data-checkout-url="{{ route('carrito.datos') }}"
                        >
                            Continuar con datos
                        </button>

                        <a href="{{ route('catalogo') }}" class="btn btn-outline-dark cart-continue-btn">
                            Seguir comprando
                        </a>
                    </div>
                </div>

                <div class="cart-benefits-grid">
                    <div class="page-card cart-benefit-card">
                        <i class="bi bi-truck"></i>
                        <div>
                            <strong>Envíos</strong>
                            <span>Coordinación rápida según zona y volumen.</span>
                        </div>
                    </div>

                    <div class="page-card cart-benefit-card">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <strong>Respaldo</strong>
                            <span>Pedido claro, stock validado y seguimiento.</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/carrito.js') }}?v={{ filemtime(public_path('js/carrito.js')) }}"></script>
@endpush
