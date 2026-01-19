@extends('layouts.app')

@section('title', 'Detalle del Paciente')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-person-circle-fill text-primary me-2"></i>Detalle del Paciente
                </h2>
                <p class="text-muted mb-0">{{ $patient->full_name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-pencil-fill me-2"></i>Editar
                </a>
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Tipo de Documento:</strong><br>
                                <span class="badge bg-secondary">{{ strtoupper($patient->document_type) }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Número de Documento:</strong><br>
                                {{ $patient->document_number }}
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nombre:</strong><br>
                                {{ $patient->first_name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Apellido:</strong><br>
                                {{ $patient->last_name }}
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Email:</strong><br>
                                {{ $patient->email ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Teléfono:</strong><br>
                                {{ $patient->phone ?? '-' }}
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Fecha de Nacimiento:</strong><br>
                                {{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '-' }}
                                @if($patient->birth_date)
                                    <small class="text-muted">
                                        ({{ $patient->birth_date->age }} años)
                                    </small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <strong>Género:</strong><br>
                                @if($patient->gender)
                                    {{ $patient->gender == 'male' ? 'Masculino' : ($patient->gender == 'female' ? 'Femenino' : 'Otro') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        @if($patient->address)
                            <hr>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Dirección:</strong><br>
                                    {{ $patient->address }}
                                </div>
                            </div>
                        @endif

                        @if($patient->notes)
                            <hr>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Notas:</strong><br>
                                    {{ $patient->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Citas</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Total de citas:</strong> {{ $patient->appointments->count() }}
                        </p>
                        <p class="mb-0">
                            <strong>Citas pendientes:</strong> 
                            {{ $patient->appointments->where('status', 'pending')->count() + 
                               $patient->appointments->where('status', 'confirmed')->count() }}
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Información del Registro</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Creado:</strong><br>
                            <small>{{ $patient->created_at->format('d/m/Y H:i') }}</small>
                        </p>
                        <p class="mb-0">
                            <strong>Última actualización:</strong><br>
                            <small>{{ $patient->updated_at->format('d/m/Y H:i') }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($patient->appointments->count() > 0)
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Historial de Citas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Doctor</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patient->appointments->sortByDesc('date') as $appointment)
                                    <tr>
                                        <td>{{ $appointment->date->format('d/m/Y') }}</td>
                                        <td>{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                                        <td>{{ $appointment->doctor->name ?? '-' }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'info',
                                                    'cancelled' => 'danger',
                                                    'attended' => 'success'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Pendiente',
                                                    'confirmed' => 'Confirmada',
                                                    'cancelled' => 'Cancelada',
                                                    'attended' => 'Atendida'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$appointment->status] ?? $appointment->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
