@extends('layouts.app')

@section('title', 'Carrito | Hierro & Forja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleCarrito.css') }}">
@endpush

@section('contenido')
<section class="page-section cart-page">
    <div class="container">

        <!-- ================= HERO DEL CARRITO ================= -->
        <div class="cart-hero">
            <div class="cart-hero-inline">
                <span class="home-kicker">Proceso de compra</span>
                <p class="cart-hero-copy">Revisá tus productos, completá tus datos y dejá listo el pedido para confirmar.</p>
            </div>
        </div>

        <!-- ================= CONTENIDO PRINCIPAL ================= -->
        <div class="cart-layout">

            <!-- ===== COLUMNA IZQUIERDA ===== -->
            <div class="cart-main">

                <!-- ===== PRODUCTOS DEL CARRITO ===== -->
                <section class="cart-section">
                    <div class="section-heading cart-section-heading">
                        <span class="home-kicker">Resumen</span>
                        <h2>Productos del carrito</h2>
                        <p>Modificá cantidades, eliminá artículos o revisá los importes antes de continuar.</p>
                    </div>

                    <div class="cart-items-wrap" id="cartItemsWrap"></div>

                    <div class="page-card cart-empty-state d-none" id="cartEmptyState">
                        <i class="bi bi-cart-x"></i>
                        <h3>Tu carrito está vacío</h3>
                        <p>Cuando agregues productos, los vas a ver listados acá.</p>
                        <a href="{{ route('catalogo') }}" class="btn btn-warning">Ir al catálogo</a>
                    </div>
                </section>

                <!-- ===== DATOS DEL CLIENTE ===== -->
                <section class="cart-section">
                    <div class="section-heading cart-section-heading">
                        <span class="home-kicker">Contacto</span>
                        <h2>Datos del cliente y entrega</h2>
                        <p>Este bloque ya queda preparado para conectarse después con el backend y guardar el pedido real.</p>
                    </div>

                    <div class="page-card cart-form-card">
                        <form id="cartCheckoutForm" class="cart-form">
                            <div class="cart-form-grid">
                                <div class="cart-form-field">
                                    <label for="cliente_nombre">Nombre completo</label>
                                    <input type="text" id="cliente_nombre" class="form-control" placeholder="Ingresá tu nombre completo">
                                </div>

                                <div class="cart-form-field">
                                    <label for="cliente_correo">Correo electrónico</label>
                                    <input type="email" id="cliente_correo" class="form-control" placeholder="Ingresá tu correo">
                                </div>

                                <div class="cart-form-field">
                                    <label for="cliente_telefono">Teléfono</label>
                                    <input type="text" id="cliente_telefono" class="form-control" placeholder="Ingresá tu teléfono">
                                </div>

                                <div class="cart-form-field">
                                    <label for="cliente_ciudad">Ciudad</label>
                                    <input type="text" id="cliente_ciudad" class="form-control" placeholder="Ingresá tu ciudad">
                                </div>

                                <div class="cart-form-field cart-form-field-full">
                                    <label for="cliente_direccion">Dirección</label>
                                    <input type="text" id="cliente_direccion" class="form-control" placeholder="Ingresá tu dirección">
                                </div>

                                <div class="cart-form-field">
                                    <label for="cliente_provincia">Provincia</label>
                                    <input type="text" id="cliente_provincia" class="form-control" placeholder="Ingresá tu provincia">
                                </div>

                                <div class="cart-form-field">
                                    <label for="cliente_cp">Código postal</label>
                                    <input type="text" id="cliente_cp" class="form-control" placeholder="Ingresá tu código postal">
                                </div>

                                <div class="cart-form-field cart-form-field-full">
                                    <label for="cliente_observaciones">Observaciones</label>
                                    <textarea id="cliente_observaciones" class="form-control" rows="4" placeholder="Escribí una observación si lo necesitás"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

            <!-- ===== COLUMNA DERECHA ===== -->
            <aside class="cart-sidebar">

                <!-- ===== RESUMEN ===== -->
                <div class="page-card cart-summary-card">
                    <div class="cart-summary-head">
                        <h2>Resumen del pedido</h2>
                        <p>Totales calculados automáticamente según el carrito actual.</p>
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
                            <input type="radio" name="payment_method" value="transferencia" checked>
                            <div>
                                <strong>Transferencia bancaria</strong>
                                <span>Validación manual del pago</span>
                            </div>
                        </label>

                        <label class="cart-payment-option">
                            <input type="radio" name="payment_method" value="tarjeta">
                            <div>
                                <strong>Tarjeta</strong>
                                <span>Preparado para integrar pasarela</span>
                            </div>
                        </label>

                        <label class="cart-payment-option">
                            <input type="radio" name="payment_method" value="efectivo">
                            <div>
                                <strong>Efectivo / contra entrega</strong>
                                <span>Según condiciones comerciales</span>
                            </div>
                        </label>
                    </div>

                    <div class="cart-summary-actions">
                        <button type="button" class="btn btn-warning cart-confirm-btn" id="cartConfirmBtn">
                            Confirmar pedido
                        </button>

                        <a href="{{ route('catalogo') }}" class="btn btn-outline-dark cart-continue-btn">
                            Seguir comprando
                        </a>
                    </div>

                    <p class="cart-summary-note">
                        Este flujo queda listo para que después solo conectes el guardado real del pedido y la integración con usuarios o base de datos.
                    </p>
                </div>

                <!-- ===== BENEFICIOS ===== -->
                <div class="cart-benefits-grid">
                    <div class="page-card cart-benefit-card">
                        <i class="bi bi-truck"></i>
                        <div>
                            <strong>Envíos</strong>
                            <span>Coordinación según zona</span>
                        </div>
                    </div>

                    <div class="page-card cart-benefit-card">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <strong>Respaldo</strong>
                            <span>Compra segura y clara</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/carrito.js') }}"></script>
@endpush