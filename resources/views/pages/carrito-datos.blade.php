@extends('layouts.app')

@section('title', 'Datos y entrega | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleCarrito.css') }}">
@endpush

@section('contenido')
<section class="page-section cart-page">
    <div class="container">
        @include('checkout.partials.timeline', ['currentStep' => 'datos'])

        <div class="cart-hero">
            <h2>PASO 2: DATOS Y ENTREGA</h2>
            <p class="cart-hero-copy">Corrobora tus datos personales y elige como quieres recibir tu pedido.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                {{ $errors->first('checkout') ?: 'Revisa los datos obligatorios antes de continuar.' }}
            </div>
        @endif

        <div class="cart-layout">
            <div class="cart-main">
                <form
                    id="cartCheckoutForm"
                    class="cart-form"
                    method="POST"
                    action="{{ route('carrito.datos.store') }}"
                    data-initial-cart='@json($carrito)'
                    data-local-province="{{ $provinciaLocal }}"
                    data-shipping-same-province="{{ $shippingSameProvince }}"
                    data-shipping-other-province="{{ $shippingOtherProvince }}"
                >
                    @csrf

                    <section class="page-card cart-form-card buyer-data-card">
                        <div class="section-heading cart-section-heading">
                            <h2>DATOS DEL COMPRADOR</h2>
                        </div>

                        <div class="buyer-data-compact">
                            <div class="buyer-row buyer-row-two">
                                <div class="buyer-info-item">
                                    <span class="buyer-label">Nombre:</span>
                                    <span class="buyer-value">{{ $usuario->nombre }}</span>
                                </div>
                                <div class="buyer-info-item">
                                    <span class="buyer-label">Apellido:</span>
                                    <span class="buyer-value">{{ $usuario->apellido }}</span>
                                </div>
                            </div>

                            <div class="buyer-row buyer-row-two">
                                <div class="buyer-info-item">
                                    <span class="buyer-label">Telefono:</span>
                                    <span class="buyer-value">{{ $usuario->telefono ?: 'No informado' }}</span>
                                </div>
                                <div class="buyer-info-item">
                                    <span class="buyer-label">DNI:</span>
                                    <span class="buyer-value">{{ $usuario->dni ?: 'No informado' }}</span>
                                </div>
                            </div>

                            <div class="buyer-row buyer-row-full">
                                <div class="buyer-info-item">
                                    <span class="buyer-label">Correo:</span>
                                    <span class="buyer-value">{{ $usuario->email }}</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="page-card cart-form-card delivery-card">
                        <div class="section-heading cart-section-heading">
                            <h2>ENTREGA</h2>
                            <p>Usa tu domicilio actual, carga uno nuevo o elige retiro en local.</p>
                        </div>

                        @if($domicilios->isEmpty())
                            <div class="alert alert-warning mb-3" role="alert">
                                Agrega un nuevo domicilio para continuar con envio o selecciona retiro en local.
                            </div>
                        @endif

                        <div class="delivery-options">
                            @if($domicilios->isNotEmpty())
                                <div class="delivery-option-group">
                                    <label
                                        class="delivery-option {{ $domicilioOpcion === 'domicilio_existente' ? 'active' : '' }}"
                                        id="existingAddressOption"
                                        for="entrega_domicilio_existente"
                                    >
                                        <input
                                            type="radio"
                                            class="delivery-option-input"
                                            id="entrega_domicilio_existente"
                                            name="entrega_opcion"
                                            value="domicilio_existente"
                                            {{ $domicilioOpcion === 'domicilio_existente' ? 'checked' : '' }}
                                        >
                                        <span class="delivery-option-header">
                                            <span class="delivery-option-radio" aria-hidden="true"></span>
                                            <span>
                                                <strong class="delivery-option-title">
                                                    {{ $domicilios->count() > 1 ? 'Selecciona un domicilio registrado' : 'Domicilio registrado' }}
                                                </strong>
                                                <span class="delivery-option-copy">
                                                    {{ $domicilios->count() > 1 ? 'Elige uno de tus domicilios guardados para esta compra.' : 'Usa tu domicilio guardado para esta compra.' }}
                                                </span>
                                            </span>
                                        </span>
                                    </label>

                                    <div class="delivery-option-body {{ $domicilioOpcion === 'domicilio_existente' ? '' : 'd-none' }}" id="existingAddressesBlock">
                                        <div class="delivery-address-list">
                                            @foreach($domicilios as $domicilio)
                                                <label class="delivery-address-card" for="domicilio_existente_{{ $domicilio->id }}">
                                                    <input
                                                        type="radio"
                                                        class="delivery-address-input"
                                                        id="domicilio_existente_{{ $domicilio->id }}"
                                                        name="domicilio_id"
                                                        value="{{ $domicilio->id }}"
                                                        data-provincia="{{ $domicilio->provincia }}"
                                                        {{ (int) $domicilioSeleccionadoId === (int) $domicilio->id ? 'checked' : '' }}
                                                    >
                                                    <span class="delivery-address-card-head">
                                                        <span class="delivery-option-radio" aria-hidden="true"></span>
                                                        <span>
                                                            <strong class="delivery-option-title">
                                                                {{ $domicilio->es_principal ? 'Domicilio principal' : 'Domicilio registrado' }}
                                                            </strong>
                                                            <span class="delivery-option-copy">
                                                                {{ $domicilio->linea_principal ?? trim($domicilio->calle . ' ' . $domicilio->numero) }}
                                                            </span>
                                                        </span>
                                                    </span>

                                                    <span class="address-data-compact">
                                                        <span class="address-row-two">
                                                            <span class="address-info-item">
                                                                <span class="address-label">Calle:</span>
                                                                <span class="address-value">{{ $domicilio->calle }}</span>
                                                            </span>
                                                            <span class="address-info-item">
                                                                <span class="address-label">Numero:</span>
                                                                <span class="address-value">{{ $domicilio->numero }}</span>
                                                            </span>
                                                        </span>

                                                        <span class="address-row-two">
                                                            <span class="address-info-item">
                                                                <span class="address-label">Piso / Departamento:</span>
                                                                <span class="address-value">{{ $domicilio->piso_departamento ?: 'No informado' }}</span>
                                                            </span>
                                                            <span class="address-info-item">
                                                                <span class="address-label">Ciudad:</span>
                                                                <span class="address-value">{{ $domicilio->ciudad }}</span>
                                                            </span>
                                                        </span>

                                                        <span class="address-row-two">
                                                            <span class="address-info-item">
                                                                <span class="address-label">Provincia:</span>
                                                                <span class="address-value">{{ $domicilio->provincia }}</span>
                                                            </span>
                                                            <span class="address-info-item">
                                                                <span class="address-label">Codigo postal:</span>
                                                                <span class="address-value">{{ $domicilio->codigo_postal ?: 'No informado' }}</span>
                                                            </span>
                                                        </span>

                                                        <span class="address-row-two address-row-two--single">
                                                            <span class="address-info-item">
                                                                <span class="address-label">Referencia:</span>
                                                                <span class="address-value">{{ $domicilio->referencia ?: 'No informado' }}</span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="delivery-option-group">
                                <label
                                    class="delivery-option {{ $domicilioOpcion === 'domicilio_nuevo' ? 'active' : '' }}"
                                    id="newAddressOption"
                                    for="entrega_domicilio_nuevo"
                                >
                                    <input
                                        type="radio"
                                        class="delivery-option-input"
                                        id="entrega_domicilio_nuevo"
                                        name="entrega_opcion"
                                        value="domicilio_nuevo"
                                        {{ $domicilioOpcion === 'domicilio_nuevo' ? 'checked' : '' }}
                                    >
                                    <span class="delivery-option-header">
                                        <span class="delivery-option-radio" aria-hidden="true"></span>
                                        <span>
                                            <strong class="delivery-option-title">Nuevo domicilio</strong>
                                            <span class="delivery-option-copy">Cargar otro domicilio para esta compra.</span>
                                        </span>
                                    </span>
                                </label>

                                <div class="delivery-option-body {{ $domicilioOpcion === 'domicilio_nuevo' ? '' : 'd-none' }}" id="newAddressFields">
                                    <div class="cart-form-grid address-grid" id="newAddressGrid">
                                        <div class="cart-form-field address-field">
                                            <label for="checkout_calle">Calle</label>
                                            <input type="text" id="checkout_calle" name="calle" class="form-control" value="{{ $nuevoDomicilio['calle'] }}">
                                        </div>
                                        <div class="cart-form-field address-field">
                                            <label for="checkout_numero">Numero</label>
                                            <input type="text" id="checkout_numero" name="numero" class="form-control" value="{{ $nuevoDomicilio['numero'] }}">
                                        </div>
                                        <div class="cart-form-field address-field">
                                            <label for="checkout_piso_departamento">Piso / Departamento</label>
                                            <input type="text" id="checkout_piso_departamento" name="piso_departamento" class="form-control" value="{{ $nuevoDomicilio['piso_departamento'] }}">
                                        </div>
                                        <div class="cart-form-field address-field">
                                            <label for="checkout_ciudad">Ciudad</label>
                                            <input type="text" id="checkout_ciudad" name="ciudad" class="form-control" value="{{ $nuevoDomicilio['ciudad'] }}">
                                        </div>
                                        <div class="cart-form-field address-field">
                                            <label for="checkout_provincia">Provincia</label>
                                            <input type="text" id="checkout_provincia" name="provincia" class="form-control" value="{{ $nuevoDomicilio['provincia'] }}">
                                        </div>
                                        <div class="cart-form-field address-field">
                                            <label for="checkout_codigo_postal">Codigo postal</label>
                                            <input type="text" id="checkout_codigo_postal" name="codigo_postal" class="form-control" value="{{ $nuevoDomicilio['codigo_postal'] }}">
                                        </div>
                                        <div class="cart-form-field cart-form-field-full address-field">
                                            <label for="checkout_referencia">Referencia</label>
                                            <input type="text" id="checkout_referencia" name="referencia" class="form-control" value="{{ $nuevoDomicilio['referencia'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="delivery-option-group">
                                <label
                                    class="delivery-option {{ $domicilioOpcion === 'retiro_local' ? 'active' : '' }}"
                                    id="pickupOptionCard"
                                    for="entrega_retiro_local"
                                >
                                    <input
                                        type="radio"
                                        class="delivery-option-input"
                                        id="entrega_retiro_local"
                                        name="entrega_opcion"
                                        value="retiro_local"
                                        {{ $domicilioOpcion === 'retiro_local' ? 'checked' : '' }}
                                    >
                                    <span class="delivery-option-header">
                                        <span class="delivery-option-radio" aria-hidden="true"></span>
                                        <span>
                                            <strong class="delivery-option-title">Retiro en local</strong>
                                            <span class="delivery-option-copy">Retira tu pedido en nuestro local. {{ $direccionLocal }}</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            <aside class="cart-sidebar">
                <div class="page-card cart-summary-card cart-summary-card--sticky">
                    <div class="cart-summary-head">
                        <h2>RESUMEN DEL PEDIDO</h2>
                        <p>Revisa tus productos antes de pasar al paso final.</p>
                    </div>

                    <div class="alert d-none" id="checkoutFeedback" role="alert"></div>

                    <div class="cart-summary-items" id="checkoutSummaryItems"></div>

                    <div class="cart-summary-lines">
                        <div class="cart-summary-line">
                            <span>Subtotal</span>
                            <strong id="checkoutSubtotal">${{ number_format((float) ($carrito['subtotal'] ?? 0), 0, ',', '.') }}</strong>
                        </div>
                        <div class="cart-summary-line">
                            <span>Envio estimado</span>
                            <strong id="checkoutShipping">${{ number_format((float) ($carrito['envio'] ?? 0), 0, ',', '.') }}</strong>
                        </div>
                        <div class="cart-summary-line">
                            <span>Descuento</span>
                            <strong id="checkoutDiscount">${{ number_format((float) ($carrito['descuento'] ?? 0), 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="cart-summary-total">
                        <span>Total</span>
                        <strong id="checkoutTotal">${{ number_format((float) ($carrito['total'] ?? 0), 0, ',', '.') }}</strong>
                    </div>

                    <div class="cart-summary-actions cart-summary-actions--stacked">
                        <button type="submit" form="cartCheckoutForm" class="btn btn-warning cart-confirm-btn" id="checkoutSubmitBtn">
                            Continuar a confirmacion
                        </button>

                        <a href="{{ route('carrito') }}" class="btn btn-outline-dark cart-continue-btn">
                            Volver al carrito
                        </a>
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
