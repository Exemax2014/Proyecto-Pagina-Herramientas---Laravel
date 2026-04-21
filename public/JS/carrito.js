document.addEventListener('DOMContentLoaded', function () {
    /* =========================================
       DATOS TEMPORALES: carrito
       ========================================= */
    let carrito = window.CartUtils.getCart();

    /* =========================================
       REFERENCIAS DEL DOM:
       elementos principales de la vista
       ========================================= */
    const itemsWrap = document.getElementById('cartItemsWrap');
    const emptyState = document.getElementById('cartEmptyState');
    const subtotalEl = document.getElementById('cartSubtotal');
    const shippingEl = document.getElementById('cartShipping');
    const discountEl = document.getElementById('cartDiscount');
    const totalEl = document.getElementById('cartTotal');
    const confirmBtn = document.getElementById('cartConfirmBtn');

    /* =========================================
       FORMATEO DE PRECIO:
       convierte valores numéricos a formato ARS
       ========================================= */
    function formatPrice(value) {
        return '$' + Number(value).toLocaleString('es-AR');
    }

    /* =========================================
       TEXTO DE CATEGORIA:
       mejora la presentación del slug
       ========================================= */
    function humanizeCategory(value) {
        const map = {
            construccion: 'Construcción',
            herreria: 'Herrería',
            carpinteria: 'Carpintería',
            durlok: 'Durlok',
            pintureria: 'Pinturería',
            ferreteria: 'Ferretería'
        };

        return map[value] || value;
    }

    /* =========================================
       CALCULOS:
       obtiene subtotal, envío, descuento y total
       ========================================= */
    function getSubtotal() {
        return carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
    }

    function getShipping(subtotal) {
        if (carrito.length === 0) return 0;
        return subtotal >= 300000 ? 0 : 18000;
    }

    function getDiscount() {
        return 0;
    }

    function getTotal() {
        const subtotal = getSubtotal();
        const shipping = getShipping(subtotal);
        const discount = getDiscount();

        return {
            subtotal,
            shipping,
            discount,
            total: subtotal + shipping - discount
        };
    }

    /* =========================================
       RESUMEN:
       actualiza importes laterales
       ========================================= */
    function renderSummary() {
        const totals = getTotal();

        subtotalEl.textContent = formatPrice(totals.subtotal);
        shippingEl.textContent = formatPrice(totals.shipping);
        discountEl.textContent = formatPrice(totals.discount);
        totalEl.textContent = formatPrice(totals.total);
    }

    /* =========================================
       ITEMS DEL CARRITO:
       pinta cada producto con controles de cantidad y eliminación
       ========================================= */
    function renderItems() {
        if (!itemsWrap) return;

        if (carrito.length === 0) {
            itemsWrap.innerHTML = '';
            emptyState.classList.remove('d-none');
            renderSummary();
            return;
        }

        emptyState.classList.add('d-none');

        itemsWrap.innerHTML = carrito.map(item => `
            <article class="page-card cart-item-card">
                <div class="cart-item-media">
                    <img src="${item.imagen}" alt="${item.nombre}">
                </div>

                <div class="cart-item-body">
                    <span class="cart-item-brand">${item.marca}</span>
                    <h3>${item.nombre}</h3>
                    <p class="cart-item-meta">Categoría: ${humanizeCategory(item.categoria)}</p>

                    <div class="cart-item-controls">
                        <div class="cart-qty-box">
                            <button type="button" class="cart-qty-btn cart-qty-minus" data-id="${item.id}" aria-label="Restar cantidad">
                                <i class="bi bi-dash"></i>
                            </button>

                            <span>${item.cantidad}</span>

                            <button type="button" class="cart-qty-btn cart-qty-plus" data-id="${item.id}" aria-label="Sumar cantidad">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>

                        <button type="button" class="cart-remove-btn" data-id="${item.id}">
                            Eliminar
                        </button>
                    </div>
                </div>

                <div class="cart-item-price">
                    <strong>${formatPrice(item.precio * item.cantidad)}</strong>
                    <span>${formatPrice(item.precio)} por unidad</span>
                </div>
            </article>
        `).join('');

        bindItemEvents();
        renderSummary();
    }

   /* =========================================
        EVENTOS DE ITEMS:
        conecta cantidad +/- y eliminar
        ========================================= */
    function bindItemEvents() {
        itemsWrap.querySelectorAll('.cart-qty-minus').forEach(button => {
            button.addEventListener('click', function () {
                const id = Number(this.dataset.id);
                const item = carrito.find(product => Number(product.id) === id);

                if (!item) return;

                item.cantidad = Math.max(1, item.cantidad - 1);

                window.CartUtils.saveCart(carrito);
                window.CartUtils.updateCartCount();
                renderItems();
            });
        });

        itemsWrap.querySelectorAll('.cart-qty-plus').forEach(button => {
            button.addEventListener('click', function () {
                const id = Number(this.dataset.id);
                const item = carrito.find(product => Number(product.id) === id);

                if (!item) return;

                item.cantidad += 1;

                window.CartUtils.saveCart(carrito);
                window.CartUtils.updateCartCount();
                renderItems();
            });
        });

        itemsWrap.querySelectorAll('.cart-remove-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = Number(this.dataset.id);

                carrito = carrito.filter(product => Number(product.id) !== id);

                window.CartUtils.saveCart(carrito);
                window.CartUtils.updateCartCount();
                renderItems();
            });
        });
    }
    /* =========================================
        CONFIRMAR PEDIDO:
        limpia carrito, actualiza vista y muestra mensaje
        ========================================= */
    confirmBtn?.addEventListener('click', function () {
        if (carrito.length === 0) {
            itemsWrap.innerHTML = `
                <div class="page-card cart-empty">
                    <h3>Tu carrito está vacío</h3>
                    <p>Agregá productos para continuar.</p>
                </div>
            `;
            renderSummary();
            return;
        }

        // vaciar carrito en memoria
        carrito = [];

        // limpiar localStorage
        window.CartUtils.clearCart();

        // actualizar UI
        renderItems();

        // mensaje final
        window.showToast('Pedido realizado correctamente');
    });

    /* =========================================
       INICIALIZACION:
       renderiza items y resumen
       ========================================= */
    renderItems();
});