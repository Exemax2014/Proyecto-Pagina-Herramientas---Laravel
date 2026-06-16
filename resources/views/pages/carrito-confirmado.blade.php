@extends('layouts.app')

@section('title', 'Pedido confirmado | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleCarrito.css') }}">
@endpush

@section('contenido')
<section class="page-section cart-page">
    <div class="container">
        @include('checkout.partials.timeline', ['currentStep' => 'confirmacion'])

        <div class="cart-hero">
            <h1 class="cart-page-title">PEDIDO CONFIRMADO</h1>
            <p class="cart-hero-copy">Tu pedido ya quedo registrado. A partir de ahora podes seguir su estado desde tu cuenta.</p>
        </div>

        <div class="cart-layout cart-layout--confirmed">
            <div class="cart-main">
                <section class="page-card cart-confirmed-hero">
                    <div class="cart-confirmed-badge">
                        <i class="bi bi-check2-circle"></i>
                        <span>Pedido registrado con exito</span>
                    </div>

                    <div class="cart-confirmed-grid">
                        <div>
                            <span class="cart-confirmed-label">Numero de pedido</span>
                            <strong>{{ $pedido->codigo_visible }}</strong>
                        </div>
                        <div>
                            <span class="cart-confirmed-label">Fecha de confirmacion</span>
                            <strong>{{ $pedido->fecha_confirmacion?->format('d/m/Y H:i') ?? '-' }}</strong>
                        </div>
                        <div>
                            <span class="cart-confirmed-label">Metodo de pago</span>
                            <strong>{{ $pedido->metodo_pago === 'efectivo' ? 'Efectivo / contra entrega' : 'Tarjeta' }}</strong>
                        </div>
                        <div>
                            <span class="cart-confirmed-label">Modo de entrega</span>
                            <strong>{{ $pedido->modo_entrega === 'envio_domicilio' ? 'Coordinacion de envio' : 'Retiro en local' }}</strong>
                        </div>
                    </div>
                </section>

                <section class="page-card cart-confirmed-items-card">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h2 class="h4 mb-1">Resumen real del pedido</h2>
                            <p class="text-muted mb-0">Los importes ya quedaron cerrados al momento de confirmar.</p>
                        </div>
                    </div>

                    <div class="cart-confirmed-items">
                        @foreach($pedido->items as $item)
                            <article class="cart-summary-item">
                                <div class="cart-summary-item-media">
                                    <img src="{{ $item->producto?->imagenPrincipal?->url ? asset($item->producto->imagenPrincipal->url) : asset('img/producto-sin-imagen.svg') }}" alt="{{ $item->producto_nombre ?: 'Producto' }}">
                                </div>
                                <div class="cart-summary-item-body">
                                    <span class="cart-item-brand">{{ $item->producto_marca ?: ($item->producto?->marca?->nombre ?? 'Sin marca') }}</span>
                                    <strong>{{ $item->producto_nombre ?: ($item->producto?->nombre ?? 'Producto sin nombre') }}</strong>
                                    <span class="cart-summary-item-meta">{{ $item->producto_categoria ?: ($item->producto?->categoria?->nombre ?? 'Sin categoria') }}</span>
                                    <span class="cart-summary-item-meta">Cantidad: {{ $item->cantidad }}</span>
                                </div>
                                <div class="cart-summary-item-price">
                                    <strong>${{ number_format((float) $item->subtotal, 0, ',', '.') }}</strong>
                                    <span>${{ number_format((float) $item->precio_unitario, 0, ',', '.') }} por unidad</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>

            <aside class="cart-sidebar">
                <div class="page-card cart-summary-card cart-summary-card--sticky">
                    <div class="cart-summary-head">
                        <h2>Total del pedido</h2>
                    </div>

                    <div class="cart-summary-lines">
                        <div class="cart-summary-line">
                            <span>Subtotal</span>
                            <strong>${{ number_format((float) $pedido->subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="cart-summary-line">
                            <span>Envio</span>
                            <strong>${{ number_format((float) $pedido->envio, 0, ',', '.') }}</strong>
                        </div>
                        <div class="cart-summary-line">
                            <span>Descuento</span>
                            <strong>${{ number_format((float) $pedido->descuento, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="cart-summary-total">
                        <span>Total</span>
                        <strong>${{ number_format((float) $pedido->total, 0, ',', '.') }}</strong>
                    </div>

                    <div class="cart-confirmed-actions">
                        <a href="{{ route('mis-compras.index') }}" class="btn btn-warning cart-confirm-btn">Ver mis compras</a>
                        <a href="{{ route('catalogo') }}" class="btn btn-outline-dark cart-continue-btn">Seguir comprando</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

