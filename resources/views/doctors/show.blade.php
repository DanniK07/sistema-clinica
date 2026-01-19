@extends('layouts.app')

@section('title', 'Detalle del Doctor')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-person-circle-fill text-primary me-2"></i>Detalle del Doctor
                </h2>
                <p class="text-muted mb-0">{{ $doctor->name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-pencil-fill me-2"></i>Editar
                </a>
                <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary btn-lg">
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
                                <strong>Nombre:</strong><br>
                                {{ $doctor->name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Email:</strong><br>
                                {{ $doctor->email }}
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Teléfono:</strong><br>
                                {{ $doctor->phone ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Especialidad:</strong><br>
                                {{ $doctor->specialty ?? '-' }}
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Estado:</strong><br>
                                @if($doctor->active)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($doctor->schedule && count($doctor->schedule) > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-clock"></i> Horarios de Atención</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Día</th>
                                            <th>Hora Inicio</th>
                                            <th>Hora Fin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $days = [
                                                'monday' => 'Lunes',
                                                'tuesday' => 'Martes',
                                                'wednesday' => 'Miércoles',
                                                'thursday' => 'Jueves',
                                                'friday' => 'Viernes',
                                                'saturday' => 'Sábado',
                                                'sunday' => 'Domingo'
                                            ];
                                        @endphp
                                        @foreach($doctor->schedule as $schedule)
                                            <tr>
                                                <td>{{ $days[$schedule['day']] ?? $schedule['day'] }}</td>
                                                <td>{{ $schedule['start_time'] }}</td>
                                                <td>{{ $schedule['end_time'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle"></i> No hay horarios configurados para este doctor.
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Citas</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Total de citas:</strong> {{ $doctor->appointments->count() }}
                        </p>
                        <p class="mb-0">
                            <strong>Citas pendientes:</strong> 
                            {{ $doctor->appointments->whereIn('status', ['pending', 'confirmed'])->count() }}
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
                            <small>{{ $doctor->created_at->format('d/m/Y H:i') }}</small>
                        </p>
                        <p class="mb-0">
                            <strong>Última actualización:</strong><br>
                            <small>{{ $doctor->updated_at->format('d/m/Y H:i') }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($doctor->appointments->count() > 0)
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Próximas Citas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Paciente</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doctor->appointments->where('date', '>=', now()->toDateString())->sortBy('date')->take(10) as $appointment)
                                    <tr>
                                        <td>{{ $appointment->date->format('d/m/Y') }}</td>
                                        <td>{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                                        <td>{{ $appointment->patient->full_name ?? '-' }}</td>
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
