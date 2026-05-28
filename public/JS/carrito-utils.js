(function () {
    const CART_STORAGE_KEY = 'hf_cart';

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function getCartConfig() {
        return window.hfCartConfig || {
            loggedIn: false,
            loginUrl: '/login',
            endpoints: {},
        };
    }

    function isLoggedIn() {
        if (typeof window.usuarioLogueado !== 'undefined') {
            return Boolean(window.usuarioLogueado);
        }

        if (document.body?.dataset?.usuarioLogueado) {
            return document.body.dataset.usuarioLogueado === '1';
        }

        return Boolean(getCartConfig().loggedIn);
    }

    function getCart() {
        try {
            const savedCart = localStorage.getItem(CART_STORAGE_KEY);
            return savedCart ? JSON.parse(savedCart) : [];
        } catch (error) {
            console.error('Error al leer el carrito local:', error);
            return [];
        }
    }

    function saveCart(cart) {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
    }

    function normalizeLegacyCart(cart) {
        if (!Array.isArray(cart)) {
            return [];
        }

        return cart.map(item => ({
            id: Number(item?.id) || 0,
            producto_id: Number(item?.producto_id ?? item?.id) || 0,
            nombre: item?.nombre || item?.producto_nombre || 'Producto sin nombre',
            marca: item?.marca || item?.producto_marca || 'Sin marca',
            categoria: item?.categoria || item?.producto_categoria || 'Sin categoria',
            precio_unitario: Number(item?.precio_unitario ?? item?.precio) || 0,
            cantidad: Number(item?.cantidad) || 0,
            subtotal: Number(item?.subtotal) || ((Number(item?.precio_unitario ?? item?.precio) || 0) * (Number(item?.cantidad) || 0)),
            imagen: item?.imagen || item?.producto?.imagen || '/img/producto-sin-imagen.png',
        })).filter(item => item.producto_id > 0 || item.id > 0);
    }

    function removeFromCart(productId) {
        const cart = normalizeLegacyCart(getCart()).filter(item => Number(item.producto_id) !== Number(productId) && Number(item.id) !== Number(productId));
        saveCart(cart);
        updateCartCount();
    }

    function updateCartQty(productId, quantity) {
        const cart = normalizeLegacyCart(getCart());
        const item = cart.find(product => Number(product.producto_id) === Number(productId) || Number(product.id) === Number(productId));

        if (!item) return;

        item.cantidad = Math.max(1, quantity);
        item.subtotal = item.precio_unitario * item.cantidad;
        saveCart(cart);
        updateCartCount();
    }

    function clearCart() {
        saveCart([]);
        updateCartCount();
    }

    function getCartCount() {
        return normalizeLegacyCart(getCart()).reduce((acc, item) => acc + (Number(item.cantidad) || 0), 0);
    }

    function addToLocalCart(productOrId, quantity = 1) {
        const product = typeof productOrId === 'object' ? productOrId : null;

        if (!product || !product.id) {
            return { ok: false, message: 'No se pudo identificar el producto.' };
        }

        const cart = normalizeLegacyCart(getCart());
        const productId = Number(product.id);
        const amount = Math.max(1, Number(quantity) || 1);
        const existingProduct = cart.find(item => Number(item.producto_id) === productId);

        if (existingProduct) {
            existingProduct.cantidad += amount;
            existingProduct.subtotal = existingProduct.precio_unitario * existingProduct.cantidad;
        } else {
            const precioUnitario = Number(product.precio_unitario ?? product.precio) || 0;

            cart.push({
                id: productId,
                producto_id: productId,
                nombre: product.nombre || 'Producto sin nombre',
                marca: product.marca || 'Sin marca',
                categoria: product.categoria || 'Sin categoria',
                precio_unitario: precioUnitario,
                cantidad: amount,
                subtotal: precioUnitario * amount,
                imagen: product.imagen || '/img/producto-sin-imagen.png',
            });
        }

        saveCart(cart);
        updateCartCount();

        return {
            ok: true,
            local: true,
            message: 'Producto agregado al carrito',
            carrito: {
                items: cart,
                cantidad_total: getCartCount(),
            },
        };
    }

    function syncLegacyCartFromBackend(carrito) {
        const items = normalizeLegacyCart(carrito?.items || []);
        saveCart(items);
    }

    function getBackendCartCount(carrito) {
        const items = Array.isArray(carrito?.items) ? carrito.items : [];
        return items.reduce((acc, item) => acc + (Number(item?.cantidad) || 0), 0);
    }

    function renderCartCount(count) {
        const countElements = document.querySelectorAll('[data-cart-count]');

        countElements.forEach(element => {
            element.textContent = count > 0 ? count : '';
            element.setAttribute('data-count', count);
        });
    }

    function updateCartCountFromCarrito(carrito) {
        syncLegacyCartFromBackend(carrito);
        const count = getBackendCartCount(carrito);
        renderCartCount(count);
        return count;
    }

    async function requestJson(url, options = {}) {
        const headers = {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(options.headers || {}),
        };

        if (options.method && options.method !== 'GET') {
            headers['X-CSRF-TOKEN'] = getCsrfToken();
        }

        const response = await fetch(url, {
            credentials: 'same-origin',
            ...options,
            headers,
        });

        const contentType = response.headers.get('content-type') || '';

        if (!contentType.includes('application/json')) {
            const authError = new Error('Primero tenes que iniciar sesion.');
            authError.status = 401;

            if (response.redirected || response.url.includes('/login')) {
                throw authError;
            }

            const invalidResponseError = new Error('La respuesta del servidor no es valida.');
            invalidResponseError.status = response.status || 500;
            throw invalidResponseError;
        }

        const data = await response.json();

        if (!response.ok) {
            const error = new Error(data.message || 'Ocurrio un error al procesar la solicitud.');
            error.status = response.status;
            error.data = data;
            throw error;
        }

        return data;
    }

    function redirectToLogin() {
        window.location.href = getCartConfig().loginUrl;
    }

    async function fetchBackendCart() {
        if (!isLoggedIn()) {
            throw Object.assign(new Error('Primero tenes que iniciar sesion.'), { status: 401 });
        }

        const data = await requestJson(getCartConfig().endpoints.obtener);
        syncLegacyCartFromBackend(data.carrito);
        return data.carrito;
    }

    async function updateCartCount() {
        const count = getCartCount();
        renderCartCount(count);
        return count;
    }

    async function addToCart(productOrId, quantity = 1) {
        if (!isLoggedIn()) {
            return addToLocalCart(productOrId, quantity);
        }

        const productId = typeof productOrId === 'object'
            ? productOrId.id
            : productOrId;

        const data = await requestJson(getCartConfig().endpoints.agregar, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                producto_id: Number(productId),
                cantidad: Number(quantity) || 1,
            }),
        });

        updateCartCountFromCarrito(data.carrito);
        return data;
    }

    async function removeCartItem(itemId) {
        const data = await requestJson(`${getCartConfig().endpoints.itemBase}/${itemId}`, {
            method: 'DELETE',
        });

        updateCartCountFromCarrito(data.carrito);
        return data;
    }

    async function confirmCart() {
        const data = await requestJson(getCartConfig().endpoints.confirmar, {
            method: 'POST',
        });

        clearCart();
        renderCartCount(0);
        return data;
    }

    window.CartUtils = {
        getCart,
        saveCart,
        addToCart,
        addToLocalCart,
        removeFromCart,
        updateCartQty,
        clearCart,
        getCartCount,
        updateCartCount,
        fetchBackendCart,
        removeCartItem,
        confirmCart,
        updateCartCountFromCarrito,
    };

    document.addEventListener('DOMContentLoaded', function () {
        updateCartCount();
    });
})();
