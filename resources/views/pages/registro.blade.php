@extends('layouts.app')

@section('contenido')
<section class="page-section">
    <div class="container">
        <div class="page-hero">
            <span class="home-kicker">Alta de usuario</span>
            <h1>Registro</h1>
            <p>
                Pantalla base para un futuro formulario de alta, manteniendo el mismo lenguaje visual del resto del sitio.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <article class="page-card">
                    <h2>Crear una cuenta</h2>
                    <div class="form-placeholder">
                        <div class="placeholder-field">Nombre y apellido</div>
                        <div class="placeholder-field">Correo electr&oacute;nico</div>
                        <div class="placeholder-field">Contrase&ntilde;a</div>
                        <a href="{{ route('login') }}" class="btn btn-outline-dark">Ya tengo cuenta</a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection