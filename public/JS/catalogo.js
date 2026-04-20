document.addEventListener('DOMContentLoaded', function () {
    /* =========================================
       DATOS TEMPORALES DEL CATALOGO:
       toma el array global cargado desde catalogo-productos.js
       ========================================= */
    const productos = Array.isArray(window.catalogoProductos) ? window.catalogoProductos : [];

    /* =========================================
       REFERENCIAS GENERALES DEL DOM:
       elementos principales de búsqueda, orden, paginación y layout
       ========================================= */
    const searchInput = document.getElementById('catalogSearch');
    const sortMarketSelect = document.getElementById('catalogSortMarket');
    const sortNameSelect = document.getElementById('catalogSortName');
    const rangeInput = document.getElementById('catalogPriceRange');
    const rangeValue = document.getElementById('catalogPriceValue');
    const resetBtn = document.getElementById('resetCatalogFilters');
    const filterToggle = document.getElementById('catalogFilterToggle');
    const filterClose = document.getElementById('catalogFilterClose');
    const sidebar = document.getElementById('catalogSidebar');
    const grid = document.getElementById('catalogGrid');
    const emptyState = document.getElementById('catalogEmptyState');
    const pagination = document.querySelector('.catalog-pagination');

    /* =========================================
       FILTROS FIJOS:
       categorías y tipo de energía ya existen en el HTML desde el inicio
       ========================================= */
    const categoryChecks = Array.from(document.querySelectorAll('.filter-category'));
    const energyRadios = Array.from(document.querySelectorAll('input[name="energy"]'));

    /* =========================================
       PAGINACION:
       define página actual y cantidad de productos por página
       ========================================= */
    let currentPage = 1;
    const itemsPerPage = 9;

    /* =========================================
       FORMATEO DE PRECIO:
       convierte números a formato monetario argentino
       ========================================= */
    function formatPrice(value) {
        return '$' + Number(value).toLocaleString('es-AR');
    }

    /* =========================================
       MARCAS SELECCIONADAS:
       obtiene dinámicamente las marcas activas
       ========================================= */
    function getSelectedBrands() {
        return Array.from(document.querySelectorAll('.filter-brand:checked'))
            .map(check => check.value);
    }

    /* =========================================
       ENERGIA SELECCIONADA:
       devuelve el valor activo del grupo de radios
       ========================================= */
    function getSelectedEnergy() {
        const selected = energyRadios.find(radio => radio.checked);
        return selected ? selected.value : '';
    }

    /* =========================================
       GENERADOR AUTOMATICO DE MARCAS:
       crea el bloque de filtros de marca a partir de los productos cargados
       ========================================= */
    function generarMarcas(productos) {
        const contenedor = document.getElementById('brandFilters');
        if (!contenedor) return;

        const marcasUnicas = [...new Set(productos.map(producto => producto.marca))]
            .sort((a, b) => a.localeCompare(b, 'es'));

        contenedor.innerHTML = '';

        marcasUnicas.forEach(marca => {
            contenedor.innerHTML += `
                <label class="catalog-check">
                    <input type="checkbox" class="filter-brand" value="${marca}">
                    <span>${marca}</span>
                </label>
            `;
        });
    }

    /* =========================================
       EVENTOS DE MARCAS:
       como las marcas se generan dinámicamente, sus eventos se asignan después
       ========================================= */
    function bindBrandEvents() {
        const brandChecks = Array.from(document.querySelectorAll('.filter-brand'));

        brandChecks.forEach(check => {
            check.addEventListener('change', function () {
                currentPage = 1;
                renderProducts();
            });
        });
    }

    /* =========================================
       FILTRADO PRINCIPAL:
       aplica búsqueda, categoría, marca, energía y precio
       ========================================= */
    function getFilteredProducts() {
        const search = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const maxPrice = rangeInput ? Number(rangeInput.value) : 300000;
        const selectedEnergy = getSelectedEnergy();
        const selectedBrands = getSelectedBrands();

        const selectedCategories = categoryChecks
            .filter(check => check.checked)
            .map(check => check.value);

        return productos.filter(producto => {
            const matchesSearch = producto.nombre.toLowerCase().includes(search);
            const matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(producto.categoria);
            const matchesBrand = selectedBrands.length === 0 || selectedBrands.includes(producto.marca);
            const matchesEnergy = !selectedEnergy || producto.energia === selectedEnergy;
            const matchesPrice = Number(producto.precio) <= maxPrice;

            return matchesSearch && matchesCategory && matchesBrand && matchesEnergy && matchesPrice;
        });
    }

    /* =========================================
       ORDENAMIENTO:
       usa un select para precio/ventas y otro para nombre
       ========================================= */
    function sortProducts(productosFiltrados) {
        const marketSort = sortMarketSelect ? sortMarketSelect.value : 'default';
        const nameSort = sortNameSelect ? sortNameSelect.value : 'default';

        const productosOrdenados = [...productosFiltrados];

        if (marketSort !== 'default') {
            productosOrdenados.sort((a, b) => {
                switch (marketSort) {
                    case 'price-asc':
                        return a.precio - b.precio;
                    case 'price-desc':
                        return b.precio - a.precio;
                    case 'best-sellers':
                        return Number(b.ventas || 0) - Number(a.ventas || 0);
                    default:
                        return 0;
                }
            });

            return productosOrdenados;
        }

        if (nameSort !== 'default') {
            productosOrdenados.sort((a, b) => {
                switch (nameSort) {
                    case 'name-asc':
                        return a.nombre.localeCompare(b.nombre, 'es');
                    case 'name-desc':
                        return b.nombre.localeCompare(a.nombre, 'es');
                    default:
                        return 0;
                }
            });
        }

        return productosOrdenados;
    }

    /* =========================================
       ETIQUETA VISUAL DEL PRODUCTO:
       genera badges como Oferta, Nuevo o Destacado
       ========================================= */
    function createBadgeHtml(producto) {
        if (!producto.etiqueta) return '';

        const extraClass = producto.etiquetaClase ? ` ${producto.etiquetaClase}` : '';
        return `<span class="catalog-product-badge${extraClass}">${producto.etiqueta}</span>`;
    }

    /* =========================================
       PRECIO ANTERIOR:
       solo se muestra si el producto lo tiene cargado
       ========================================= */
    function createOldPriceHtml(producto) {
        if (!producto.precioAnterior) return '';
        return `<small>${formatPrice(producto.precioAnterior)}</small>`;
    }

    /* =========================================
       PAGINACION DE PRODUCTOS:
       corta el array según la página actual
       ========================================= */
    function paginateProducts(productosOrdenados) {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return productosOrdenados.slice(start, end);
    }

    /* =========================================
       RENDER DE BOTONES DE PAGINACION:
       genera los números según la cantidad de páginas
       ========================================= */
    function renderPagination(totalItems) {
        if (!pagination) return;

        const totalPages = Math.ceil(totalItems / itemsPerPage);

        if (totalPages <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let buttonsHtml = '';

        for (let page = 1; page <= totalPages; page++) {
            buttonsHtml += `
                <button
                    type="button"
                    class="catalog-page-btn ${page === currentPage ? 'active' : ''}"
                    data-page="${page}">
                    ${page}
                </button>
            `;
        }

        pagination.innerHTML = buttonsHtml;

        pagination.querySelectorAll('.catalog-page-btn').forEach(button => {
            button.addEventListener('click', function () {
                currentPage = Number(this.dataset.page);
                renderProducts();
            });
        });
    }

    /* =========================================
       RENDER DE PRODUCTOS:
       dibuja la grilla completa según filtros, orden y página actual
       ========================================= */
    function renderProducts() {
        if (!grid) return;

        const filtrados = getFilteredProducts();
        const ordenados = sortProducts(filtrados);

        const totalPages = Math.ceil(ordenados.length / itemsPerPage);
        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }

        const productosPaginados = paginateProducts(ordenados);

        grid.innerHTML = productosPaginados.map(producto => `
            <article class="page-card catalog-product-card">
                <div class="catalog-product-media">
                    <img src="${producto.imagen}" alt="${producto.nombre}">
                    ${createBadgeHtml(producto)}
                    <button class="catalog-product-action" type="button" aria-label="Agregar al carrito">
                        <i class="bi bi-cart-plus"></i>
                    </button>
                </div>

                <div class="catalog-product-body">
                    <span class="catalog-product-brand">${producto.marca}</span>
                    <h3>${producto.nombre}</h3>
                    <p>${producto.descripcion}</p>

                    <div class="catalog-product-footer">
                        <div class="catalog-product-price">
                            ${createOldPriceHtml(producto)}
                            <strong>${formatPrice(producto.precio)}</strong>
                        </div>
                    </div>
                </div>
            </article>
        `).join('');

        if (emptyState) {
            emptyState.classList.toggle('d-none', ordenados.length > 0);
        }

        renderPagination(ordenados.length);
    }

    /* =========================================
       RANGO DE PRECIO:
       actualiza texto y vuelve a la primera página
       ========================================= */
    if (rangeInput && rangeValue) {
        rangeInput.addEventListener('input', function () {
            rangeValue.textContent = formatPrice(this.value);
            currentPage = 1;
            renderProducts();
        });
    }

    /* =========================================
       BUSQUEDA Y ORDEN:
       reaccionan automáticamente y reinician paginación
       ========================================= */
    searchInput?.addEventListener('input', function () {
        currentPage = 1;
        renderProducts();
    });

    sortMarketSelect?.addEventListener('change', function () {
        currentPage = 1;
        renderProducts();
    });

    sortNameSelect?.addEventListener('change', function () {
        currentPage = 1;
        renderProducts();
    });

    /* =========================================
       FILTROS FIJOS:
       categorías y energía reinician paginación
       ========================================= */
    categoryChecks.forEach(check => {
        check.addEventListener('change', function () {
            currentPage = 1;
            renderProducts();
        });
    });

    energyRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            currentPage = 1;
            renderProducts();
        });
    });

    /* =========================================
       RESET GENERAL:
       limpia filtros y vuelve a página 1
       ========================================= */
    resetBtn?.addEventListener('click', function () {
        if (searchInput) searchInput.value = '';
        if (sortMarketSelect) sortMarketSelect.value = 'default';
        if (sortNameSelect) sortNameSelect.value = 'default';
        if (rangeInput) rangeInput.value = 300000;
        if (rangeValue) rangeValue.textContent = formatPrice(300000);

        categoryChecks.forEach(check => {
            check.checked = false;
        });

        document.querySelectorAll('.filter-brand').forEach(check => {
            check.checked = false;
        });

        energyRadios.forEach(radio => {
            radio.checked = radio.value === '';
        });

        currentPage = 1;
        renderProducts();
    });

    /* =========================================
       SIDEBAR RESPONSIVE:
       abre y cierra el bloque de filtros debajo de la toolbar
       ========================================= */
    filterToggle?.addEventListener('click', function () {
        sidebar?.classList.toggle('is-open');
    });

    filterClose?.addEventListener('click', function () {
        sidebar?.classList.remove('is-open');
    });

    /* =========================================
       INICIALIZACION:
       primero genera marcas, luego enlaza eventos y finalmente renderiza
       ========================================= */
    generarMarcas(productos);
    bindBrandEvents();
    renderProducts();
});