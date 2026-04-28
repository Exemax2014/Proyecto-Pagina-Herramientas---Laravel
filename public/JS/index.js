document.addEventListener('DOMContentLoaded', function () {
    const productos = Array.isArray(window.catalogoProductos) ? window.catalogoProductos : [];
    const routeCatalogoBase = window.routeCatalogoBase || '/catalogo';
    const routeProductoBase = window.routeProductoBase || '/producto';

    function formatPrice(value) {
        return '$' + Number(value).toLocaleString('es-AR');
    }

    function createBadgeHtml(producto) {
        if (!producto.etiqueta) return '';

        const extraClass = producto.etiquetaClase ? ` ${producto.etiquetaClase}` : '';

        return `<span class="product-card-badge${extraClass}">${producto.etiqueta}</span>`;
    }

    function createOldPriceHtml(producto) {
        if (!producto.precioAnterior) return '';

        return `<small>${formatPrice(producto.precioAnterior)}</small>`;
    }

    /* MARCAS AUTOMATICAS */
    const marcas = [...new Set(productos.map(producto => producto.marca))];
    const brandContainer = document.getElementById('homeBrands');

    if (brandContainer) {
        brandContainer.innerHTML = marcas.map(marca => `
            <a href="${routeCatalogoBase}?marca=${encodeURIComponent(marca)}" class="home-brand-link">
                <span class="home-brand">${marca}</span>
            </a>
        `).join('');
    }

    /* CATEGORIAS DEL HOME */
    const categorias = [
        {
            nombre: 'Herrería',
            slug: 'herreria',
            clase: 'category-card-large',
            imagen: '/img/categorias/herreria.jpg'
        },
        {
            nombre: 'Carpintería',
            slug: 'carpinteria',
            clase: 'category-card-large',
            imagen: '/img/categorias/carpinteria.jpg'
        },
        {
            nombre: 'Construcción',
            slug: 'construccion',
            clase: 'category-card-medium',
            imagen: '/img/categorias/construccion.jpg'
        },
        {
            nombre: 'Durlok',
            slug: 'durlok',
            clase: 'category-card-medium',
            imagen: '/img/categorias/durlock.jpg'
        },
        {
            nombre: 'Ferretería',
            slug: 'ferreteria',
            clase: 'category-card-small',
            imagen: '/img/categorias/ferreteria.jpg'
        },
        {
            nombre: 'Pinturería',
            slug: 'pintureria',
            clase: 'category-card-small',
            imagen: '/img/categorias/pintura.jpg'
        }
    ];

    const categoriesContainer = document.getElementById('homeCategoriesGrid');

    if (categoriesContainer) {
        categoriesContainer.innerHTML = categorias.map(categoria => `
            <a href="${routeCatalogoBase}?categoria=${encodeURIComponent(categoria.slug)}"
               class="category-card ${categoria.clase}"
               style="--category-image: url('${categoria.imagen}')">
                <span>${categoria.nombre}</span>
            </a>
        `).join('');
    }

    /* OFERTAS DESTACADAS */
    const offersContainer = document.getElementById('homeOffers');
    const offersPrev = document.getElementById('offersPrev');
    const offersNext = document.getElementById('offersNext');

    let offerStartIndex = 0;

    const ofertas = productos.filter(producto => {
        return producto.etiqueta === 'Oferta' || producto.precioAnterior;
    });

    function getVisibleOffers() {
        if (window.innerWidth < 768) return 2;
        if (window.innerWidth < 1200) return 3;
        return 4;
    }

    function renderHomeOffers() {
        if (!offersContainer) return;

        const visibleOffers = getVisibleOffers();

        if (offerStartIndex + visibleOffers > ofertas.length) {
            offerStartIndex = Math.max(0, ofertas.length - visibleOffers);
        }

        const ofertasVisibles = ofertas.slice(offerStartIndex, offerStartIndex + visibleOffers);

        offersContainer.innerHTML = ofertasVisibles.map(producto => `
            <article 
                class="page-card product-card home-product-card" 
                data-product-id="${producto.id}"
                role="link"
                tabindex="0"
                aria-label="Ver detalle de ${producto.nombre}"
            >
                <div class="product-card-media">
                    <img src="${producto.imagen}" alt="${producto.nombre}">
                    ${createBadgeHtml(producto)}

                    <button 
                        class="product-card-action home-cart-btn" 
                        type="button" 
                        data-product-id="${producto.id}" 
                        aria-label="Agregar al carrito"
                    >
                        <i class="bi bi-cart-plus"></i>
                    </button>
                </div>

                <div class="product-card-body">
                    <span class="product-card-brand">${producto.marca}</span>
                    <h3>${producto.nombre}</h3>
                    <p>${producto.descripcion}</p>

                    <div class="product-card-footer">
                        <div class="product-card-price">
                            ${createOldPriceHtml(producto)}
                            <strong>${formatPrice(producto.precio)}</strong>
                        </div>
                    </div>
                </div>
            </article>
        `).join('');

        updateOfferControls();
        bindHomeOfferEvents();
    }

    function updateOfferControls() {
        if (!offersPrev || !offersNext) return;

        const visibleOffers = getVisibleOffers();

        offersPrev.disabled = offerStartIndex === 0;
        offersNext.disabled = offerStartIndex + visibleOffers >= ofertas.length;

        offersPrev.classList.toggle('is-disabled', offersPrev.disabled);
        offersNext.classList.toggle('is-disabled', offersNext.disabled);
    }

    function bindHomeOfferEvents() {
        offersContainer.querySelectorAll('.home-product-card').forEach(card => {
            card.addEventListener('click', function (event) {
                if (event.target.closest('.home-cart-btn')) return;

                const productId = this.dataset.productId;
                window.location.href = `${routeProductoBase}/${productId}`;
            });

            card.addEventListener('keydown', function (event) {
                if (event.key !== 'Enter' && event.key !== ' ') return;
                if (event.target.closest('.home-cart-btn')) return;

                event.preventDefault();

                const productId = this.dataset.productId;
                window.location.href = `${routeProductoBase}/${productId}`;
            });
        });

        offersContainer.querySelectorAll('.home-cart-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.stopPropagation();

                const productId = Number(this.dataset.productId);
                const product = productos.find(item => Number(item.id) === productId);

                if (!product) return;

                window.CartUtils.addToCart(product, 1);
                window.showToast('Producto agregado al carrito');
            });
        });
    }

    offersPrev?.addEventListener('click', function () {
        if (offerStartIndex === 0) return;

        offerStartIndex -= 1;
        renderHomeOffers();
    });

    offersNext?.addEventListener('click', function () {
        const visibleOffers = getVisibleOffers();

        if (offerStartIndex + visibleOffers >= ofertas.length) return;

        offerStartIndex += 1;
        renderHomeOffers();
    });

    window.addEventListener('resize', function () {
        renderHomeOffers();
    });

    renderHomeOffers();
});