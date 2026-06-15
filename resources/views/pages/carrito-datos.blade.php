@extends('layouts.app')

@section('title', 'Datos y entrega | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleCarrito.css') }}">
@endpush

@section('contenido')
<section class="page-section cart-page">
    <div class="container">
        <div class="cart-hero">
            <span class="home-kicker">Checkout Hierro & Forja</span>
            <h1 class="cart-page-title">PASO 2: DATOS Y ENTREGA</h1>
            <p class="cart-hero-copy">Completá tus datos, elegí cómo querés recibir el pedido y confirmá la compra real.</p>
        </div>

        <div class="cart-steps" aria-label="Pasos del checkout">
            <div class="cart-step is-complete">
                <span class="cart-step-number">1</span>
                <div>
                    <strong>Carrito</strong>
                    <span>Productos revisados</span>
                </div>
            </div>
            <div class="cart-step is-active">
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
                        <h2>Datos del comprador</h2>
                        <p>Estos datos se guardan en el pedido confirmado para poder coordinar retiro o entrega.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('checkout') ?: 'Revisá los datos obligatorios antes de continuar.' }}
                        </div>
                    @endif

                    <div class="page-card cart-form-card">
                        <form
                            id="cartCheckoutForm"
                            class="cart-form"
                            method="POST"
                            action="{{ route('carrito.confirmar') }}"
                            data-initial-cart='@json($carrito)'
                        >
                            @csrf

                            <div class="cart-form-grid">
                                <div class="cart-form-field">
                                    <label for="checkout_nombre">Nombre</label>
                                    <input type="text" id="checkout_nombre" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
                                </div>

                                <div class="cart-form-field">
                                    <label for="checkout_apellido">Apellido</label>
                                    <input type="text" id="checkout_apellido" name="apellido" class="form-control" value="{{ old('apellido', $usuario->apellido) }}" required>
                                </div>

                                <div class="cart-form-field">
                                    <label for="checkout_email">Email</label>
                                    <input type="email" id="checkout_email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                                </div>

                                <div class="cart-form-field">
                                    <label for="checkout_dni">DNI</label>
                                    <input type="text" id="checkout_dni" name="dni" class="form-control" value="{{ old('dni', $usuario->dni) }}" required>
                                </div>

                                <div class="cart-form-field">
                                    <label for="checkout_telefono">Teléfono</label>
                                    <input type="text" id="checkout_telefono" name="telefono" class="form-control" value="{{ old('telefono', $usuario->telefono) }}" required>
                                </div>

                                <div class="cart-form-field cart-form-field-full">
                                    <label for="checkout_direccion">Dirección</label>
                                    <input type="text" id="checkout_direccion" name="direccion" class="form-control" value="{{ old('direccion', $usuario->direccion) }}" required>
                                </div>

                                <div class="cart-form-field">
                                    <label for="checkout_ciudad">Ciudad</label>
                                    <input type="text" id="checkout_ciudad" name="ciudad" class="form-control" value="{{ old('ciudad', $usuario->ciudad) }}" required>
                                </div>

                                <div class="cart-form-field">
                                    <label for="checkout_provincia">Provincia</label>
                                    <input type="text" id="checkout_provincia" name="provincia" class="form-control" value="{{ old('provincia', $usuario->provincia) }}" required>
                                </div>

                                <div class="cart-form-field">
                                    <label for="checkout_codigo_postal">Código postal</label>
                                    <input type="text" id="checkout_codigo_postal" name="codigo_postal" class="form-control" value="{{ old('codigo_postal', $usuario->codigo_postal) }}" required>
                                </div>
                            </div>

                            <div class="cart-form-block">
                                <div class="cart-form-block-head">
                                    <h3>Entrega</h3>
                                    <p>Elegí cómo querés coordinar este pedido.</p>
                                </div>

                                <div class="cart-choice-grid">
                                    <label class="cart-choice-card">
                                        <input type="radio" name="modo_entrega" value="retiro_local" {{ $modoEntregaSeleccionado === 'retiro_local' ? 'checked' : '' }} required>
                                        <div>
                                            <strong>Retiro en local</strong>
                                            <span>Coordinamos día y horario para retiro.</span>
                                        </div>
                                    </label>

                                    <label class="cart-choice-card">
                                        <input type="radio" name="modo_entrega" value="envio_domicilio" {{ $modoEntregaSeleccionado === 'envio_domicilio' ? 'checked' : '' }} required>
                                        <div>
                                            <strong>Coordinación de envío</strong>
                                            <span>Validamos zona, costo y disponibilidad.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="cart-form-block">
                                <div class="cart-form-block-head">
                                    <h3>Pago</h3>
                                    <p>Se toma la selección del paso 1, pero todavía podés ajustarla.</p>
                                </div>

                                <div class="cart-choice-grid">
                                    <label class="cart-choice-card">
                                        <input type="radio" name="metodo_pago" value="tarjeta" {{ $metodoPagoSeleccionado === 'tarjeta' ? 'checked' : '' }} required>
                                        <div>
                                            <strong>Tarjeta</strong>
                                            <span>Pago digital o coordinación comercial.</span>
                                        </div>
                                    </label>

                                    <label class="cart-choice-card">
                                        <input type="radio" name="metodo_pago" value="efectivo" {{ $metodoPagoSeleccionado === 'efectivo' ? 'checked' : '' }} required>
                                        <div>
                                            <strong>Efectivo / contra entrega</strong>
                                            <span>Pago acordado al momento de retirar o recibir.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="cart-form-actions">
                                <button type="submit" class="btn btn-warning cart-confirm-btn" id="checkoutSubmitBtn">
                                    Continuar a confirmación
                                </button>

                                <a href="{{ route('carrito') }}" class="btn btn-outline-dark cart-continue-btn">
                                    Volver al carrito
                                </a>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

            <aside class="cart-sidebar">
                <div class="page-card cart-summary-card cart-summary-card--sticky">
                    <div class="cart-summary-head">
                        <h2>Resumen del pedido</h2>
                        <p>Revisá la compra antes de confirmar.</p>
                    </div>

                    <div class="alert d-none" id="checkoutFeedback" role="alert"></div>

                    <div class="cart-summary-items" id="checkoutSummaryItems"></div>

                    <div class="cart-summary-lines">
                        <div class="cart-summary-line">
                            <span>Subtotal</span>
                            <strong id="checkoutSubtotal">${{ number_format((float) ($carrito['subtotal'] ?? 0), 0, ',', '.') }}</strong>
                        </div>
                        <div class="cart-summary-line">
                            <span>Envío</span>
                            <strong id="checkoutShipping">${{ number_format((float) ($carrito['envio'] ?? 0), 0, ',', '.') }}</strong>
                        </div>
                        <div class="cart-summary-line">
                            <span>Descuento</span>
                            <strong id="checkoutDiscount">${{ number_format((float) ($carrito['descuento'] ?? 0), 0, ',', '.') }}</strong>
                        </div>
                        <div class="cart-summary-line">
                            <span>Método de pago</span>
                            <strong id="checkoutPaymentLabel">{{ $metodoPagoSeleccionado === 'efectivo' ? 'Efectivo / contra entrega' : 'Tarjeta' }}</strong>
                        </div>
                    </div>

                    <div class="cart-summary-total">
                        <span>Total</span>
                        <strong id="checkoutTotal">${{ number_format((float) ($carrito['total'] ?? 0), 0, ',', '.') }}</strong>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/carrito-checkout.js') }}?v={{ filemtime(public_path('js/carrito-checkout.js')) }}"></script>
@endpush
