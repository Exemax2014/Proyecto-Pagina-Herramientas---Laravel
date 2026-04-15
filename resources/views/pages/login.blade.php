

@extends('layouts.app')

@section('contenido')
<section class="page-section">
    <div class="container">
        <div class="page-hero">
            <span class="home-kicker">&Aacute;rea de usuario</span>
            <h1>Login</h1>
            <p>
                Vista de acceso preparada como placeholder visual para sumar autenticaci&oacute;n m&aacute;s adelante
                sin tocar la base del frontend.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <article class="page-card">
                    <h2>Ingreso al sistema</h2>
                    <div class="form-placeholder">
                        <div class="placeholder-field">Correo electr&oacute;nico</div>
                        <div class="placeholder-field">Contrase&ntilde;a</div>
                        <a href="{{ route('registro') }}" class="btn btn-warning">Crear cuenta</a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
