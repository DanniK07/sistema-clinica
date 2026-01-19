@extends('layouts.app')

@section('title', 'Mi Clínica')

@section('content')
<div class="row mb-5">
    <div class="col-12">
        <div>
            <h2 class="mb-2 fw-bold text-dark">
                <i class="bi bi-building-fill text-primary me-2"></i>Información de la Clínica
            </h2>
            <p class="text-muted mb-0">Datos generales y estadísticas de tu clínica</p>
        </div>
    </div>
</div>

<!-- Clinic Info Card -->
<div class="card mb-5 border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-info-circle-fill text-primary me-2"></i>Datos de la Clínica
        </h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-8">
                <h3 class="fw-bold text-dark mb-4">{{ $clinic->name }}</h3>
                <div class="mb-3">
                    <i class="bi bi-envelope-fill text-primary me-2"></i>
                    <strong>Email:</strong> {{ $clinic->email }}
                </div>
                @if($clinic->phone)
                <div class="mb-3">
                    <i class="bi bi-telephone-fill text-primary me-2"></i>
                    <strong>Teléfono:</strong> {{ $clinic->phone }}
                </div>
                @endif
                @if($clinic->address)
                <div class="mb-0">
                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                    <strong>Dirección:</strong> {{ $clinic->address }}
                </div>
                @endif
            </div>
            <div class="col-md-4 text-md-end text-center">
                <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-building display-4 text-primary"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100 stats-card stats-card-primary">
            <div class="card-body text-center p-4">
                <div class="mb-4">
                    <div class="stats-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-people-fill text-primary"></i>
                    </div>
                </div>
                <div class="display-3 fw-bold text-primary mb-2">{{ $clinic->users_count }}</div>
                <p class="text-muted mb-0 fw-semibold text-uppercase small">Usuarios</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100 stats-card stats-card-success">
            <div class="card-body text-center p-4">
                <div class="mb-4">
                    <div class="stats-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-person-badge-fill text-success"></i>
                    </div>
                </div>
                <div class="display-3 fw-bold text-success mb-2">{{ $clinic->doctors_count }}</div>
                <p class="text-muted mb-0 fw-semibold text-uppercase small">Doctores</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100 stats-card stats-card-info">
            <div class="card-body text-center p-4">
                <div class="mb-4">
                    <div class="stats-icon bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-person-fill text-info"></i>
                    </div>
                </div>
                <div class="display-3 fw-bold text-info mb-2">{{ $clinic->patients_count }}</div>
                <p class="text-muted mb-0 fw-semibold text-uppercase small">Pacientes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100 stats-card stats-card-warning">
            <div class="card-body text-center p-4">
                <div class="mb-4">
                    <div class="stats-icon bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar-check-fill text-warning"></i>
                    </div>
                </div>
                <div class="display-3 fw-bold text-warning mb-2">{{ $clinic->appointments_count }}</div>
                <p class="text-muted mb-0 fw-semibold text-uppercase small">Citas</p>
            </div>
        </div>
    </div>
</div>

<!-- Security Notice -->
<div class="alert alert-info border-0 shadow-sm mb-5">
    <div class="d-flex align-items-start">
        <i class="bi bi-shield-check-fill fs-4 me-3 mt-1"></i>
        <div>
            <h6 class="alert-heading fw-bold mb-2">Nota de Seguridad</h6>
            <p class="mb-0">Solo puedes ver la información de tu propia clínica. Los usuarios de otras clínicas no pueden acceder a estos datos gracias al sistema de aislamiento multi-tenant.</p>
        </div>
    </div>
</div>

<!-- Action Button -->
<div class="text-center mb-4">
    <a href="{{ route('clinics.show', $clinic) }}" class="btn btn-primary btn-lg px-5">
        <i class="bi bi-eye-fill me-2"></i>Ver Detalles Completos
    </a>
</div>

<style>
.stats-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
}

.stats-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.stats-card-primary:hover {
    border-color: rgba(13, 110, 253, 0.3) !important;
}

.stats-card-success:hover {
    border-color: rgba(25, 135, 84, 0.3) !important;
}

.stats-card-info:hover {
    border-color: rgba(13, 202, 240, 0.3) !important;
}

.stats-card-warning:hover {
    border-color: rgba(255, 193, 7, 0.3) !important;
}

.stats-icon {
    width: 5rem;
    height: 5rem;
    transition: all 0.3s ease;
}

.stats-card:hover .stats-icon {
    transform: scale(1.1);
}

.display-3 {
    font-size: 3.5rem;
    line-height: 1.2;
    letter-spacing: -0.02em;
}

@media (max-width: 768px) {
    .display-3 {
        font-size: 2.5rem;
    }
    
    .stats-icon {
        width: 4rem;
        height: 4rem;
    }
}
</style>
@endsection
