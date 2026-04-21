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

                    @if ($errors->any())
                        <div class="alert alert-danger login-alert">
                            Revis&aacute; los campos del formulario.
                        </div>
                    @endif

                    <form action="{{ route('login.procesar') }}" method="POST" class="login-form">
                        @csrf

                        <div class="login-field">
                            <label for="email" class="login-label">Correo electr&oacute;nico</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control login-input @error('email') is-invalid @enderror"
                                placeholder="Ingres&aacute; tu correo"
                                value="{{ old('email') }}"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="login-field">
                            <label for="password" class="login-label">Contrase&ntilde;a</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control login-input @error('password') is-invalid @enderror"
                                placeholder="Ingres&aacute; tu contrase&ntilde;a"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="login-actions">
                            <!-- BOTÓN ENTRAR -->
                            <button type="submit" class="btn btn-warning login-btn">
                                Entrar
                            </button>

                            <!-- TEXTO + LINK A REGISTRO -->
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