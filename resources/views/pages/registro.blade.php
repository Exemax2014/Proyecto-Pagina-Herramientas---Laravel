@extends('layouts.app')

@section('title', 'Registro | Hierro & Forja')

@section('contenido')
<section class="page-section">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7">

                <article class="page-card register-card">
                    <h2>Crear una cuenta</h2>

                    <form action="{{ route('registro.procesar') }}" method="POST" class="register-form">
                        @csrf

                        <!-- NOMBRE -->
                        <div class="register-field">
                            <label for="nombre" class="register-label">Nombre</label>
                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                class="form-control register-input"
                                placeholder="Ingresá tu nombre"
                                value="{{ old('nombre') }}"
                                required
                                maxlength="100"
                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-']+"
                                autocomplete="name"
                            >
                            @error('nombre')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO -->
                        <div class="register-field">
                            <label for="apellido" class="register-label">Apellido</label>
                            <input
                                type="text"
                                id="apellido"
                                name="apellido"
                                class="form-control register-input"
                                placeholder="Ingresá tu apellido"
                                value="{{ old('apellido') }}"
                                required
                                maxlength="100"
                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-']+"
                                autocomplete="family-name"
                            >
                            @error('apellido')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- EMAIL -->
                        <div class="register-field">
                            <label for="email" class="register-label">Correo electrónico</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control register-input"
                                placeholder="Ingresá tu correo"
                                value="{{ old('email') }}"
                                required
                                maxlength="255"
                                autocomplete="email"
                            >
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div class="register-field">
                            <label for="password" class="register-label">Contraseña</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control register-input"
                                placeholder="Ingresá tu contraseña"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONFIRMAR PASSWORD -->
                        <div class="register-field">
                            <label for="password_confirmation" class="register-label">
                                Repetir contraseña
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control register-input"
                                placeholder="Repetí tu contraseña"
                                required
                                autocomplete="new-password"
                            >
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- BOTÓN -->
                        <div class="register-actions">
                            <button type="submit" class="btn btn-warning register-btn">
                                Registrarme
                            </button>

                            <p class="register-login-text">
                                ¿Ya tenés cuenta?
                                <a href="{{ route('login') }}" class="register-login-link">
                                    Iniciar sesión
                                </a>
                            </p>
                        </div>

                    </form>
                </article>

            </div>
        </div>

    </div>
</section>
@endsection