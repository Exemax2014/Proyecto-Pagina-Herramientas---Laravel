@extends('layouts.app')

@section('title', 'Registro | Hierro & Forja')

@section('contenido')
    <section class="page-section">
        <div class="container">
            <!--  FORMULARIO DE REGISTRO -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-9 col-lg-7">
                    <article class="page-card register-card">
                        <h2>Crear una cuenta</h2>

                        <form id="registroForm" class="register-form">
                            <!-- NOMBRE Y APELLIDO -->
                            <div class="register-field">
                                <label for="nombre" class="register-label">Nombre y apellido</label>
                                <input
                                    type="text"
                                    id="nombre"
                                    name="nombre"
                                    class="form-control register-input"
                                    placeholder="Ingres&aacute; tu nombre y apellido"
                                    required
                                >
                            </div>

                            <!-- CORREO -->
                            <div class="register-field">
                                <label for="email" class="register-label">Correo electr&oacute;nico</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control register-input"
                                    placeholder="Ingres&aacute; tu correo"
                                    required
                                >
                            </div>

                            <!-- CONTRASEÑA -->
                            <div class="register-field">
                                <label for="password" class="register-label">Contrase&ntilde;a</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control register-input"
                                    placeholder="Ingres&aacute; tu contrase&ntilde;a"
                                    required
                                >
                            </div>

                            <!-- REPETIR CONTRASEÑA -->
                            <div class="register-field">
                                <label for="password_confirmation" class="register-label">Repetir contrase&ntilde;a</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control register-input"
                                    placeholder="Repet&iacute; tu contrase&ntilde;a"
                                    required
                                >
                            </div>

                            <!-- ACCIONES -->
                            <div class="register-actions">
                                <button type="submit" class="btn btn-warning register-btn">
                                    Registrarme
                                </button>

                                <p class="register-login-text">
                                    ¿Ya ten&eacute;s cuenta?
                                    <a href="{{ route('login') }}" class="register-login-link">
                                        Iniciar sesi&oacute;n
                                    </a>
                                </p>
                            </div>
                        </form>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================
        SCRIPT DE REGISTRO VISUAL
        No envía datos, solo simula el alta
        ========================================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registroForm');

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;

                if (password !== passwordConfirmation) {
                    window.showToast('Las contraseñas no coinciden.', 'top');
                    return;
                }

                window.showToast('Se registró correctamente', 'top');
                form.reset();
            });
        });
    </script>
@endsection