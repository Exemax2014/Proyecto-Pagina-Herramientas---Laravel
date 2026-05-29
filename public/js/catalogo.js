document.addEventListener('DOMContentLoaded', function () {

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

    function formatPrice(value) {
        return '$' + Number(value).toLocaleString('es-AR');
    }

    // Generar marcas desde el back
    function generarMarcas() {
        const contenedor = document.getElementById('brandFilters');
        if (!contenedor || !window.marcasDisponibles) return;

        contenedor.innerHTML = '';

        window.marcasDisponibles.forEach(marca => {
            contenedor.innerHTML += `
                <label class="catalog-check">
                    <input type="checkbox" class="filter-brand" value="${marca.nombre}">
                    <span>${marca.nombre}</span>
                </label>
            `;
        });

        bindBrandEvents();
    }

    function bindBrandEvents() {
        document.querySelectorAll('.filter-brand').forEach(check => {
            check.addEventListener('change', function () {
                currentPage = 1;
                fetchProductos();
            });
        });
    }

    function getParams() {
        const params = new URLSearchParams();

        // Categorías seleccionadas
        categoryChecks
            .filter(c => c.checked)
            .forEach(c => params.append('categorias[]', c.value));

        // Marcas seleccionadas
        document.querySelectorAll('.filter-brand:checked')
            .forEach(c => params.append('marcas[]', c.value));

        // Energía
        const energia = energyRadios.find(r => r.checked);
        if (energia && energia.value) params.set('energia', energia.value);

        // Precio máximo
        if (rangeInput) params.set('precio_max', rangeInput.value);

        // Búsqueda
        if (searchInput && searchInput.value.trim()) {
            params.set('search', searchInput.value.trim());
        }

        // Ordenamiento
        const marketSort = sortMarketSelect?.value;
        const nameSort = sortNameSelect?.value;
        if (marketSort && marketSort !== 'default') params.set('sort', marketSort);
        else if (nameSort && nameSort !== 'default') params.set('sort', nameSort);

        // Página
        params.set('page', currentPage);

        return params;
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

    function renderPagination(totalPaginas) {
        if (!pagination) return;

        if (totalPaginas <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let buttonsHtml = '';
        for (let page = 1; page <= totalPaginas; page++) {
            buttonsHtml += `
                <button type="button" class="catalog-page-btn ${page === currentPage ? 'active' : ''}" data-page="${page}">
                    ${page}
                </button>
            `;
        }

        pagination.innerHTML = buttonsHtml;

        pagination.querySelectorAll('.catalog-page-btn').forEach(button => {
            button.addEventListener('click', function () {
                currentPage = Number(this.dataset.page);
                fetchProductos();
            });
        });
    }

    function renderProducts(productos) {
        if (!grid) return;

        if (productos.length === 0) {
            grid.innerHTML = '';
            if (emptyState) emptyState.classList.remove('d-none');
            return;
        }

        if (emptyState) emptyState.classList.add('d-none');

        grid.innerHTML = productos.map(producto => `
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

        // Click en card
        grid.querySelectorAll('.catalog-product-card').forEach(card => {
            card.addEventListener('click', function (event) {
                if (event.target.closest('.catalog-cart-btn')) return;
                const productId = this.dataset.productId;
                window.location.href = `${window.routeProductoBase}/${productId}`;
            });

            card.addEventListener('keydown', function (event) {
                if (event.key !== 'Enter' && event.key !== ' ') return;
                if (event.target.closest('.catalog-cart-btn')) return;
                event.preventDefault();
                const productId = this.dataset.productId;
                window.location.href = `${window.routeProductoBase}/${productId}`;
            });
        });

        // Click en carrito
        grid.querySelectorAll('.catalog-cart-btn').forEach(button => {
            button.addEventListener('click', async function (event) {
                event.stopPropagation();
                const productId = Number(this.dataset.productId);
                const product = productos.find(item => Number(item.id) === productId);

                if (!product) return;

                this.disabled = true;

                try {
                    const response = await window.CartUtils.addToCart(product, 1);

                    if (!response?.suppressToast && response?.message) {
                        window.showToast(response.message);
                    }
                } catch (error) {
                    window.showToast(error.message || 'No se pudo agregar el producto');
                } finally {
                    this.disabled = false;
                }
            });
        });
    }

    function fetchProductos() {
        const params = getParams();

        fetch(`${window.routeFiltrar}?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                renderProducts(data.productos);
                renderPagination(data.total_paginas);
            })
            .catch(err => {
                console.error('Error al cargar productos:', err);
            });
    }

    function applyFiltersFromUrl() {
        const params = new URLSearchParams(window.location.search);

        const categoryParam = params.get('categoria');
        if (categoryParam) {
            categoryChecks.forEach(check => {
                check.checked = check.value === categoryParam;
            });
        }

        const marcaParam = params.get('marca');
        if (marcaParam) {
            document.querySelectorAll('.filter-brand').forEach(check => {
                check.checked = check.value === marcaParam;
            });
        }

        const searchParam = params.get('search');
        if (searchInput && searchParam) {
            searchInput.value = searchParam;
        }
    }

    // Eventos
    searchInput?.addEventListener('input', () => { currentPage = 1; fetchProductos(); });
    sortMarketSelect?.addEventListener('change', () => { currentPage = 1; fetchProductos(); });
    sortNameSelect?.addEventListener('change', () => { currentPage = 1; fetchProductos(); });

    categoryChecks.forEach(check => {
        check.addEventListener('change', () => { currentPage = 1; fetchProductos(); });
    });

    energyRadios.forEach(radio => {
        radio.addEventListener('change', () => { currentPage = 1; fetchProductos(); });
    });

    rangeInput?.addEventListener('input', function () {
        if (rangeValue) rangeValue.textContent = formatPrice(this.value);
        currentPage = 1;
        fetchProductos();
    });

    resetBtn?.addEventListener('click', function () {
        if (searchInput) searchInput.value = '';
        if (sortMarketSelect) sortMarketSelect.value = 'default';
        if (sortNameSelect) sortNameSelect.value = 'default';
        if (rangeInput) rangeInput.value = 300000;
        if (rangeValue) rangeValue.textContent = formatPrice(300000);

        categoryChecks.forEach(check => check.checked = false);
        document.querySelectorAll('.filter-brand').forEach(check => check.checked = false);
        energyRadios.forEach(radio => radio.checked = radio.value === '');

        currentPage = 1;
        fetchProductos();
    });

    filterToggle?.addEventListener('click', () => sidebar?.classList.toggle('is-open'));
    filterClose?.addEventListener('click', () => sidebar?.classList.remove('is-open'));

    // Inicialización
    generarMarcas();
    applyFiltersFromUrl();
    fetchProductos();
});
