document.addEventListener('DOMContentLoaded', function () {
    const productos = window.catalogoProductos || [];
    const routeCatalogoBase = window.routeCatalogoBase || '/catalogo';
    const routeProductoBase = window.routeProductoBase || '/producto';

    /* =========================================
       FORMATEO DE PRECIO:
       convierte valores numéricos al formato ARS
       ========================================= */
    function formatPrice(value) {
        return '$' + Number(value).toLocaleString('es-AR');
    }

    /* =========================================
       MARCAS AUTOMATICAS:
       toma marcas únicas desde el catálogo y genera links al catálogo filtrado
       ========================================= */
    const marcas = [...new Set(productos.map(producto => producto.marca))];
    const brandContainer = document.getElementById('homeBrands');

    if (brandContainer) {
        brandContainer.innerHTML = marcas.map(marca => `
            <a href="${routeCatalogoBase}?marca=${encodeURIComponent(marca)}" class="home-brand-link">
                <span class="home-brand">${marca}</span>
            </a>
        `).join('');
    }

    /* =========================================
        CATEGORIAS DEL HOME:
        se renderizan desde JS para dejarlas listas
        a futuro para base de datos
        ========================================= */
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
            nombre: 'Durlock',
            slug: 'durlock',
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

    /* =========================================
       OFERTAS DESTACADAS:
       toma productos con precio anterior y arma cards visuales completas
       ========================================= */
    const offersContainer = document.getElementById('homeOffers');
    const ofertas = productos.filter(producto => producto.precioAnterior).slice(0, 4);

    if (offersContainer) {
        offersContainer.innerHTML = ofertas.map(producto => `
            <article class="home-card catalog-product-card">
                <a href="${routeProductoBase}/${producto.id}" class="home-product-link">
                    <div class="home-product-media">
                        <img src="${producto.imagen}" alt="${producto.nombre}">
                        <span class="home-product-badge">Oferta</span>
                    </div>

                    <div class="home-product-body">
                        <span class="home-product-brand">${producto.marca}</span>
                        <h3>${producto.nombre}</h3>

                        <div class="home-product-price-wrap">
                            <span class="home-product-price">${formatPrice(producto.precio)}</span>
                            <span class="home-product-old-price">${formatPrice(producto.precioAnterior)}</span>
                        </div>
                    </div>
                </a>
            </article>
        `).join('');
    }
});