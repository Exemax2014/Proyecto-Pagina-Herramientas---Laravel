document.addEventListener('DOMContentLoaded', function () {
    const forms = [
        document.getElementById('navbarSearchFormDesktop'),
        document.getElementById('navbarSearchFormMobile'),
    ];

    forms.forEach(form => {
        if (!form) return;

        form.addEventListener('submit', function (event) {
            const input = form.querySelector('input[name="search"]');
            const value = input ? input.value.trim() : '';

            if (!value) {
                event.preventDefault();
                return;
            }

            if (input) {
                input.value = value;
            }
        });
    });
});
