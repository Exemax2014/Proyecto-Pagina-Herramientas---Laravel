document.addEventListener('DOMContentLoaded', function () {
    const productos = Array.isArray(window.catalogoProductos) ? window.catalogoProductos : [];

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

    const categoryChecks = Array.from(document.querySelectorAll('.filter-category'));
    const energyRadios = Array.from(document.querySelectorAll('input[name="energy"]'));

    let currentPage = 1;
    const itemsPerPage = 12;

    function formatPrice(value) {
        return '$' + Number(value).toLocaleString('es-AR');
    }

    function getSelectedBrands() {
        return Array.from(document.querySelectorAll('.filter-brand:checked'))
            .map(check => check.value);
    }

    function getSelectedEnergy() {
        const selected = energyRadios.find(radio => radio.checked);
        return selected ? selected.value : '';
    }

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

    function bindBrandEvents() {
        const brandChecks = Array.from(document.querySelectorAll('.filter-brand'));

        brandChecks.forEach(check => {
            check.addEventListener('change', function () {
                currentPage = 1;
                renderProducts();
            });
        });
    }

    function applyFiltersFromUrl() {
        const params = new URLSearchParams(window.location.search);

        const categoryParam = params.get('categoria');
        const brandParam = params.get('marca');

        if (categoryParam) {
            categoryChecks.forEach(check => {
                check.checked = check.value === categoryParam;
            });
        }

        if (brandParam) {
            document.querySelectorAll('.filter-brand').forEach(check => {
                check.checked = check.value === brandParam;
            });
        }
    }

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

    function createBadgeHtml(producto) {
        if (!producto.etiqueta) return '';

        const extraClass = producto.etiquetaClase ? ` ${producto.etiquetaClase}` : '';

        return `<span class="product-card-badge${extraClass}">${producto.etiqueta}</span>`;
    }

    function createOldPriceHtml(producto) {
        if (!producto.precioAnterior) return '';

        return `<small>${formatPrice(producto.precioAnterior)}</small>`;
    }

    function paginateProducts(productosOrdenados) {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        return productosOrdenados.slice(start, end);
    }

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
            <article 
                class="page-card product-card catalog-product-card" 
                data-product-id="${producto.id}"
                role="link"
                tabindex="0"
                aria-label="Ver detalle de ${producto.nombre}"
            >
                <div class="product-card-media">
                    <img src="${producto.imagen}" alt="${producto.nombre}">
                    ${createBadgeHtml(producto)}

                    <button class="product-card-action catalog-cart-btn" type="button" data-product-id="${producto.id}" aria-label="Agregar al carrito">
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

        grid.querySelectorAll('.catalog-product-card').forEach(card => {
            card.addEventListener('click', function (event) {
                if (event.target.closest('.catalog-cart-btn')) {
                    return;
                }

                const productId = this.dataset.productId;
                window.location.href = `${window.routeProductoBase}/${productId}`;
            });

            card.addEventListener('keydown', function (event) {
                if (event.key !== 'Enter' && event.key !== ' ') return;

                if (event.target.closest('.catalog-cart-btn')) {
                    return;
                }

                event.preventDefault();

                const productId = this.dataset.productId;
                window.location.href = `${window.routeProductoBase}/${productId}`;
            });
        });

        grid.querySelectorAll('.catalog-cart-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.stopPropagation();

                const productId = Number(this.dataset.productId);
                const product = productos.find(item => Number(item.id) === productId);

                if (!product) return;

                window.CartUtils.addToCart(product, 1);
                window.showToast('Producto agregado al carrito');
            });
        });

        if (emptyState) {
            emptyState.classList.toggle('d-none', ordenados.length > 0);
        }

        renderPagination(ordenados.length);
    }

    if (rangeInput && rangeValue) {
        rangeInput.addEventListener('input', function () {
            rangeValue.textContent = formatPrice(this.value);
            currentPage = 1;
            renderProducts();
        });
    }

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

    filterToggle?.addEventListener('click', function () {
        sidebar?.classList.toggle('is-open');
    });

    filterClose?.addEventListener('click', function () {
        sidebar?.classList.remove('is-open');
    });

    const params = new URLSearchParams(window.location.search);
    const searchFromUrl = params.get('search');

    if (searchInput && searchFromUrl) {
        searchInput.value = searchFromUrl;
    }

    generarMarcas(productos);
    bindBrandEvents();
    applyFiltersFromUrl();
    renderProducts();
});