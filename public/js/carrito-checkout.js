document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('cartCheckoutForm');

    if (!form) return;

    const summaryItems = document.getElementById('checkoutSummaryItems');
    const subtotalEl = document.getElementById('checkoutSubtotal');
    const shippingEl = document.getElementById('checkoutShipping');
    const discountEl = document.getElementById('checkoutDiscount');
    const totalEl = document.getElementById('checkoutTotal');
    const paymentLabelEl = document.getElementById('checkoutPaymentLabel');
    const feedbackEl = document.getElementById('checkoutFeedback');
    const submitBtn = document.getElementById('checkoutSubmitBtn');
    const paymentInputs = Array.from(document.querySelectorAll('input[name="metodo_pago"]'));
    const fallbackImage = '/img/producto-sin-imagen.svg';

    let carrito = safeParseInitialCart(form.dataset.initialCart);
    let migrationReady = false;
    let migrationFailed = false;
    let isSubmitting = false;

    function safeParseInitialCart(rawValue) {
        if (!rawValue) {
            return { items: [], subtotal: 0, envio: 0, descuento: 0, total: 0 };
        }

        try {
            return JSON.parse(rawValue);
        } catch (error) {
            return { items: [], subtotal: 0, envio: 0, descuento: 0, total: 0 };
        }
    }

    function formatPrice(value) {
        return '$' + Number(value || 0).toLocaleString('es-AR');
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function safeImageUrl(value) {
        const rawValue = String(value ?? '').trim();

        if (!rawValue) {
            return fallbackImage;
        }

        const normalizedValue = rawValue.toLowerCase();

        if (/^(javascript|data|vbscript|file):/.test(normalizedValue)) {
            return fallbackImage;
        }

        if (/^https?:\/\//i.test(rawValue)) {
            return rawValue;
        }

        if (rawValue.startsWith('/') || rawValue.startsWith('./') || rawValue.startsWith('../')) {
            return rawValue;
        }

        return fallbackImage;
    }

    function setFeedback(message, type = 'danger') {
        if (!feedbackEl) return;

        if (!message) {
            feedbackEl.className = 'alert d-none';
            feedbackEl.textContent = '';
            return;
        }

        feedbackEl.className = `alert alert-${type}`;
        feedbackEl.textContent = message;
    }

    function getItems() {
        return Array.isArray(carrito?.items) ? carrito.items : [];
    }

    function getSelectedPaymentLabel() {
        const selected = paymentInputs.find(input => input.checked)?.value || 'tarjeta';
        return selected === 'efectivo' ? 'Efectivo / contra entrega' : 'Tarjeta';
    }

    function updateSubmitState() {
        if (!submitBtn) return;

        const hasItems = getItems().length > 0;
        submitBtn.disabled = !migrationReady || migrationFailed || !hasItems || isSubmitting;
    }

    function renderSummary() {
        const items = getItems();

        summaryItems.innerHTML = items.map(item => {
            const nombre = escapeHtml(item.nombre || 'Producto sin nombre');
            const marca = escapeHtml(item.marca || 'Sin marca');
            const categoria = escapeHtml(item.categoria || 'Sin categoria');
            const imagen = safeImageUrl(item.imagen);
            const cantidad = Number(item.cantidad) || 0;
            const subtotal = Number(item.subtotal) || 0;

            return `
                <article class="cart-summary-item">
                    <div class="cart-summary-item-media">
                        <img src="${imagen}" alt="${nombre}">
                    </div>
                    <div class="cart-summary-item-body">
                        <span class="cart-item-brand">${marca}</span>
                        <strong>${nombre}</strong>
                        <span class="cart-summary-item-meta">${categoria}</span>
                        <span class="cart-summary-item-meta">Cantidad: ${cantidad}</span>
                    </div>
                    <div class="cart-summary-item-price">
                        <strong>${formatPrice(subtotal)}</strong>
                    </div>
                </article>
            `;
        }).join('');

        subtotalEl.textContent = formatPrice(Number(carrito?.subtotal) || 0);
        shippingEl.textContent = formatPrice(Number(carrito?.envio) || 0);
        discountEl.textContent = formatPrice(Number(carrito?.descuento) || 0);
        totalEl.textContent = formatPrice(Number(carrito?.total) || 0);
        paymentLabelEl.textContent = getSelectedPaymentLabel();
        updateSubmitState();

        if (items.length === 0 && migrationReady && !migrationFailed) {
            setFeedback('No hay productos cargados para confirmar. Volvé al carrito y revisá el pedido.', 'warning');
        }
    }

    async function loadBackendCart() {
        migrationReady = false;
        migrationFailed = false;
        isSubmitting = false;
        updateSubmitState();

        try {
            setFeedback('Estamos sincronizando tu carrito antes de confirmar el pedido.', 'warning');

            if (window.CartUtils?.migrateLocalCartIfNeeded) {
                const migration = await window.CartUtils.migrateLocalCartIfNeeded();

                if (migration?.warnings?.length) {
                    setFeedback(migration.warnings[0], 'warning');
                }
            }

            carrito = await window.CartUtils.fetchBackendCart();
            migrationReady = true;
            migrationFailed = false;

            if (getItems().length > 0) {
                setFeedback('');
            }

            renderSummary();
        } catch (error) {
            migrationReady = false;
            migrationFailed = true;
            setFeedback(error.message || 'No se pudo sincronizar el carrito. No es posible confirmar el pedido hasta resolverlo.');
            renderSummary();
        }
    }

    paymentInputs.forEach(input => {
        input.addEventListener('change', renderSummary);
    });

    form.addEventListener('submit', function (event) {
        if (!migrationReady || migrationFailed) {
            event.preventDefault();
            setFeedback('Todavía no terminó la sincronización del carrito. Esperá un momento o recargá la página.', 'warning');
            updateSubmitState();
            return;
        }

        if (getItems().length === 0) {
            event.preventDefault();
            setFeedback('No hay productos cargados para confirmar. Volvé al carrito y revisá el pedido.', 'warning');
            updateSubmitState();
            return;
        }

        if (isSubmitting) {
            event.preventDefault();
            return;
        }

        isSubmitting = true;
        updateSubmitState();
    });

    updateSubmitState();
    renderSummary();
    loadBackendCart();
});
