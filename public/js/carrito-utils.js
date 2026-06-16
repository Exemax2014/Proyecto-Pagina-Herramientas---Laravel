(function () {
    const CART_STORAGE_KEY = 'hf_cart';
    const CART_MIGRATED_FLAG = 'hf_cart_migrated';
    const BACKEND_CART_COUNT_KEY = 'hf_backend_cart_count';
    let migrationPromise = null;

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

    function isAdminUser() {
        return getCartConfig().isAdmin === true || window.hfUserRole === 'admin';
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
            precio_anterior: Number(item?.precio_anterior ?? item?.precioAnterior) || 0,
            descuento_porcentaje: Number(item?.descuento_porcentaje ?? item?.descuentoPorcentaje) || 0,
            etiquetas: Array.isArray(item?.etiquetas) ? item.etiquetas : [],
            cantidad: Number(item?.cantidad) || 0,
            subtotal: Number(item?.subtotal) || ((Number(item?.precio_unitario ?? item?.precio) || 0) * (Number(item?.cantidad) || 0)),
            imagen: item?.imagen || item?.producto?.imagen || '/img/producto-sin-imagen.svg',
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
        localStorage.removeItem(CART_STORAGE_KEY);
        updateCartCount();
    }

    function getMigrationFlag() {
        return sessionStorage.getItem(CART_MIGRATED_FLAG) === '1';
    }

    function setMigrationFlag(value) {
        if (value) {
            sessionStorage.setItem(CART_MIGRATED_FLAG, '1');
            return;
        }

        sessionStorage.removeItem(CART_MIGRATED_FLAG);
    }

    function getBackendCartCountCache() {
        return Number(sessionStorage.getItem(BACKEND_CART_COUNT_KEY) || 0);
    }

    function setBackendCartCountCache(count) {
        sessionStorage.setItem(BACKEND_CART_COUNT_KEY, String(Number(count) || 0));
    }

    function clearBackendCartCountCache() {
        sessionStorage.removeItem(BACKEND_CART_COUNT_KEY);
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
            existingProduct.precio_anterior = Number(product.precio_anterior ?? product.precioAnterior) || existingProduct.precio_anterior || 0;
            existingProduct.descuento_porcentaje = Number(product.descuento_porcentaje ?? product.descuentoPorcentaje) || existingProduct.descuento_porcentaje || 0;
            existingProduct.etiquetas = Array.isArray(product.etiquetas) ? product.etiquetas : (existingProduct.etiquetas || []);
        } else {
            const precioUnitario = Number(product.precio_unitario ?? product.precio) || 0;

            cart.push({
                id: productId,
                producto_id: productId,
                nombre: product.nombre || 'Producto sin nombre',
                marca: product.marca || 'Sin marca',
                categoria: product.categoria || 'Sin categoria',
                precio_unitario: precioUnitario,
                precio_anterior: Number(product.precio_anterior ?? product.precioAnterior) || 0,
                descuento_porcentaje: Number(product.descuento_porcentaje ?? product.descuentoPorcentaje) || 0,
                etiquetas: Array.isArray(product.etiquetas) ? product.etiquetas : [],
                cantidad: amount,
                subtotal: precioUnitario * amount,
                imagen: product.imagen || '/img/producto-sin-imagen.svg',
            });
        }

        saveCart(cart);
        updateCartCount();

        return {
            ok: true,
            local: true,
            message: null,
            suppressToast: true,
            carrito: {
                items: cart,
                cantidad_total: getCartCount(),
            },
        };
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
        const count = getBackendCartCount(carrito);
        setBackendCartCountCache(count);
        renderCartCount(count);
        return count;
    }

    function getLocalCartItemsForMigration() {
        return normalizeLegacyCart(getCart())
            .map(item => ({
                producto_id: item.producto_id,
                cantidad: item.cantidad,
            }))
            .filter(item => item.producto_id > 0 && item.cantidad > 0);
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

    function redirectToLoginWithRedirect(redirectPath = '/carrito') {
        const loginUrl = new URL(getCartConfig().loginUrl, window.location.origin);
        loginUrl.searchParams.set('redirect', redirectPath);
        window.location.href = loginUrl.toString();
    }

    async function fetchBackendCart() {
        if (isAdminUser()) {
            throw Object.assign(new Error('Los administradores no pueden realizar compras.'), { status: 403 });
        }

        if (!isLoggedIn()) {
            throw Object.assign(new Error('Primero tenes que iniciar sesion.'), { status: 401 });
        }

        const data = await requestJson(getCartConfig().endpoints.obtener);
        updateCartCountFromCarrito(data.carrito);
        return data.carrito;
    }

    async function updateCartCount() {
        if (isAdminUser()) {
            clearBackendCartCountCache();
            renderCartCount(0);
            return 0;
        }

        if (isLoggedIn()) {
            const count = getBackendCartCountCache();
            renderCartCount(count);
            return count;
        }

        clearBackendCartCountCache();
        const count = getCartCount();
        renderCartCount(count);
        return count;
    }

    async function syncBackendCartCount() {
        if (isAdminUser()) {
            clearBackendCartCountCache();
            renderCartCount(0);
            return 0;
        }

        if (!isLoggedIn()) {
            clearBackendCartCountCache();
            renderCartCount(0);
            return 0;
        }

        const carrito = await fetchBackendCart();
        return getBackendCartCount(carrito);
    }

    async function addToCart(productOrId, quantity = 1) {
        if (isAdminUser()) {
            throw Object.assign(new Error('Los administradores no pueden realizar compras.'), { status: 403 });
        }

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
        if (isAdminUser()) {
            throw Object.assign(new Error('Los administradores no pueden realizar compras.'), { status: 403 });
        }

        const data = await requestJson(`${getCartConfig().endpoints.itemBase}/${itemId}`, {
            method: 'DELETE',
        });

        updateCartCountFromCarrito(data.carrito);
        return data;
    }

    async function updateBackendCartItem(itemId, quantity) {
        if (isAdminUser()) {
            throw Object.assign(new Error('Los administradores no pueden realizar compras.'), { status: 403 });
        }

        const updateUrlTemplate = getCartConfig().endpoints.actualizar || '';
        const updateUrl = updateUrlTemplate.replace('__ITEM__', itemId);

        const data = await requestJson(updateUrl, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cantidad: Number(quantity) || 1,
            }),
        });

        updateCartCountFromCarrito(data.carrito);
        return data;
    }

    async function confirmCart() {
        if (isAdminUser()) {
            throw Object.assign(new Error('Los administradores no pueden realizar compras.'), { status: 403 });
        }

        const data = await requestJson(getCartConfig().endpoints.confirmar, {
            method: 'POST',
        });

        clearCart();
        clearBackendCartCountCache();
        renderCartCount(0);
        return data;
    }

    async function migrateLocalCartIfNeeded() {
        if (isAdminUser()) {
            clearBackendCartCountCache();
            renderCartCount(0);
            return null;
        }

        if (migrationPromise) {
            return migrationPromise;
        }

        if (!isLoggedIn()) {
            setMigrationFlag(false);
            return null;
        }

        if (getMigrationFlag()) {
            return null;
        }

        const items = getLocalCartItemsForMigration();

        if (items.length === 0) {
            setMigrationFlag(true);
            return null;
        }

        migrationPromise = requestJson(getCartConfig().endpoints.migrar, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ items }),
        }).then(data => {
            const remainingItems = Array.isArray(data.remaining_items) ? data.remaining_items : [];

            if (remainingItems.length > 0) {
                const currentCart = normalizeLegacyCart(getCart());
                const currentByProductId = new Map(currentCart.map(item => [Number(item.producto_id), item]));

                const leftovers = remainingItems
                    .map(item => {
                        const productId = Number(item.producto_id);
                        const current = currentByProductId.get(productId);

                        if (!current) {
                            return null;
                        }

                        return {
                            ...current,
                            producto_id: productId,
                            cantidad: Number(item.cantidad) || current.cantidad,
                            subtotal: current.precio_unitario * (Number(item.cantidad) || current.cantidad),
                        };
                    })
                    .filter(Boolean);

                if (leftovers.length > 0) {
                    saveCart(leftovers);
                } else {
                    clearCart();
                }
            } else {
                clearCart();
            }

            setMigrationFlag(true);

            if (data.carrito) {
                updateCartCountFromCarrito(data.carrito);
            }

            return data;
        }).finally(() => {
            migrationPromise = null;
        });

        return migrationPromise;
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
        updateBackendCartItem,
        confirmCart,
        migrateLocalCartIfNeeded,
        redirectToLoginWithRedirect,
        updateCartCountFromCarrito,
        syncBackendCartCount,
    };

    document.addEventListener('DOMContentLoaded', async function () {
        await updateCartCount();

        let migration = null;

        try {
            migration = await migrateLocalCartIfNeeded();

            if (migration?.warnings?.length && window.showToast) {
                window.showToast(migration.warnings[0], 'top');
            } else if (migration?.migrados > 0 && window.showToast) {
                window.showToast(migration.message || 'El carrito temporal se migro correctamente.', 'top');
            }
        } catch (error) {
            console.error('No se pudo migrar el carrito temporal:', error);
        }

        if (isLoggedIn() && !migration?.carrito) {
            try {
                await syncBackendCartCount();
            } catch (error) {
                console.error('No se pudo sincronizar el contador del carrito:', error);
            }
        }
    });
})();
