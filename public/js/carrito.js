document.addEventListener('DOMContentLoaded', function () {
    let carrito = null;

    const itemsWrap = document.getElementById('cartItemsWrap');
    const emptyState = document.getElementById('cartEmptyState');
    const subtotalEl = document.getElementById('cartSubtotal');
    const shippingEl = document.getElementById('cartShipping');
    const discountEl = document.getElementById('cartDiscount');
    const totalEl = document.getElementById('cartTotal');
    const confirmBtn = document.getElementById('cartConfirmBtn');
    const feedbackEl = document.getElementById('cartFeedback');
    const paymentInputs = Array.from(document.querySelectorAll('input[name="payment_method"]'));
    const paymentStorageKey = 'hf_checkout_payment_method';
    const fallbackImage = '/img/producto-sin-imagen.svg';

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

    function getItems() {
        return Array.isArray(carrito?.items) ? carrito.items : [];
    }

    function isLoggedIn() {
        return Boolean(window.usuarioLogueado || window.hfCartConfig?.loggedIn);
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

    function getSelectedPaymentMethod() {
        return paymentInputs.find(input => input.checked)?.value || sessionStorage.getItem(paymentStorageKey) || 'tarjeta';
    }

    function syncSelectedPaymentMethod() {
        const stored = sessionStorage.getItem(paymentStorageKey);
        const current = stored === 'efectivo' ? 'efectivo' : 'tarjeta';
        const targetInput = paymentInputs.find(input => input.value === current);

        if (targetInput) {
            targetInput.checked = true;
        }
    }

    function persistSelectedPaymentMethod() {
        sessionStorage.setItem(paymentStorageKey, getSelectedPaymentMethod());
    }

    function renderSummary() {
        subtotalEl.textContent = formatPrice(Number(carrito?.subtotal) || 0);
        shippingEl.textContent = formatPrice(Number(carrito?.envio) || 0);
        discountEl.textContent = formatPrice(Number(carrito?.descuento) || 0);
        totalEl.textContent = formatPrice(Number(carrito?.total) || 0);

        if (confirmBtn) {
            confirmBtn.disabled = getItems().length === 0;
        }
    }

    function renderItems() {
        if (!itemsWrap) return;

        const items = getItems();

        if (items.length === 0) {
            itemsWrap.innerHTML = '';
            emptyState?.classList.remove('d-none');
            renderSummary();
            return;
        }

        emptyState?.classList.add('d-none');

        itemsWrap.innerHTML = items.map(item => {
            const nombre = escapeHtml(item.nombre || 'Producto sin nombre');
            const imagen = safeImageUrl(item.imagen);
            const marca = escapeHtml(item.marca || 'Sin marca');
            const categoria = escapeHtml(item.categoria || 'Sin categoria');
            const descripcion = escapeHtml(item.descripcion || 'Herramienta lista para coordinar compra, retiro o entrega.');
            const cantidad = Number(item.cantidad) || 0;
            const subtotal = Number(item.subtotal) || 0;
            const precioUnitario = Number(item.precio_unitario) || 0;

            return `
                <article class="page-card cart-item-card">
                    <div class="cart-item-media">
                        <img src="${imagen}" alt="${nombre}">
                    </div>

                    <div class="cart-item-body">
                        <span class="cart-item-brand">${marca}</span>
                        <h3>${nombre}</h3>
                        <p class="cart-item-meta">Categoría: ${categoria}</p>
                        <p class="cart-item-description">${descripcion}</p>

                        <div class="cart-item-controls">
                            <div class="cart-qty-box">
                                <button type="button" class="cart-qty-btn cart-qty-minus" data-item-id="${item.id}" data-cantidad-actual="${cantidad}" aria-label="Restar cantidad">
                                    <i class="bi bi-dash"></i>
                                </button>

                                <span>${cantidad}</span>

                                <button type="button" class="cart-qty-btn cart-qty-plus" data-item-id="${item.id}" data-cantidad-actual="${cantidad}" aria-label="Sumar cantidad">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>

                            <button type="button" class="cart-remove-btn" data-item-id="${item.id}">
                                Eliminar
                            </button>
                        </div>
                    </div>

                    <div class="cart-item-price">
                        <strong>${formatPrice(subtotal)}</strong>
                        <span>${formatPrice(precioUnitario)} por unidad</span>
                    </div>
                </article>
            `;
        }).join('');

        bindItemEvents();
        renderSummary();
    }

    async function loadCart() {
        setFeedback('');

        if (!isLoggedIn()) {
            carrito = getLocalCartView();
            renderItems();
            return;
        }

        try {
            if (window.CartUtils?.migrateLocalCartIfNeeded) {
                const migration = await window.CartUtils.migrateLocalCartIfNeeded();

                if (migration?.warnings?.length) {
                    setFeedback(migration.warnings[0], 'warning');
                }
            }

            carrito = await window.CartUtils.fetchBackendCart();
            renderItems();
            window.CartUtils.updateCartCountFromCarrito(carrito);
        } catch (error) {
            carrito = {
                subtotal: 0,
                envio: 0,
                descuento: 0,
                total: 0,
                items: [],
            };

            renderItems();
            setFeedback(error.message || 'No se pudo cargar el carrito.');
        }
    }

    function getLocalCartView() {
        const items = (window.CartUtils?.getCart?.() || []).map(item => {
            const cantidad = Number(item.cantidad) || 0;
            const precioUnitario = Number(item.precio_unitario ?? item.precio) || 0;

            return {
                id: Number(item.producto_id ?? item.id) || 0,
                producto_id: Number(item.producto_id ?? item.id) || 0,
                nombre: item.nombre || 'Producto sin nombre',
                marca: item.marca || 'Sin marca',
                categoria: item.categoria || 'Sin categoria',
                descripcion: item.descripcion || 'Herramienta lista para coordinar compra, retiro o entrega.',
                precio_unitario: precioUnitario,
                cantidad,
                subtotal: precioUnitario * cantidad,
                imagen: item.imagen || '/img/producto-sin-imagen.svg',
            };
        });

        const subtotal = items.reduce((acc, item) => acc + (Number(item.subtotal) || 0), 0);

        return {
            items,
            subtotal,
            envio: 0,
            descuento: 0,
            total: subtotal,
            cantidad_total: items.reduce((acc, item) => acc + (Number(item.cantidad) || 0), 0),
        };
    }

    function bindItemEvents() {
        itemsWrap.querySelectorAll('.cart-qty-minus').forEach(button => {
            button.addEventListener('click', async function () {
                const itemId = Number(this.dataset.itemId);
                const currentQty = Number(this.dataset.cantidadActual) || 1;
                const nextQty = currentQty - 1;

                if (nextQty < 1) {
                    setFeedback('La cantidad mínima es 1. Usá Eliminar para quitar el item.');
                    return;
                }

                await updateItemQuantity(itemId, nextQty, this, currentQty);
            });
        });

        itemsWrap.querySelectorAll('.cart-qty-plus').forEach(button => {
            button.addEventListener('click', async function () {
                const itemId = Number(this.dataset.itemId);
                const currentQty = Number(this.dataset.cantidadActual) || 1;
                const nextQty = currentQty + 1;

                await updateItemQuantity(itemId, nextQty, this, currentQty);
            });
        });

        itemsWrap.querySelectorAll('.cart-remove-btn').forEach(button => {
            button.addEventListener('click', async function () {
                const itemId = Number(this.dataset.itemId);

                this.disabled = true;
                setFeedback('');

                try {
                    if (isLoggedIn()) {
                        const response = await window.CartUtils.removeCartItem(itemId);
                        carrito = response.carrito;
                        window.showToast(response.message || 'Item eliminado del carrito');
                    } else {
                        window.CartUtils.removeFromCart(itemId);
                        carrito = getLocalCartView();
                        window.showToast('Item eliminado del carrito');
                    }

                    renderItems();
                } catch (error) {
                    setFeedback(error.message || 'No se pudo eliminar el item.');
                    this.disabled = false;
                }
            });
        });
    }

    async function updateItemQuantity(itemId, quantity, triggerButton, currentQty) {
        if (triggerButton) {
            triggerButton.disabled = true;
        }

        setFeedback('');

        try {
            if (isLoggedIn()) {
                const response = await window.CartUtils.updateBackendCartItem(itemId, quantity);
                carrito = response.carrito;

                if (response?.warnings?.length) {
                    setFeedback(response.warnings[0], 'warning');
                }
            } else {
                window.CartUtils.updateCartQty(itemId, quantity);
                carrito = getLocalCartView();
            }

            renderItems();
        } catch (error) {
            setFeedback(error.message || 'No se pudo actualizar la cantidad.');

            if (!isLoggedIn() && typeof currentQty === 'number') {
                window.CartUtils.updateCartQty(itemId, currentQty);
                carrito = getLocalCartView();
                renderItems();
            }
        } finally {
            if (triggerButton && document.body.contains(triggerButton)) {
                triggerButton.disabled = false;
            }
        }
    }

    confirmBtn?.addEventListener('click', function () {
        if (getItems().length === 0) {
            setFeedback('Tu carrito está vacío.');
            return;
        }

        persistSelectedPaymentMethod();

        const checkoutUrl = this.dataset.checkoutUrl || '/carrito/datos';
        const nextUrl = `${checkoutUrl}?metodo_pago=${encodeURIComponent(getSelectedPaymentMethod())}`;

        if (!isLoggedIn()) {
            window.CartUtils.redirectToLoginWithRedirect(nextUrl);
            return;
        }

        window.location.href = nextUrl;
    });

    paymentInputs.forEach(input => {
        input.addEventListener('change', persistSelectedPaymentMethod);
    });

    syncSelectedPaymentMethod();
    loadCart();
});
