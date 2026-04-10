@extends('layouts.app')

@extends('layouts.app')

@section('contenido')

<!-- HERO -->
<div class="bg-dark text-white p-5 rounded mb-5">
    <h1 class="display-4 fw-bold">HIERRO & FORJA</h1>
    <p class="lead">Herramientas profesionales para construcción y herrería.</p>
    <a href="#" class="btn btn-warning">Ver catálogo</a>
</div>

<!-- SECCIONES -->
<div class="row">

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Construcción</h5>
                <p>Materiales y herramientas pesadas.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Herrería</h5>
                <p>Equipamiento profesional para forja.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Carpintería</h5>
                <p>Herramientas de precisión.</p>
            </div>
        </div>
    </div>

</div>

@endsection