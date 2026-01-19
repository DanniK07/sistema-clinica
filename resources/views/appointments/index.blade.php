@extends('layouts.app')

@section('title', 'Agenda de Citas')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="mb-2 fw-bold text-dark">
                    <i class="bi bi-calendar-check-fill text-primary me-2"></i>Agenda de Citas
                </h2>
                <p class="text-muted mb-0">Gestiona las citas médicas de la clínica</p>
            </div>
            <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle-fill me-2"></i>Nueva Cita
            </a>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('appointments.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="date" class="form-label fw-semibold text-muted small">Fecha</label>
                    <input type="date" 
                           name="date" 
                           id="date"
                           class="form-control form-control-lg" 
                           value="{{ request('date', now()->toDateString()) }}">
                </div>
                <div class="col-md-3">
                    <label for="doctor_id" class="form-label fw-semibold text-muted small">Doctor</label>
                    <select name="doctor_id" id="doctor_id" class="form-select form-select-lg">
                        <option value="">Todos los doctores</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label fw-semibold text-muted small">Estado</label>
                    <select name="status" id="status" class="form-select form-select-lg">
                        <option value="">Todos los estados</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                        <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>Atendida</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary w-100 btn-lg">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Appointments Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2 text-primary"></i>Lista de Citas
            </h5>
            <span class="badge bg-primary fs-6 px-3 py-2">{{ $appointments->total() }} {{ $appointments->total() == 1 ? 'cita' : 'citas' }}</span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($appointments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4 py-3 text-start fw-semibold" style="width: 12%;">Fecha</th>
                            <th class="py-3 text-start fw-semibold" style="width: 15%;">Hora</th>
                            <th class="py-3 text-start fw-semibold" style="width: 20%;">Paciente</th>
                            <th class="py-3 text-start fw-semibold" style="width: 20%;">Doctor</th>
                            <th class="py-3 text-start fw-semibold" style="width: 18%;">Tipo</th>
                            <th class="py-3 text-center fw-semibold" style="width: 10%;">Estado</th>
                            <th class="text-center pe-4 py-3 fw-semibold" style="width: 5%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr class="table-row-hover">
                                <td class="ps-4 py-3 text-start align-middle">
                                    <div>
                                        <strong>{{ $appointment->date->format('d/m/Y') }}</strong>
                                        <div class="mt-1">
                                            @if($appointment->date->isToday())
                                                <span class="badge bg-info">Hoy</span>
                                            @elseif($appointment->date->isTomorrow())
                                                <span class="badge bg-warning">Mañana</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-start align-middle">
                                    <i class="bi bi-clock me-1 text-muted"></i>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</span>
                                    <span class="text-muted">-</span>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</span>
                                </td>
                                <td class="py-3 text-start align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="bi bi-person-fill text-primary"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $appointment->patient->full_name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-start align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="bi bi-person-badge-fill text-success"></i>
                                        </div>
                                        <span>{{ $appointment->doctor->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-start align-middle">
                                    @if($appointment->type)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            <i class="bi bi-tag me-1"></i>{{ $appointment->type }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center align-middle">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['color' => 'warning', 'icon' => 'clock', 'label' => 'Pendiente'],
                                            'confirmed' => ['color' => 'info', 'icon' => 'check-circle', 'label' => 'Confirmada'],
                                            'cancelled' => ['color' => 'danger', 'icon' => 'x-circle', 'label' => 'Cancelada'],
                                            'attended' => ['color' => 'success', 'icon' => 'check2-circle', 'label' => 'Atendida']
                                        ];
                                        $status = $statusConfig[$appointment->status] ?? ['color' => 'secondary', 'icon' => 'circle', 'label' => $appointment->status];
                                    @endphp
                                    <span class="badge bg-{{ $status['color'] }}">
                                        <i class="bi bi-{{ $status['icon'] }} me-1"></i>{{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="text-center pe-4 py-3">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('appointments.show', $appointment) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Ver detalles"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('appointments.edit', $appointment) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Editar"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('appointments.destroy', $appointment) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta cita?');">
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
            @if($appointments->hasPages())
                <div class="card-footer bg-white border-top py-4 px-4">
                    <div class="d-flex justify-content-center">
                        {{ $appointments->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5 px-4">
                <i class="bi bi-calendar-x display-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No se encontraron citas</h5>
                <p class="text-muted mb-4">
                    @if(request('date') || request('doctor_id') || request('status'))
                        No hay resultados para los filtros seleccionados
                    @else
                        Aún no hay citas programadas en el sistema
                    @endif
                </p>
                @if(request('date') || request('doctor_id') || request('status'))
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Ver todas las citas
                    </a>
                @else
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear primera cita
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
