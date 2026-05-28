document.addEventListener('DOMContentLoaded', function () {
    const offersContainer = document.getElementById('homeOffers');
    const offersPrev = document.getElementById('offersPrev');
    const offersNext = document.getElementById('offersNext');
    const offerCards = Array.from(document.querySelectorAll('.home-product-card'));

    let offerStartIndex = 0;

    function getVisibleOffers() {
        if (window.innerWidth < 768) return 2;
        if (window.innerWidth < 1200) return 3;
        return 4;
    }

    function renderHomeOffers() {
        if (!offersContainer) return;

        const visibleOffers = getVisibleOffers();
        const totalOffers = offerCards.length;

        if (offerStartIndex + visibleOffers > totalOffers) {
            offerStartIndex = Math.max(0, totalOffers - visibleOffers);
        }

        offerCards.forEach((card, index) => {
            const isVisible = index >= offerStartIndex && index < offerStartIndex + visibleOffers;
            card.classList.toggle('d-none', !isVisible);
        });

        updateOfferControls();
    }

    function updateOfferControls() {
        if (!offersPrev || !offersNext) return;

        const visibleOffers = getVisibleOffers();
        const totalOffers = offerCards.length;

        offersPrev.disabled = offerStartIndex === 0;
        offersNext.disabled = offerStartIndex + visibleOffers >= totalOffers;

        offersPrev.classList.toggle('is-disabled', offersPrev.disabled);
        offersNext.classList.toggle('is-disabled', offersNext.disabled);
    }

    function bindHomeOfferEvents() {
        offerCards.forEach(card => {
            card.addEventListener('click', function (event) {
                if (event.target.closest('.home-cart-btn')) return;

                const productLink = this.dataset.productLink;

                if (productLink) {
                    window.location.href = productLink;
                }
            });

            card.addEventListener('keydown', function (event) {
                if (event.key !== 'Enter' && event.key !== ' ') return;
                if (event.target.closest('.home-cart-btn')) return;

                event.preventDefault();

                const productLink = this.dataset.productLink;

                if (productLink) {
                    window.location.href = productLink;
                }
            });
        });

        document.querySelectorAll('.home-cart-btn').forEach(button => {
            button.addEventListener('click', async function (event) {
                event.stopPropagation();

                const productId = Number(this.dataset.productId);

                this.disabled = true;

                try {
                    const product = {
                        id: productId,
                        nombre: this.dataset.productNombre,
                        marca: this.dataset.productMarca,
                        categoria: this.dataset.productCategoria,
                        energia: this.dataset.productEnergia,
                        precio: Number(this.dataset.productPrecio),
                        imagen: this.dataset.productImagen,
                    };

                    const response = await window.CartUtils.addToCart(product, 1);

                    window.showToast(response.message || 'Producto agregado al carrito');
                } catch (error) {
                    window.showToast(error.message || 'No se pudo agregar el producto');
                } finally {
                    this.disabled = false;
                }
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
        const totalOffers = offerCards.length;

        if (offerStartIndex + visibleOffers >= totalOffers) return;

        offerStartIndex += 1;
        renderHomeOffers();
    });

    window.addEventListener('resize', function () {
        renderHomeOffers();
    });

    bindHomeOfferEvents();
    renderHomeOffers();
});
