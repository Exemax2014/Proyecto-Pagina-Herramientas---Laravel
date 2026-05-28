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

    function formatPrice(value) {
        return '$' + Number(value || 0).toLocaleString('es-AR');
    }

    function getItems() {
        return Array.isArray(carrito?.items) ? carrito.items : [];
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
            const nombre = item.nombre || 'Producto sin nombre';
            const imagen = item.imagen || '/img/producto-sin-imagen.png';
            const marca = item.marca || 'Sin marca';
            const categoria = item.categoria || 'Sin categoría';
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

                        <div class="cart-item-controls">
                            <div class="cart-qty-box">
                                <span>Cantidad: ${cantidad}</span>
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

        try {
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

    function bindItemEvents() {
        itemsWrap.querySelectorAll('.cart-remove-btn').forEach(button => {
            button.addEventListener('click', async function () {
                const itemId = Number(this.dataset.itemId);

                this.disabled = true;
                setFeedback('');

                try {
                    const response = await window.CartUtils.removeCartItem(itemId);
                    carrito = response.carrito;
                    renderItems();
                    window.showToast(response.message || 'Item eliminado del carrito');
                } catch (error) {
                    setFeedback(error.message || 'No se pudo eliminar el item.');
                    this.disabled = false;
                }
            });
        });
    }

    confirmBtn?.addEventListener('click', async function () {
        if (getItems().length === 0) {
            setFeedback('Tu carrito está vacío.');
            return;
        }

        this.disabled = true;
        setFeedback('');

        try {
            const response = await window.CartUtils.confirmCart();
            window.showToast(response.message || 'Pedido confirmado correctamente');
            await loadCart();
        } catch (error) {
            setFeedback(error.message || 'No se pudo confirmar el pedido.');
        } finally {
            this.disabled = getItems().length === 0;
        }
    });

    loadCart();
});
