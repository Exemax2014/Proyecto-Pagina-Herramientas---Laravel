document.addEventListener('DOMContentLoaded', function () {
    /* =========================================
       DATOS TEMPORALES:
       toma productos desde el archivo compartido del catálogo
       ========================================= */
    const productos = Array.isArray(window.catalogoProductos) ? window.catalogoProductos : [];

    /* =========================================
       PARAMETROS DE URL:
       obtiene el id del producto solicitado
       ========================================= */ 
    const pathParts = window.location.pathname.split('/');
    const productId = Number(pathParts[pathParts.length - 1]);

    /* =========================================
       BUSQUEDA DE PRODUCTO:
       encuentra el producto actual según el id recibido
       ========================================= */
    const product = productos.find(item => Number(item.id) === productId);

    /* =========================================
       REFERENCIAS DEL DOM:
       elementos principales de la vista de detalle
       ========================================= */
    const breadcrumb = document.getElementById('productBreadcrumb');
    const mainImage = document.getElementById('productMainImage');
    const thumbs = document.getElementById('productThumbs');
    const badge = document.getElementById('productBadge');
    const brand = document.getElementById('productBrand');
    const title = document.getElementById('productTitle');
    const shortText = document.getElementById('productShort');
    const oldPrice = document.getElementById('productOldPrice');
    const price = document.getElementById('productPrice');
    const category = document.getElementById('productCategory');
    const brandMeta = document.getElementById('productBrandMeta');
    const energy = document.getElementById('productEnergy');
    const sales = document.getElementById('productSales');
    const description = document.getElementById('productDescription');
    const specs = document.getElementById('productSpecs');
    const related = document.getElementById('relatedProducts');
    const qtyEl = document.getElementById('productQty');
    const qtyMinus = document.getElementById('qtyMinus');
    const qtyPlus = document.getElementById('qtyPlus');
    const addToCartBtn = document.getElementById('productAddToCartBtn');

    /* =========================================
       CANTIDAD:
       valor inicial del selector de cantidad
       ========================================= */
    let qty = 1;

    /* =========================================
       FORMATEO DE PRECIO:
       convierte números al formato monetario argentino
       ========================================= */
    function formatPrice(value) {
        return '$' + Number(value).toLocaleString('es-AR');
    }

    /* =========================================
       TEXTO DE CATEGORIA:
       transforma el slug en texto más legible
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
       TEXTO DE ENERGIA:
       transforma el valor guardado en una etiqueta amigable
       ========================================= */
    function humanizeEnergy(value) {
        const map = {
            manual: 'Manual',
            electrica: 'Eléctrica',
            inalambrica: 'Inalámbrica'
        };

        return map[value] || value;
    }

    /* =========================================
       GALERIA:
       por ahora duplica la misma imagen para simular miniaturas
       luego se puede reemplazar por un array real de imágenes
       ========================================= */
    function getGallery(producto) {
        return [
            producto.imagen,
            producto.imagen,
            producto.imagen,
            producto.imagen
        ];
    }

    /* =========================================
       ESPECIFICACIONES:
       arma bloques de datos técnicos y comerciales
       ========================================= */
    function buildSpecs(producto) {
        return [
            {
                titulo: 'Datos comerciales',
                items: [
                    ['Marca', producto.marca],
                    ['Categoría', humanizeCategory(producto.categoria)],
                    ['Energía', humanizeEnergy(producto.energia)],
                    ['Ventas', String(producto.ventas || 0)]
                ]
            },
            {
                titulo: 'Características',
                items: [
                    ['Uso recomendado', 'Trabajo técnico y comercial'],
                    ['Condición', 'Producto nuevo'],
                    ['Disponibilidad', 'Sujeto a stock'],
                    ['Garantía', 'Según marca y proveedor']
                ]
            },
            {
                titulo: 'Información extra',
                items: [
                    ['Código interno', `PRD-${producto.id}`],
                    ['Entrega', 'Coordinada con atención'],
                    ['Retiro', 'Disponible en local'],
                    ['Consulta', 'Por WhatsApp o formulario']
                ]
            }
        ];
    }

    /* =========================================
       BREADCRUMB:
       muestra la ruta de navegación del producto
       ========================================= */
    function renderBreadcrumb(producto) {
        breadcrumb.innerHTML = `
            <a href="/">Inicio</a>
            <i class="bi bi-chevron-right"></i>
            <a href="/catalogo">Catálogo</a>
            <i class="bi bi-chevron-right"></i>
            <span>${producto.nombre}</span>
        `;
    }

    /* =========================================
       GALERIA VISUAL:
       renderiza imagen principal y miniaturas clickeables
       ========================================= */
    function renderGallery(producto) {
        const images = getGallery(producto);

        mainImage.src = images[0];
        mainImage.alt = producto.nombre;

        thumbs.innerHTML = images.map((image, index) => `
            <button type="button" class="product-thumb ${index === 0 ? 'active' : ''}" data-image="${image}">
                <img src="${image}" alt="${producto.nombre}">
            </button>
        `).join('');

        thumbs.querySelectorAll('.product-thumb').forEach(button => {
            button.addEventListener('click', function () {
                mainImage.src = this.dataset.image;

                thumbs.querySelectorAll('.product-thumb').forEach(item => {
                    item.classList.remove('active');
                });

                this.classList.add('active');
            });
        });
    }

    /* =========================================
       INFORMACION PRINCIPAL:
       carga marca, nombre, precio, meta y descripción
       ========================================= */
    function renderMainInfo(producto) {
        if (producto.etiqueta) {
            badge.textContent = producto.etiqueta;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }

        brand.textContent = producto.marca;
        title.textContent = producto.nombre;
        shortText.textContent = producto.descripcion;
        price.textContent = formatPrice(producto.precio);

        if (producto.precioAnterior) {
            oldPrice.textContent = formatPrice(producto.precioAnterior);
            oldPrice.classList.remove('d-none');
        } else {
            oldPrice.classList.add('d-none');
        }

        category.textContent = humanizeCategory(producto.categoria);
        brandMeta.textContent = producto.marca;
        energy.textContent = humanizeEnergy(producto.energia);
        sales.textContent = `${producto.ventas || 0} ventas`;
        description.textContent = producto.descripcion;
    }

    /* =========================================
       ESPECIFICACIONES VISUALES:
       crea las tarjetas de detalle técnico y comercial
       ========================================= */
    function renderSpecs(producto) {
        const blocks = buildSpecs(producto);

        specs.innerHTML = blocks.map(block => `
            <div class="page-card product-spec-card">
                <h3>${block.titulo}</h3>
                <div class="product-spec-list">
                    ${block.items.map(item => `
                        <div class="product-spec-item">
                            <span>${item[0]}</span>
                            <strong>${item[1]}</strong>
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    /* =========================================
       RELACIONADOS:
       muestra productos de la misma categoría
       ========================================= */
    function renderRelated(producto) {
        const relatedProducts = productos
            .filter(item => item.id !== producto.id && item.categoria === producto.categoria)
            .slice(0, 4);

        related.innerHTML = relatedProducts.map(item => `
            <article class="page-card product-related-card">
                <a href="/producto/${item.id}">
                    <img src="${item.imagen}" alt="${item.nombre}" class="product-related-image">
                    <div class="product-related-body">
                        <span class="product-related-brand">${item.marca}</span>
                        <h3>${item.nombre}</h3>
                        <span class="product-related-price">${formatPrice(item.precio)}</span>
                    </div>
                </a>
            </article>
        `).join('');
    }

    /* =========================================
       CANTIDAD:
       controla el selector +/- del producto
       ========================================= */
    function bindQty() {
        qtyMinus?.addEventListener('click', function () {
            qty = Math.max(1, qty - 1);
            qtyEl.textContent = qty;
        });

        qtyPlus?.addEventListener('click', function () {
            qty += 1;
            qtyEl.textContent = qty;
        });
    }

   /* =========================================
        BOTON AGREGAR AL CARRITO:
        guarda el producto actual con la cantidad seleccionada
        ========================================= */
    function bindAddToCart(producto) {
        addToCartBtn?.addEventListener('click', function () {
            if (!window.CartUtils) {
                console.error('CartUtils no está cargado');
                return;
            }

            window.CartUtils.addToCart(producto, qty);
            window.showToast('Producto agregado al carrito');
        });
    }

    /* =========================================
       ESTADO NO ENCONTRADO:
       muestra un mensaje si el id no existe
       ========================================= */
    if (!product) {
        document.getElementById('productDetailWrap').innerHTML = `
            <div class="page-card product-description-card">
                <h2>Producto no encontrado</h2>
                <p>No pudimos cargar el detalle solicitado.</p>
            </div>
        `;
        return;
    }

    /* =========================================
       INICIALIZACION:
       carga toda la vista con la información del producto
       ========================================= */
    renderBreadcrumb(product);
    renderGallery(product);
    renderMainInfo(product);
    renderSpecs(product);
    renderRelated(product);
    bindQty();
    bindAddToCart(product);
});