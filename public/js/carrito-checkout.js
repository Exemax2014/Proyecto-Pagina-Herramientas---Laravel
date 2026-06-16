document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('cartCheckoutForm');

    if (!form) return;

    const summaryItems = document.getElementById('checkoutSummaryItems');
    const subtotalEl = document.getElementById('checkoutSubtotal');
    const shippingEl = document.getElementById('checkoutShipping');
    const discountEl = document.getElementById('checkoutDiscount');
    const totalEl = document.getElementById('checkoutTotal');
    const feedbackEl = document.getElementById('checkoutFeedback');
    const submitBtn = document.getElementById('checkoutSubmitBtn');
    const deliveryRadios = Array.from(form.querySelectorAll('input[name="entrega_opcion"]'));
    const existingAddressOption = document.getElementById('existingAddressOption');
    const newAddressOption = document.getElementById('newAddressOption');
    const pickupOptionCard = document.getElementById('pickupOptionCard');
    const existingAddressesBlock = document.getElementById('existingAddressesBlock');
    const existingAddressRadios = Array.from(form.querySelectorAll('input[name="domicilio_id"]'));
    const newAddressFields = document.getElementById('newAddressFields');
    const addressFields = Array.from(form.querySelectorAll('#newAddressGrid input'));
    const provinceField = document.getElementById('checkout_provincia');
    const fallbackImage = '/img/producto-sin-imagen.svg';
    const localProvince = form.dataset.localProvince || '';
    const shippingSameProvince = Number(form.dataset.shippingSameProvince || 0);
    const shippingOtherProvince = Number(form.dataset.shippingOtherProvince || 0);

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

    function normalizeProvince(value) {
        return String(value ?? '')
            .trim()
            .replace(/\s+/g, ' ')
            .toLowerCase();
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

    function createSummaryOldPriceHtml(item) {
        const precioAnterior = Number(item.precio_anterior) || 0;
        const precioUnitario = Number(item.precio_unitario) || 0;

        if (precioAnterior <= precioUnitario) {
            return '';
        }

        return `<span class="cart-summary-item-old-price">${formatPrice(precioAnterior)}</span>`;
    }

    function createSummaryOfferHtml(item) {
        const descuento = Number(item.descuento_porcentaje) || 0;

        if (descuento <= 0) {
            return '';
        }

        return `<span class="cart-item-offer">${descuento}% OFF</span>`;
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

    function getSelectedDeliveryMode() {
        return deliveryRadios.find(function (radio) {
            return radio.checked;
        })?.value || 'domicilio_nuevo';
    }

    function getSelectedExistingProvince() {
        return existingAddressRadios.find(function (radio) {
            return radio.checked;
        })?.dataset.provincia || '';
    }

    function calculateShippingPreview() {
        const selectedMode = getSelectedDeliveryMode();

        if (selectedMode === 'retiro_local') {
            return 0;
        }

        const province = selectedMode === 'domicilio_existente'
            ? getSelectedExistingProvince()
            : provinceField?.value || '';

        const normalizedProvince = normalizeProvince(province);

        if (!normalizedProvince) {
            return 0;
        }

        return normalizedProvince === normalizeProvince(localProvince)
            ? shippingSameProvince
            : shippingOtherProvince;
    }

    function updateShippingPreview() {
        carrito = {
            ...(carrito || {}),
            envio: calculateShippingPreview(),
        };
        carrito.total = Number(carrito?.subtotal || 0) + Number(carrito?.envio || 0) - Number(carrito?.descuento || 0);
        renderSummary();
    }

    function updateSubmitState() {
        if (!submitBtn) return;

        submitBtn.disabled = !migrationReady || migrationFailed || getItems().length === 0 || isSubmitting;
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
            const precioUnitario = Number(item.precio_unitario) || 0;

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
                        ${createSummaryOldPriceHtml(item)}
                        <strong>${formatPrice(subtotal)}</strong>
                        <span>${formatPrice(precioUnitario)} c/u</span>
                        ${createSummaryOfferHtml(item)}
                    </div>
                </article>
            `;
        }).join('');

        subtotalEl.textContent = formatPrice(Number(carrito?.subtotal) || 0);
        shippingEl.textContent = formatPrice(Number(carrito?.envio) || 0);
        discountEl.textContent = formatPrice(Number(carrito?.descuento) || 0);
        totalEl.textContent = formatPrice(Number(carrito?.total) || 0);
        updateSubmitState();
    }

    function clearNewAddressFields() {
        addressFields.forEach(function (field) {
            field.value = '';
        });
    }

    function updateSelectedExistingAddressCards() {
        existingAddressRadios.forEach(function (radio) {
            radio.closest('.delivery-address-card')?.classList.toggle('is-selected', radio.checked);
        });
    }

    function updateOptionCards(mode) {
        [
            [existingAddressOption, mode === 'domicilio_existente'],
            [newAddressOption, mode === 'domicilio_nuevo'],
            [pickupOptionCard, mode === 'retiro_local'],
        ].forEach(function ([element, isActive]) {
            if (!element) return;

            element.classList.toggle('active', isActive);
            element.classList.toggle('is-active', isActive);
        });
    }

    function setAddressMode(mode, shouldClear = false) {
        if (mode === 'domicilio_existente' && !existingAddressOption) {
            mode = 'domicilio_nuevo';
        }

        if (!['domicilio_existente', 'domicilio_nuevo', 'retiro_local'].includes(mode)) {
            mode = existingAddressOption ? 'domicilio_existente' : 'domicilio_nuevo';
        }

        deliveryRadios.forEach(function (radio) {
            radio.checked = radio.value === mode;
        });

        if (shouldClear) {
            clearNewAddressFields();
        }

        updateOptionCards(mode);
        updateSelectedExistingAddressCards();

        existingAddressesBlock?.classList.toggle('d-none', mode !== 'domicilio_existente');
        newAddressFields?.classList.toggle('d-none', mode !== 'domicilio_nuevo');
        updateShippingPreview();
    }

    async function loadBackendCart() {
        migrationReady = false;
        migrationFailed = false;
        isSubmitting = false;
        updateSubmitState();

        try {
            setFeedback('Estamos sincronizando tu carrito antes de continuar.', 'warning');

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

            updateShippingPreview();
        } catch (error) {
            migrationReady = false;
            migrationFailed = true;
            setFeedback(error.message || 'No se pudo sincronizar el carrito. No es posible continuar hasta resolverlo.');
            updateShippingPreview();
        }
    }

    deliveryRadios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            setAddressMode(radio.value, true);
        });
    });

    existingAddressRadios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            setAddressMode('domicilio_existente', false);
        });
    });

    provinceField?.addEventListener('input', function () {
        if (getSelectedDeliveryMode() === 'domicilio_nuevo') {
            updateShippingPreview();
        }
    });

    form.addEventListener('submit', function (event) {
        if (!migrationReady || migrationFailed) {
            event.preventDefault();
            setFeedback('Estamos sincronizando el carrito, intenta nuevamente en unos segundos.', 'warning');
            updateSubmitState();
            return;
        }

        if (getItems().length === 0) {
            event.preventDefault();
            setFeedback('No hay productos cargados para continuar. Vuelve al carrito y revisa el pedido.', 'warning');
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

    setAddressMode(deliveryRadios.find(radio => radio.checked)?.value || 'domicilio_nuevo', false);
    updateSubmitState();
    updateShippingPreview();
    loadBackendCart();
});
