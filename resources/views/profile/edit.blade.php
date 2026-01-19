@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-person-circle-fill text-primary me-2"></i>Perfil de Usuario
                </h2>
                <p class="text-muted mb-0">Gestiona tu información personal</p>
            </div>
        </div>
    </div>
</div>

<!-- Mensaje de disponibilidad próxima -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center" role="alert">
            <div class="me-3">
                <i class="bi bi-info-circle-fill fs-2"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="alert-heading fw-bold mb-2">
                    <i class="bi bi-clock-history me-2"></i>Edición de Perfil - Próximamente
                </h5>
                <p class="mb-0">
                    La funcionalidad de edición de perfil estará disponible próximamente. 
                    Estamos trabajando para brindarte la mejor experiencia de usuario.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Card informativa adicional -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-tools text-primary fs-1"></i>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h5 class="fw-bold mb-2">
                            <i class="bi bi-gear-fill text-primary me-2"></i>Funcionalidad en Desarrollo
                        </h5>
                        <p class="text-muted mb-2">
                            Pronto podrás actualizar tu información personal, cambiar tu contraseña y gestionar 
                            todas las configuraciones de tu cuenta desde esta sección.
                        </p>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <small>Te notificaremos cuando esta funcionalidad esté disponible</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información actual del usuario (solo lectura) -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-person-badge-fill text-primary me-2"></i>Información Actual
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Nombre</label>
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-person-fill text-primary me-2"></i>
                            <span class="fw-medium">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Correo Electrónico</label>
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-envelope-fill text-primary me-2"></i>
                            <span class="fw-medium">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->clinic)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Clínica</label>
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-building-fill text-primary me-2"></i>
                            <span class="fw-medium">{{ Auth::user()->clinic->name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted">Rol</label>
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-shield-check-fill text-primary me-2"></i>
                            <span class="badge bg-primary">{{ ucfirst(Auth::user()->role ?? 'Usuario') }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
