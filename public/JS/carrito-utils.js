/* ═════════════════════════════════════════════════════════════════════════════════════════ */
/* ----------------------------------- UTILIDADES CARRITO --------------------------------- */
/* ═════════════════════════════════════════════════════════════════════════════════════════ */

(function () {
    const CART_STORAGE_KEY = 'hf_cart';

    /* =========================================
       OBTENER CARRITO:
       lee el carrito guardado en localStorage
       ========================================= */
    function getCart() {
        try {
            const savedCart = localStorage.getItem(CART_STORAGE_KEY);
            return savedCart ? JSON.parse(savedCart) : [];
        } catch (error) {
            console.error('Error al leer el carrito:', error);
            return [];
        }
    }

    /* =========================================
       GUARDAR CARRITO:
       persiste el carrito actualizado
       ========================================= */
    function saveCart(cart) {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
    }

    /* =========================================
       AGREGAR AL CARRITO:
       suma un producto nuevo o incrementa cantidad si ya existe
       ========================================= */
    function addToCart(product, quantity = 1) {
        const cart = getCart();
        const existingProduct = cart.find(item => Number(item.id) === Number(product.id));

        if (existingProduct) {
            existingProduct.cantidad += quantity;
        } else {
            cart.push({
                id: product.id,
                nombre: product.nombre,
                marca: product.marca,
                categoria: product.categoria,
                energia: product.energia,
                precio: product.precio,
                imagen: product.imagen,
                cantidad: quantity
            });
        }

        saveCart(cart);
        updateCartCount();
    }

    /* =========================================
       ELIMINAR DEL CARRITO:
       remueve un producto por id
       ========================================= */
    function removeFromCart(productId) {
        const cart = getCart().filter(item => Number(item.id) !== Number(productId));
        saveCart(cart);
        updateCartCount();
    }

    /* =========================================
       ACTUALIZAR CANTIDAD:
       modifica la cantidad de un producto existente
       ========================================= */
    function updateCartQty(productId, quantity) {
        const cart = getCart();
        const item = cart.find(product => Number(product.id) === Number(productId));

        if (!item) return;

        item.cantidad = Math.max(1, quantity);
        saveCart(cart);
        updateCartCount();
    }

    /* =========================================
       VACIAR CARRITO:
       elimina todos los productos
       ========================================= */
    function clearCart() {
        saveCart([]);
        updateCartCount();
    }

    /* =========================================
       CONTADOR DEL CARRITO:
       devuelve cantidad total de unidades
       ========================================= */
    function getCartCount() {
        return getCart().reduce((acc, item) => acc + Number(item.cantidad || 0), 0);
    }

    /* =========================================
       ACTUALIZAR CONTADOR VISUAL:
       busca elementos del navbar o interfaz y actualiza el número
       ========================================= */
   function updateCartCount() {
        const count = getCartCount();
        const countElements = document.querySelectorAll('[data-cart-count]');

        countElements.forEach(element => {
            element.textContent = count > 0 ? count : '';
            element.setAttribute('data-count', count);
        });
    }

    /* =========================================
       EXPONER FUNCIONES:
       las deja disponibles en todo el proyecto
       ========================================= */
    window.CartUtils = {
        getCart,
        saveCart,
        addToCart,
        removeFromCart,
        updateCartQty,
        clearCart,
        getCartCount,
        updateCartCount
    };

    /* =========================================
       INICIALIZACION:
       actualiza contador apenas carga el sitio
       ========================================= */
    document.addEventListener('DOMContentLoaded', updateCartCount);
})();