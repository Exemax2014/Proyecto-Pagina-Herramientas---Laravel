@extends('layouts.app')

@section('title', 'Ingreso | Hierro & Forja')

@section('contenido')
<section class="page-section">
    <div class="container">

        <!-- FORMULARIO -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <article class="page-card login-card">
                    <h2>Ingreso al sistema</h2>

                    <!-- 🔥 ERROR GENERAL -->
                    @if ($errors->any())
                        <div class="alert alert-danger login-alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.procesar') }}" method="POST" class="login-form">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ old('redirect', $redirect ?? null) }}">

                        <!-- EMAIL -->
                        <div class="login-field">
                            <label for="email" class="login-label">Correo electrónico</label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control login-input @error('email') is-invalid @enderror"
                                placeholder="Ingresá tu correo"
                                value="{{ old('email') }}"
                                required
                                maxlength="255"
                                autocomplete="email"
                            >

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div class="login-field">
                            <label for="password" class="login-label">Contraseña</label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control login-input @error('password') is-invalid @enderror"
                                placeholder="Ingresá tu contraseña"
                                required
                                autocomplete="current-password"
                            >

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ACCIONES -->
                        <div class="login-actions">
                            <button type="submit" class="btn btn-warning login-btn">
                                Entrar
                            </button>

                            <p class="login-register-text">
                                ¿No tenés cuenta?
                                <a href="{{ route('registro') }}" class="login-register-link">
                                    Crear cuenta
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
