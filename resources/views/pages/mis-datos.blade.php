@extends('layouts.app')

@section('title', 'Mis Datos | Hierro & Forja')

@section('contenido')
<section class="page-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <article class="page-card">
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning mb-4">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong>Hay errores en el formulario:</strong>

                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <span class="home-kicker">Perfil de usuario</span>
                            <h2 class="mb-1">Mis datos</h2>
                            <p class="mb-0 text-muted">
                                Actualiza tus datos personales y los domicilios disponibles para tus futuras compras.
                            </p>
                        </div>

                        @if(session('usuario_role') === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
                                Volver al panel admin
                            </a>
                        @else
                            <a href="{{ route('catalogo') }}" class="btn btn-outline-dark">
                                Volver al catalogo
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('mis-datos.update') }}" method="POST" id="misDatosForm">
                        @csrf
                        @method('PATCH')

                        @if(request('redirect_to'))
                            <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                        @endif

                        <input type="hidden" name="domicilio_mode" id="domicilio_mode" value="{{ $domicilioMode }}">
                        <input type="hidden" name="selected_domicilio_id" id="selected_domicilio_id" value="{{ $selectedDomicilioId }}">

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="border rounded-3 p-3">
                                    <h3 class="h5 mb-3">Datos principales</h3>

                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input
                                                type="text"
                                                id="nombre"
                                                name="nombre"
                                                class="form-control"
                                                value="{{ old('nombre', $usuario->nombre) }}"
                                                required
                                                maxlength="100"
                                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-']+"
                                                autocomplete="given-name"
                                            >
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="apellido" class="form-label">Apellido</label>
                                            <input
                                                type="text"
                                                id="apellido"
                                                name="apellido"
                                                class="form-control"
                                                value="{{ old('apellido', $usuario->apellido) }}"
                                                required
                                                maxlength="100"
                                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-']+"
                                                autocomplete="family-name"
                                            >
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="telefono" class="form-label">Telefono</label>
                                            <input
                                                type="text"
                                                id="telefono"
                                                name="telefono"
                                                class="form-control"
                                                value="{{ old('telefono', $usuario->telefono) }}"
                                                required
                                                maxlength="30"
                                                inputmode="tel"
                                                pattern="[0-9+\s\-()\.]+"
                                                autocomplete="tel"
                                            >
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="dni" class="form-label">DNI</label>
                                            <input
                                                type="text"
                                                id="dni"
                                                name="dni"
                                                class="form-control"
                                                value="{{ old('dni', $usuario->dni) }}"
                                                required
                                                maxlength="10"
                                                inputmode="numeric"
                                                pattern="\d{6,10}"
                                            >
                                        </div>

                                        <div class="col-12">
                                            <label for="email" class="form-label">Email</label>
                                            <input
                                                type="email"
                                                id="email"
                                                name="email"
                                                class="form-control"
                                                value="{{ old('email', $usuario->email) }}"
                                                required
                                                maxlength="255"
                                                autocomplete="email"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border rounded-3 p-3">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                                        <div>
                                            <h3 class="h5 mb-1">Domicilios registrados</h3>
                                            <p class="mb-0 text-muted">
                                                {{ $isAdmin ? 'Actualiza tu domicilio activo para mantener completo el perfil administrador.' : 'Selecciona un domicilio para editarlo o agrega uno nuevo si todavia tienes espacio disponible.' }}
                                            </p>
                                        </div>

                                        @if(! $isAdmin && ! $canAddDomicilio)
                                            <span class="small text-muted">Maximo 4 domicilios registrados</span>
                                        @endif
                                    </div>

                                    <div class="profile-address-tabs mb-3" id="domicilioTabs">
                                        @foreach($domicilios as $index => $domicilio)
                                            <button
                                                type="button"
                                                class="btn {{ $domicilioMode === 'existing' && (int) $selectedDomicilioId === (int) $domicilio->id ? 'btn-warning' : 'btn-outline-dark' }} profile-address-tab"
                                                data-mode="existing"
                                                data-id="{{ $domicilio->id }}"
                                                data-calle="{{ e($domicilio->calle) }}"
                                                data-numero="{{ e($domicilio->numero) }}"
                                                data-piso="{{ e($domicilio->piso_departamento ?? '') }}"
                                                data-ciudad="{{ e($domicilio->ciudad) }}"
                                                data-provincia="{{ e($domicilio->provincia) }}"
                                                data-codigo-postal="{{ e($domicilio->codigo_postal ?? '') }}"
                                                data-referencia="{{ e($domicilio->referencia ?? '') }}"
                                            >
                                                Domicilio {{ $index + 1 }}
                                            </button>
                                        @endforeach

                                        @if($canAddDomicilio)
                                            <button
                                                type="button"
                                                class="btn {{ $domicilioMode === 'new' ? 'btn-warning' : 'btn-outline-dark' }} profile-address-tab"
                                                id="addAddressTab"
                                                data-mode="new"
                                                data-id=""
                                            >
                                                Agregar domicilio
                                            </button>
                                        @endif
                                    </div>

                                    @if($domicilios->isEmpty())
                                        <p class="text-muted small mb-3">Completa los datos para registrar tu primer domicilio.</p>
                                    @endif

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-4">
                                            <label for="calle" class="form-label">Calle</label>
                                            <input
                                                type="text"
                                                id="calle"
                                                name="calle"
                                                class="form-control"
                                                value="{{ old('calle', $domicilioForm['calle']) }}"
                                                maxlength="120"
                                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-']+"
                                                autocomplete="street-address"
                                            >
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <label for="numero" class="form-label">Numero</label>
                                            <input
                                                type="text"
                                                id="numero"
                                                name="numero"
                                                class="form-control"
                                                value="{{ old('numero', $domicilioForm['numero']) }}"
                                                maxlength="40"
                                                pattern="[A-Za-z0-9\s\/\-]+"
                                            >
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <label for="piso_departamento" class="form-label">Piso / Departamento</label>
                                            <input
                                                type="text"
                                                id="piso_departamento"
                                                name="piso_departamento"
                                                class="form-control"
                                                value="{{ old('piso_departamento', $domicilioForm['piso_departamento']) }}"
                                                maxlength="80"
                                            >
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <label for="ciudad" class="form-label">Ciudad</label>
                                            <input
                                                type="text"
                                                id="ciudad"
                                                name="ciudad"
                                                class="form-control"
                                                value="{{ old('ciudad', $domicilioForm['ciudad']) }}"
                                                maxlength="100"
                                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-']+"
                                                autocomplete="address-level2"
                                            >
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <label for="provincia" class="form-label">Provincia</label>
                                            <input
                                                type="text"
                                                id="provincia"
                                                name="provincia"
                                                class="form-control"
                                                value="{{ old('provincia', $domicilioForm['provincia']) }}"
                                                maxlength="100"
                                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-']+"
                                                autocomplete="address-level1"
                                            >
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <label for="codigo_postal" class="form-label">Codigo postal</label>
                                            <input
                                                type="text"
                                                id="codigo_postal"
                                                name="codigo_postal"
                                                class="form-control"
                                                value="{{ old('codigo_postal', $domicilioForm['codigo_postal']) }}"
                                                maxlength="20"
                                                pattern="[A-Za-z0-9\s\-]+"
                                                autocomplete="postal-code"
                                            >
                                        </div>

                                        <div class="col-12">
                                            <label for="referencia" class="form-label">Referencia</label>
                                            <input
                                                type="text"
                                                id="referencia"
                                                name="referencia"
                                                class="form-control"
                                                value="{{ old('referencia', $domicilioForm['referencia']) }}"
                                                maxlength="255"
                                            >
                                        </div>
                                    </div>

                                    <div
                                        id="deleteAddressFormWrap"
                                        class="mt-3 {{ $canDeleteDomicilio && $domicilioMode === 'existing' && $selectedDomicilioId ? '' : 'd-none' }}"
                                    >
                                        <button type="submit" form="deleteAddressForm" class="btn btn-outline-danger">
                                            Eliminar domicilio
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                @if(session('usuario_role') === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
                                        Cancelar
                                    </a>
                                @else
                                    <a href="{{ route('catalogo') }}" class="btn btn-outline-dark">
                                        Cancelar
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-warning">
                                    Guardar cambios
                                </button>
                            </div>
                        </div>
                    </form>

                    <form
                        action="{{ route('mis-datos.domicilios.baja', ['domicilio' => $selectedDomicilioId ?: 0]) }}"
                        method="POST"
                        id="deleteAddressForm"
                        class="d-none"
                        data-base-url="{{ url('/mis-datos/domicilios') }}"
                        onsubmit="return confirm('Seguro que queres dar de baja este domicilio?');"
                    >
                        @csrf
                        @method('PATCH')
                    </form>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = Array.from(document.querySelectorAll('.profile-address-tab'));
    const modeInput = document.getElementById('domicilio_mode');
    const selectedIdInput = document.getElementById('selected_domicilio_id');
    const deleteAddressForm = document.getElementById('deleteAddressForm');
    const deleteAddressFormWrap = document.getElementById('deleteAddressFormWrap');
    const fields = {
        calle: document.getElementById('calle'),
        numero: document.getElementById('numero'),
        piso: document.getElementById('piso_departamento'),
        ciudad: document.getElementById('ciudad'),
        provincia: document.getElementById('provincia'),
        codigoPostal: document.getElementById('codigo_postal'),
        referencia: document.getElementById('referencia'),
    };

    if (!tabs.length || !modeInput || !selectedIdInput) return;

    function setTabStyle(activeTab) {
        tabs.forEach(function (tab) {
            const isActive = tab === activeTab;
            tab.classList.toggle('btn-warning', isActive);
            tab.classList.toggle('btn-outline-dark', !isActive);
        });
    }

    function fillAddressForm(data) {
        fields.calle.value = data.calle || '';
        fields.numero.value = data.numero || '';
        fields.piso.value = data.piso || '';
        fields.ciudad.value = data.ciudad || '';
        fields.provincia.value = data.provincia || '';
        fields.codigoPostal.value = data.codigoPostal || '';
        fields.referencia.value = data.referencia || '';
    }

    function activateTab(tab) {
        const mode = tab.dataset.mode || 'existing';
        const selectedId = mode === 'existing' ? (tab.dataset.id || '') : '';

        modeInput.value = mode;
        selectedIdInput.value = selectedId;

        if (mode === 'new') {
            fillAddressForm({
                calle: '',
                numero: '',
                piso: '',
                ciudad: '',
                provincia: '',
                codigoPostal: '',
                referencia: '',
            });
        } else {
            fillAddressForm({
                calle: tab.dataset.calle || '',
                numero: tab.dataset.numero || '',
                piso: tab.dataset.piso || '',
                ciudad: tab.dataset.ciudad || '',
                provincia: tab.dataset.provincia || '',
                codigoPostal: tab.dataset.codigoPostal || '',
                referencia: tab.dataset.referencia || '',
            });
        }

        if (deleteAddressForm && deleteAddressFormWrap) {
            const shouldShowDelete = mode === 'existing' && selectedId;
            deleteAddressFormWrap.classList.toggle('d-none', !shouldShowDelete);

            if (shouldShowDelete) {
                deleteAddressForm.action = `${deleteAddressForm.dataset.baseUrl}/${selectedId}/baja`;
            }
        }

        setTabStyle(tab);
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activateTab(tab);
        });
    });
});
</script>
@endpush



