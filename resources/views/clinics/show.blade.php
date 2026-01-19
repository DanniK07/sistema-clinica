@extends('layouts.app')

@section('title', 'Detalles de la Clínica')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-hospital-fill text-primary me-2"></i>Detalles de la Clínica
                </h2>
                <p class="text-muted mb-0">{{ $clinic->name }}</p>
            </div>
            <a href="{{ route('clinics.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información General</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nombre:</strong><br>
                                {{ $clinic->name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Email:</strong><br>
                                {{ $clinic->email }}
                            </div>
                        </div>

                        @if($clinic->phone)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Teléfono:</strong><br>
                                {{ $clinic->phone }}
                            </div>
                        </div>
                        @endif

                        @if($clinic->address)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Dirección:</strong><br>
                                {{ $clinic->address }}
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Estado:</strong><br>
                                @if($clinic->active)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-danger">Inactiva</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <strong>Fecha de Creación:</strong><br>
                                {{ $clinic->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Estadísticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h4 class="text-primary">{{ $clinic->users_count }}</h4>
                                    <p class="mb-0">Usuarios Totales</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h4 class="text-success">{{ $clinic->doctors_count }}</h4>
                                    <p class="mb-0">Doctores</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h4 class="text-info">{{ $clinic->patients_count }}</h4>
                                    <p class="mb-0">Pacientes</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h4 class="text-warning">{{ $clinic->appointments_count }}</h4>
                                    <p class="mb-0">Citas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-person-check"></i> Últimos Usuarios</h5>
                    </div>
                    <div class="card-body">
                        @if($clinic->users->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($clinic->users->take(5) as $user)
                                    <li class="list-group-item">
                                        <div>
                                            <strong>{{ $user->name }}</strong><br>
                                            <small class="text-muted">{{ $user->email }}</small><br>
                                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No hay usuarios registrados.</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Doctores Activos</h5>
                    </div>
                    <div class="card-body">
                        @if($clinic->doctors->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($clinic->doctors->take(5) as $doctor)
                                    <li class="list-group-item">
                                        <div>
                                            <strong>{{ $doctor->name }}</strong><br>
                                            @if($doctor->specialty)
                                                <small class="text-muted">{{ $doctor->specialty }}</small>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No hay doctores activos.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="alert alert-warning">
                <i class="bi bi-shield-check"></i> <strong>Seguridad Multi-tenant:</strong> 
                Esta vista demuestra el aislamiento de datos. Solo puedes ver la información de tu propia clínica (ID: {{ $clinic->id }}). 
                Si intentas acceder a otra clínica por URL, recibirás un error 403.
            </div>
        </div>
    </div>
</div>
@endsection
