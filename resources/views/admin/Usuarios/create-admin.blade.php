@extends('layouts.admin')

@section('title', 'Crear administrador | Panel Admin')
@section('page-title', 'Crear administrador')

@section('contenido')

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Hay errores en el formulario:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.usuarios.store-admin') }}" method="POST" id="createAdminForm">
        @csrf

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="admin-card mb-4">
                    <h2>Datos del administrador</h2>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Nombre</label>
                            <input
                                type="text"
                                name="nombre"
                                id="nombre"
                                class="form-control"
                                value="{{ old('nombre') }}"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Apellido</label>
                            <input
                                type="text"
                                name="apellido"
                                id="apellido"
                                class="form-control"
                                value="{{ old('apellido') }}"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Contraseña</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">DNI</label>
                            <input
                                type="text"
                                name="dni"
                                id="dni"
                                class="form-control"
                                value="{{ old('dni') }}"
                                maxlength="8"
                                inputmode="numeric"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Teléfono</label>
                            <input
                                type="text"
                                name="telefono"
                                id="telefono"
                                class="form-control"
                                value="{{ old('telefono') }}"
                                inputmode="numeric"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Calle</label>
                            <input
                                type="text"
                                name="calle"
                                id="calle"
                                class="form-control"
                                value="{{ old('calle') }}"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Número</label>
                            <input
                                type="text"
                                name="numero"
                                id="numero"
                                class="form-control"
                                value="{{ old('numero') }}"
                                inputmode="numeric"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Piso / Departamento</label>
                            <input
                                type="text"
                                name="piso_departamento"
                                id="piso_departamento"
                                class="form-control"
                                value="{{ old('piso_departamento') }}"
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Ciudad</label>
                            <input
                                type="text"
                                name="ciudad"
                                id="ciudad"
                                class="form-control"
                                value="{{ old('ciudad') }}"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Provincia</label>
                            <input
                                type="text"
                                name="provincia"
                                id="provincia"
                                class="form-control"
                                value="{{ old('provincia') }}"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Código postal</label>
                            <input
                                type="text"
                                name="codigo_postal"
                                id="codigo_postal"
                                class="form-control"
                                value="{{ old('codigo_postal') }}"
                                inputmode="numeric"
                                required
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12">
                            <label class="admin-form-label">Referencia</label>
                            <input
                                type="text"
                                name="referencia"
                                id="referencia"
                                class="form-control"
                                value="{{ old('referencia') }}"
                            >
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="admin-card">
                    <h2>Acciones</h2>

                    <button type="submit" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-person-plus"></i>
                        Crear administrador
                    </button>

                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-dark w-100">
                        Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('createAdminForm');

    // Bloquear letras en campos numéricos en tiempo real
    ['dni', 'telefono', 'numero', 'codigo_postal'].forEach(function (id) {
        const input = document.getElementById(id);
        if (!input) return;
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    // Bloquear números en campos de texto
    ['nombre', 'apellido', 'ciudad', 'provincia'].forEach(function (id) {
        const input = document.getElementById(id);
        if (!input) return;
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-]/g, '');
        });
    });

    function mostrarError(input, mensaje) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = mensaje;
        }
    }

    function limpiarError(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }

    function limpiarTodos() {
        form.querySelectorAll('.form-control').forEach(function (input) {
            input.classList.remove('is-invalid', 'is-valid');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = '';
            }
        });
    }

    function validar() {
        let valido = true;
        limpiarTodos();

        // Nombre
        const nombre = document.getElementById('nombre');
        if (!nombre.value.trim()) {
            mostrarError(nombre, 'El nombre es obligatorio.');
            valido = false;
        } else if (nombre.value.trim().length < 2) {
            mostrarError(nombre, 'El nombre debe tener al menos 2 caracteres.');
            valido = false;
        } else {
            limpiarError(nombre);
        }

        // Apellido
        const apellido = document.getElementById('apellido');
        if (!apellido.value.trim()) {
            mostrarError(apellido, 'El apellido es obligatorio.');
            valido = false;
        } else if (apellido.value.trim().length < 2) {
            mostrarError(apellido, 'El apellido debe tener al menos 2 caracteres.');
            valido = false;
        } else {
            limpiarError(apellido);
        }

        // Email
        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim()) {
            mostrarError(email, 'El email es obligatorio.');
            valido = false;
        } else if (!emailRegex.test(email.value.trim())) {
            mostrarError(email, 'Ingresá un email válido.');
            valido = false;
        } else {
            limpiarError(email);
        }

        // Contraseña
        const password = document.getElementById('password');
        if (!password.value) {
            mostrarError(password, 'La contraseña es obligatoria.');
            valido = false;
        } else if (password.value.length < 8) {
            mostrarError(password, 'La contraseña debe tener al menos 8 caracteres.');
            valido = false;
        } else {
            limpiarError(password);
        }

        // DNI
        const dni = document.getElementById('dni');
        if (!dni.value.trim()) {
            mostrarError(dni, 'El DNI es obligatorio.');
            valido = false;
        } else if (!/^[0-9]{7,8}$/.test(dni.value.trim())) {
            mostrarError(dni, 'El DNI debe tener 7 u 8 números sin puntos.');
            valido = false;
        } else {
            limpiarError(dni);
        }

        // Teléfono
        const telefono = document.getElementById('telefono');
        if (!telefono.value.trim()) {
            mostrarError(telefono, 'El teléfono es obligatorio.');
            valido = false;
        } else if (!/^[0-9]{7,20}$/.test(telefono.value.trim())) {
            mostrarError(telefono, 'El teléfono debe tener entre 7 y 20 números.');
            valido = false;
        } else {
            limpiarError(telefono);
        }

        // Calle
        const calle = document.getElementById('calle');
        if (!calle.value.trim()) {
            mostrarError(calle, 'La calle es obligatoria.');
            valido = false;
        } else if (calle.value.trim().length < 2) {
            mostrarError(calle, 'La calle debe tener al menos 2 caracteres.');
            valido = false;
        } else {
            limpiarError(calle);
        }

        // Número
        const numero = document.getElementById('numero');
        if (!numero.value.trim()) {
            mostrarError(numero, 'El número es obligatorio.');
            valido = false;
        } else if (!/^[0-9]+$/.test(numero.value.trim())) {
            mostrarError(numero, 'El número solo puede contener dígitos.');
            valido = false;
        } else {
            limpiarError(numero);
        }

        // Ciudad
        const ciudad = document.getElementById('ciudad');
        if (!ciudad.value.trim()) {
            mostrarError(ciudad, 'La ciudad es obligatoria.');
            valido = false;
        } else if (ciudad.value.trim().length < 2) {
            mostrarError(ciudad, 'La ciudad debe tener al menos 2 caracteres.');
            valido = false;
        } else {
            limpiarError(ciudad);
        }

        // Provincia
        const provincia = document.getElementById('provincia');
        if (!provincia.value.trim()) {
            mostrarError(provincia, 'La provincia es obligatoria.');
            valido = false;
        } else if (provincia.value.trim().length < 2) {
            mostrarError(provincia, 'La provincia debe tener al menos 2 caracteres.');
            valido = false;
        } else {
            limpiarError(provincia);
        }

        // Código postal
        const codigoPostal = document.getElementById('codigo_postal');
        if (!codigoPostal.value.trim()) {
            mostrarError(codigoPostal, 'El código postal es obligatorio.');
            valido = false;
        } else if (!/^[0-9]{4,8}$/.test(codigoPostal.value.trim())) {
            mostrarError(codigoPostal, 'El código postal debe tener entre 4 y 8 números.');
            valido = false;
        } else {
            limpiarError(codigoPostal);
        }

        return valido;
    }

    form.addEventListener('submit', function (e) {
        if (!validar()) {
            e.preventDefault();
            const primerError = form.querySelector('.is-invalid');
            if (primerError) {
                primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>
@endpush