@extends('layouts.app')

@section('contenido')
<section class="page-section">
    <div class="container">
        <div class="page-hero">
            <span class="home-kicker">L&iacute;neas destacadas</span>
            <h1>Cat&aacute;logo</h1>
            <p>
                Esta secci&oacute;n funciona como base visual para exponer productos o categor&iacute;as sin necesidad de
                sumar todav&iacute;a una l&oacute;gica de cat&aacute;logo completa.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <article class="page-card h-100">
                    <i class="bi bi-lightning-charge page-icon"></i>
                    <h2>El&eacute;ctricas</h2>
                    <p>Herramientas para corte, perforaci&oacute;n y trabajo continuo en obra o taller.</p>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="page-card h-100">
                    <i class="bi bi-wrench-adjustable page-icon"></i>
                    <h2>Manuales</h2>
                    <p>Equipos y accesorios de uso cotidiano con foco en resistencia y control.</p>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="page-card h-100">
                    <i class="bi bi-box-seam page-icon"></i>
                    <h2>Accesorios</h2>
                    <p>Complementos para ampliar rendimiento y mantener ordenado cada trabajo.</p>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="page-card h-100">
                    <i class="bi bi-shield-check page-icon"></i>
                    <h2>Protecci&oacute;n</h2>
                    <p>Elementos pensados para reforzar seguridad y confianza en tareas exigentes.</p>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
