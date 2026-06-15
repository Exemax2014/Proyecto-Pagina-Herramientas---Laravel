@extends('layouts.app')

@section('title', 'Carrito | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleCarrito.css') }}">
@endpush

@section('contenido')
<section class="page-section cart-page">
    <div class="container">
        <div class="cart-hero">
            <span class="home-kicker">Checkout Hierro & Forja</span>
            <h1 class="cart-page-title">PRODUCTOS DEL CARRITO</h1>
            <p class="cart-hero-copy">Revisá tus productos, ajustá cantidades y dejá listo el pedido para continuar con tus datos.</p>
        </div>

        <div class="cart-steps" aria-label="Pasos del checkout">
            <div class="cart-step is-active">
                <span class="cart-step-number">1</span>
                <div>
                    <strong>Carrito</strong>
                    <span>Productos seleccionados</span>
                </div>
            </div>
            <div class="cart-step">
                <span class="cart-step-number">2</span>
                <div>
                    <strong>Datos</strong>
                    <span>Entrega y contacto</span>
                </div>
            </div>
            <div class="cart-step">
                <span class="cart-step-number">3</span>
                <div>
                    <strong>Confirmación</strong>
                    <span>Pedido realizado</span>
                </div>
            </div>
        </div>

        <div class="cart-layout">
            <div class="cart-main">
                <section class="cart-section">
                    <div class="section-heading cart-section-heading">
                        <h2>Productos del carrito</h2>
                        <p>Vas a poder editar cantidades o eliminar artículos antes de avanzar al paso 2.</p>
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
                <div class="page-card cart-summary-card">
                    <div class="cart-summary-head">
                        <h2>Resumen del pedido</h2>
                        <p>El paso 1 no confirma el pedido. Solo prepara el checkout.</p>
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

                    <div class="cart-payment-methods">
                        <h3>Método de pago</h3>

                        <label class="cart-payment-option">
                            <input type="radio" name="payment_method" value="tarjeta" checked>
                            <div>
                                <strong>Tarjeta</strong>
                                <span>Débito, crédito o coordinación comercial</span>
                            </div>
                        </label>

                        <label class="cart-payment-option">
                            <input type="radio" name="payment_method" value="efectivo">
                            <div>
                                <strong>Efectivo / contra entrega</strong>
                                <span>Pago acordado al retirar o recibir</span>
                            </div>
                        </label>
                    </div>

                    <div class="cart-summary-actions">
                        <button
                            type="button"
                            class="btn btn-warning cart-confirm-btn"
                            id="cartConfirmBtn"
                            data-checkout-url="{{ route('carrito.datos') }}"
                        >
                            Confirmar pedido
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
