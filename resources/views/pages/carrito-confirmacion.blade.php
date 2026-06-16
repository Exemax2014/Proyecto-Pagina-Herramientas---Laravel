@extends('layouts.app')

@section('title', 'Confirmación y pago | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleCarrito.css') }}">
@endpush

@section('contenido')
<section class="page-section cart-page">
    <div class="container">
        @include('checkout.partials.timeline', ['currentStep' => 'confirmacion'])

        <div class="cart-hero">
            <h2>PASO 3: CONFIRMACIÓN Y PAGO</h2>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                {{ $errors->first('checkout') ?: 'No se pudo confirmar el pedido. Revisá la información y volvé a intentar.' }}
            </div>
        @endif

        <div class="cart-layout">
            <div class="cart-main">
                <form id="cartConfirmForm" class="cart-form" method="POST" action="{{ route('carrito.confirmar') }}">
                    @csrf

                    <section class="page-card cart-form-card">
                        <div class="section-heading cart-section-heading">
                            <h2>COMPRADOR</h2>
                            <p>Confirmamos los datos del usuario logueado antes de cerrar el pedido.</p>
                        </div>

                        <div class="cart-readonly-grid">
                            <div class="cart-readonly-field">
                                <span>Nombre completo</span>
                                <strong>{{ trim($usuario->nombre . ' ' . $usuario->apellido) }}</strong>
                            </div>
                            <div class="cart-readonly-field">
                                <span>Email</span>
                                <strong>{{ $usuario->email }}</strong>
                            </div>
                            <div class="cart-readonly-field">
                                <span>DNI</span>
                                <strong>{{ $usuario->dni ?: 'No cargado' }}</strong>
                            </div>
                            <div class="cart-readonly-field">
                                <span>Teléfono</span>
                                <strong>{{ $usuario->telefono ?: 'No cargado' }}</strong>
                            </div>
                        </div>
                    </section>

                    <section class="page-card cart-form-card">
                        <div class="section-heading cart-section-heading">
                            <h2>ENTREGA Y DOMICILIO</h2>
                            <p>Estos datos vienen del paso anterior y son los que usaremos para la compra.</p>
                        </div>

                        <div class="cart-readonly-grid">
                            <div class="cart-readonly-field">
                                <span>Modo de entrega</span>
                                <strong>{{ $modoEntregaSeleccionado === 'retiro_local' ? 'Retiro en local' : 'Envío a domicilio' }}</strong>
                            </div>
                            <div class="cart-readonly-field cart-readonly-field-full">
                                <span>{{ $modoEntregaSeleccionado === 'retiro_local' ? 'Retiro' : 'Domicilio seleccionado' }}</span>
                                <strong>
                                    @if($modoEntregaSeleccionado !== 'retiro_local' && $domicilioSeleccionado)
                                        {{ $domicilioSeleccionado['linea_principal'] ?? '-' }}
                                        @if(!empty($domicilioSeleccionado['referencia']))
                                            <small class="d-block text-muted mt-1">Referencia: {{ $domicilioSeleccionado['referencia'] }}</small>
                                        @endif
                                    @else
                                        {{ $direccionLocal }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </section>

                    <section class="page-card cart-form-card">
                        <div class="section-heading cart-section-heading">
                            <h2>PAGO</h2>
                            <p>Seleccioná la forma de pago antes de confirmar el pedido real.</p>
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
                                    <span>Pago acordado al retirar o recibir el pedido.</span>
                                </div>
                            </label>
                        </div>
                    </section>

                    <section class="page-card cart-form-card">
                        <div class="section-heading cart-section-heading">
                            <h2>OBSERVACIONES DEL PEDIDO</h2>
                            <p>Agregá una aclaración opcional para la entrega, el contacto o algún detalle puntual.</p>
                        </div>

                        <div class="cart-form-field">
                            <label for="checkout_observaciones">Observaciones</label>
                            <textarea
                                id="checkout_observaciones"
                                name="observaciones"
                                class="form-control"
                                rows="4"
                                maxlength="500"
                                placeholder="Ej: aclaraciones sobre entrega, horario de contacto o detalles del pedido"
                            >{{ old('observaciones', $observaciones ?? '') }}</textarea>
                            <small class="text-muted d-block mt-2">Opcional. Hasta 500 caracteres.</small>
                        </div>
                    </section>
                </form>
            </div>

            <aside class="cart-sidebar">
                <div class="page-card cart-summary-card cart-summary-card--sticky">
                    <div class="cart-summary-head">
                        <h2>RESUMEN DEL PEDIDO</h2>
                        <p>Verificá el pedido antes de la confirmación final.</p>
                    </div>

                    <div class="cart-summary-items">
                        @foreach($carrito['items'] as $item)
                            <article class="cart-summary-item">
                                <div class="cart-summary-item-media">
                                    <img src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}">
                                </div>
                                <div class="cart-summary-item-body">
                                    <span class="cart-item-brand">{{ $item['marca'] }}</span>
                                    <strong>{{ $item['nombre'] }}</strong>
                                    <span class="cart-summary-item-meta">{{ $item['categoria'] }}</span>
                                    <span class="cart-summary-item-meta">Cantidad: {{ $item['cantidad'] }}</span>
                                </div>
                                <div class="cart-summary-item-price">
                                    @if(!empty($item['precio_anterior']) && (float) $item['precio_anterior'] > (float) $item['precio_unitario'])
                                        <span class="cart-summary-item-old-price">
                                            ${{ number_format((float) $item['precio_anterior'], 0, ',', '.') }}
                                        </span>
                                    @endif
                                    <strong>${{ number_format((float) $item['subtotal'], 0, ',', '.') }}</strong>
                                    <span>${{ number_format((float) $item['precio_unitario'], 0, ',', '.') }} c/u</span>
                                    @if(!empty($item['descuento_porcentaje']))
                                        <span class="cart-item-offer">{{ $item['descuento_porcentaje'] }}% OFF</span>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="cart-summary-lines">
                        <div class="cart-summary-line">
                            <span>Subtotal</span>
                            <strong>${{ number_format((float) ($carrito['subtotal'] ?? 0), 0, ',', '.') }}</strong>
                        </div>
                        <div class="cart-summary-line">
                            <span>Envío estimado</span>
                            <strong>${{ number_format((float) ($carrito['envio'] ?? 0), 0, ',', '.') }}</strong>
                        </div>
                        <div class="cart-summary-line">
                            <span>Descuento</span>
                            <strong>${{ number_format((float) ($carrito['descuento'] ?? 0), 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="cart-summary-total">
                        <span>Total</span>
                        <strong>${{ number_format((float) ($carrito['total'] ?? 0), 0, ',', '.') }}</strong>
                    </div>

                    <div class="cart-summary-actions cart-summary-actions--stacked">
                        <button type="submit" form="cartConfirmForm" class="btn btn-warning cart-confirm-btn">
                            Confirmar pedido
                        </button>

                        <a href="{{ route('carrito.datos') }}" class="btn btn-outline-dark cart-continue-btn">
                            Volver a datos
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
