@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')
<!-- Dashboard Header -->
<div class="row g-0 mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-3">
                    <div class="col-md-8">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="bi bi-speedometer2 me-3"></i>Panel General
                        </h1>
                        <p class="lead mb-0 opacity-75">
                            <i class="bi bi-calendar3 me-2"></i>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-building me-2"></i>{{ Auth::user()->clinic->name ?? 'Clínica' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end text-center">
                        <div class="d-inline-block bg-white bg-opacity-20 rounded p-3">
                            <i class="bi bi-clock-history display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="mb-2 fw-bold text-dark">
                    <i class="bi bi-people-fill text-primary me-2"></i>Gestión de Pacientes
                </h2>
                <p class="text-muted mb-0">Administra el registro de pacientes de la clínica</p>
            </div>
            <a href="{{ route('patients.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Paciente
            </a>
        </div>
    </div>
</div>

<!-- Search Card -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('patients.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label for="search" class="form-label fw-semibold text-muted small">Buscar Paciente</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                               id="search"
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por nombre, documento, email o teléfono..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100 btn-lg">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Patients Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2 text-primary"></i>Lista de Pacientes
            </h5>
            <span class="badge bg-primary fs-6 px-3 py-2">{{ $patients->total() }} {{ $patients->total() == 1 ? 'paciente' : 'pacientes' }}</span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($patients->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4 py-3 text-start fw-semibold" style="width: 15%;">Documento</th>
                            <th class="py-3 text-start fw-semibold" style="width: 25%;">Nombre Completo</th>
                            <th class="py-3 text-start fw-semibold" style="width: 20%;">Email</th>
                            <th class="py-3 text-start fw-semibold" style="width: 15%;">Teléfono</th>
                            <th class="py-3 text-start fw-semibold" style="width: 15%;">Fecha Nac.</th>
                            <th class="text-center pe-4 py-3 fw-semibold" style="width: 10%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr class="table-row-hover">
                                <td class="ps-4 py-3 text-start align-middle">
                                    <span class="badge bg-secondary me-2">{{ strtoupper($patient->document_type) }}</span>
                                    <span class="fw-medium">{{ $patient->document_number }}</span>
                                </td>
                                <td class="py-3 text-start align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bi bi-person-fill text-primary"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $patient->full_name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-start align-middle">
                                    @if($patient->email)
                                        <i class="bi bi-envelope me-1 text-muted"></i>{{ $patient->email }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-start align-middle">
                                    @if($patient->phone)
                                        <i class="bi bi-telephone me-1 text-muted"></i>{{ $patient->phone }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-start align-middle">
                                    @if($patient->birth_date)
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>{{ $patient->birth_date->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4 py-3">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('patients.show', $patient) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Ver detalles"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Editar"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('patients.destroy', $patient) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este paciente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Eliminar"
                                                    data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($patients->hasPages())
                <div class="card-footer bg-white border-top py-4 px-4">
                    <div class="d-flex justify-content-center">
                        {{ $patients->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5 px-4">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No se encontraron pacientes</h5>
                <p class="text-muted mb-4">
                    @if(request('search'))
                        No hay resultados para "{{ request('search') }}"
                    @else
                        Aún no hay pacientes registrados en el sistema
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('patients.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Ver todos los pacientes
                    </a>
                @else
                    <a href="{{ route('patients.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear primer paciente
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}
.table-row-hover {
    transition: background-color 0.15s ease-in-out;
}
.table-row-hover:hover {
    background-color: #e7f1ff !important;
    cursor: pointer;
}
.table-dark {
    background-color: #212529;
}
.table-striped > tbody > tr:nth-of-type(odd) > td {
    background-color: rgba(0, 0, 0, 0.02);
}
</style>

<script>
// Initialize Bootstrap tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
@endsection
