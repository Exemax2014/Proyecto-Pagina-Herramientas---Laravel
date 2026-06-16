<div class="cart-process-card">
    <div class="checkout-timeline" aria-label="Pasos del checkout">
        <div class="timeline-title">
            <span class="timeline-icon">
                <i class="bi bi-cart"></i>
            </span>
            <span class="timeline-title-text">PROCESO DE COMPRA</span>
        </div>

        <div class="timeline-line" aria-hidden="true"></div>

        <div class="timeline-step {{ $currentStep === 'carrito' ? 'active' : '' }}">
            <span class="step-number">1</span>
            <span class="step-label">Carrito</span>
        </div>

        <div class="timeline-line" aria-hidden="true"></div>

        <div class="timeline-step {{ $currentStep === 'datos' ? 'active' : '' }}">
            <span class="step-number">2</span>
            <span class="step-label">Datos</span>
        </div>

        <div class="timeline-line" aria-hidden="true"></div>

        <div class="timeline-step {{ $currentStep === 'confirmacion' ? 'active' : '' }}">
            <span class="step-number">3</span>
            <span class="step-label">Confirmación y pago</span>
        </div>
    </div>
</div>
