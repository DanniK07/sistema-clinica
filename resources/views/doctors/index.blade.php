@extends('layouts.app')

@section('title', 'Doctores')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="mb-2 fw-bold text-dark">
                    <i class="bi bi-person-badge-fill text-primary me-2"></i>Gestión de Doctores
                </h2>
                <p class="text-muted mb-0">Administra el personal médico de la clínica</p>
            </div>
            <a href="{{ route('doctors.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Doctor
            </a>
        </div>
    </div>
</div>

<!-- Search Card -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('doctors.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-7">
                    <label for="search" class="form-label fw-semibold text-muted small">Buscar Doctor</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                               id="search"
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por nombre, email, especialidad o teléfono..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="active" class="form-label fw-semibold text-muted small">Estado</label>
                    <select name="active" id="active" class="form-select form-select-lg">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
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

<!-- Doctors Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2 text-primary"></i>Lista de Doctores
            </h5>
            <span class="badge bg-primary fs-6 px-3 py-2">{{ $doctors->total() }} {{ $doctors->total() == 1 ? 'doctor' : 'doctores' }}</span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($doctors->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4 py-3 text-start fw-semibold" style="width: 25%;">Nombre</th>
                            <th class="py-3 text-start fw-semibold" style="width: 20%;">Email</th>
                            <th class="py-3 text-start fw-semibold" style="width: 20%;">Especialidad</th>
                            <th class="py-3 text-start fw-semibold" style="width: 15%;">Teléfono</th>
                            <th class="py-3 text-center fw-semibold" style="width: 10%;">Estado</th>
                            <th class="text-center pe-4 py-3 fw-semibold" style="width: 10%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                            <tr class="table-row-hover">
                                <td class="ps-4 py-3 text-start align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bi bi-person-badge-fill text-success"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $doctor->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-start align-middle">
                                    <i class="bi bi-envelope me-1 text-muted"></i>{{ $doctor->email }}
                                </td>
                                <td class="py-3 text-start align-middle">
                                    @if($doctor->specialty)
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="bi bi-briefcase me-1"></i>{{ $doctor->specialty }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-start align-middle">
                                    @if($doctor->phone)
                                        <i class="bi bi-telephone me-1 text-muted"></i>{{ $doctor->phone }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center align-middle">
                                    @if($doctor->active)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x-circle me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4 py-3">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('doctors.show', $doctor) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Ver detalles"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('doctors.edit', $doctor) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Editar"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('doctors.destroy', $doctor) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este doctor?');">
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
            @if($doctors->hasPages())
                <div class="card-footer bg-white border-top py-4 px-4">
                    <div class="d-flex justify-content-center">
                        {{ $doctors->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5 px-4">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No se encontraron doctores</h5>
                <p class="text-muted mb-4">
                    @if(request('search') || request('active'))
                        No hay resultados para los filtros seleccionados
                    @else
                        Aún no hay doctores registrados en el sistema
                    @endif
                </p>
                @if(request('search') || request('active'))
                    <a href="{{ route('doctors.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Ver todos los doctores
                    </a>
                @else
                    <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear primer doctor
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
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
@endsection
