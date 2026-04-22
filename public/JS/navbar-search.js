document.addEventListener('DOMContentLoaded', function () {
    const productos = Array.isArray(window.catalogoProductos) ? window.catalogoProductos : [];
    const routeCatalogo = window.routeCatalogoBase || '/catalogo';
    const routeProducto = window.routeProductoBase || '/producto';

    const instances = [
        {
            form: document.getElementById('navbarSearchFormDesktop'),
            input: document.getElementById('navbarSearchInputDesktop'),
            results: document.getElementById('navbarSearchResultsDesktop')
        },
        {
            form: document.getElementById('navbarSearchFormMobile'),
            input: document.getElementById('navbarSearchInputMobile'),
            results: document.getElementById('navbarSearchResultsMobile')
        }
    ];

    function normalize(text) {
        return String(text || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function buscar(valor) {
        const query = normalize(valor);

        if (!query) return [];

        return productos.filter(producto => {
            return normalize(producto.nombre).includes(query) ||
                   normalize(producto.marca).includes(query) ||
                   normalize(producto.categoria).includes(query);
        }).slice(0, 6);
    }

    function renderResults(lista, results) {
        if (!results) return;

        if (!lista.length) {
            results.innerHTML = `<div class="navbar-search-empty">Sin resultados</div>`;
            results.classList.add('is-open');
            return;
        }

        results.innerHTML = lista.map(producto => `
            <button type="button" class="navbar-search-result-item" data-id="${producto.id}">
                <span class="navbar-search-result-name">${producto.nombre}</span>
            </button>
        `).join('');

        results.classList.add('is-open');

        results.querySelectorAll('.navbar-search-result-item').forEach(item => {
            item.addEventListener('click', function () {
                window.location.href = `${routeProducto}/${this.dataset.id}`;
            });
        });
    }

    function closeResults(results) {
        if (!results) return;
        results.classList.remove('is-open');
    }

    instances.forEach(instance => {
        const { form, input, results } = instance;

        if (!form || !input || !results) return;

        input.addEventListener('input', function () {
            const valor = this.value.trim();

            if (!valor) {
                results.innerHTML = '';
                closeResults(results);
                return;
            }

            renderResults(buscar(valor), results);
        });

        input.addEventListener('focus', function () {
            const valor = this.value.trim();
            if (!valor) return;

            renderResults(buscar(valor), results);
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const valor = input.value.trim();
            if (!valor) return;

            window.location.href = `${routeCatalogo}?search=${encodeURIComponent(valor)}`;
        });

        document.addEventListener('click', function (e) {
            if (!form.contains(e.target)) {
                closeResults(results);
            }
        });
    });
});