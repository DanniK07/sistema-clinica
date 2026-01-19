@extends('layouts.app')

@section('title', 'Detalle de la Cita')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-calendar-event-fill text-primary me-2"></i>Detalle de la Cita
                </h2>
                <p class="text-muted mb-0">{{ $appointment->date->format('d/m/Y') }} - {{ $appointment->patient->full_name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-pencil-fill me-2"></i>Editar
                </a>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-lg">
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
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información de la Cita</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Fecha:</strong><br>
                                <h4 class="mt-1">{{ $appointment->date->format('d/m/Y') }}</h4>
                                @if($appointment->date->isToday())
                                    <span class="badge bg-info">Hoy</span>
                                @elseif($appointment->date->isTomorrow())
                                    <span class="badge bg-warning">Mañana</span>
                                @elseif($appointment->date->isPast())
                                    <span class="badge bg-secondary">Pasada</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <strong>Horario:</strong><br>
                                <h5 class="mt-1">{{ $appointment->start_time }} - {{ $appointment->end_time }}</h5>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Paciente:</strong><br>
                                <a href="{{ route('patients.show', $appointment->patient) }}" class="text-decoration-none">
                                    {{ $appointment->patient->full_name }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    {{ strtoupper($appointment->patient->document_type) }}: {{ $appointment->patient->document_number }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <strong>Doctor:</strong><br>
                                <a href="{{ route('doctors.show', $appointment->doctor) }}" class="text-decoration-none">
                                    {{ $appointment->doctor->name }}
                                </a>
                                @if($appointment->doctor->specialty)
                                    <br>
                                    <small class="text-muted">{{ $appointment->doctor->specialty }}</small>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Estado:</strong><br>
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
                                <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }} fs-6">
                                    {{ $statusLabels[$appointment->status] ?? $appointment->status }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Tipo de Consulta:</strong><br>
                                {{ $appointment->type ?? '-' }}
                            </div>
                        </div>

                        @if($appointment->cancellation_reason)
                            <hr>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Motivo de Cancelación:</strong><br>
                                    <div class="alert alert-warning mb-0">
                                        {{ $appointment->cancellation_reason }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($appointment->notes)
                            <hr>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Notas:</strong><br>
                                    {{ $appointment->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Información del Registro</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Creado por:</strong><br>
                            <small>{{ $appointment->creator->name ?? 'Sistema' }}</small>
                        </p>
                        <p class="mb-2">
                            <strong>Fecha de creación:</strong><br>
                            <small>{{ $appointment->created_at->format('d/m/Y H:i') }}</small>
                        </p>
                        <p class="mb-0">
                            <strong>Última actualización:</strong><br>
                            <small>{{ $appointment->updated_at->format('d/m/Y H:i') }}</small>
                        </p>
                    </div>
                </div>

                @if($appointment->reminders->count() > 0)
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-bell"></i> Recordatorios</h5>
                        </div>
                        <div class="card-body">
                            @foreach($appointment->reminders as $reminder)
                                <div class="mb-2">
                                    <small>
                                        <i class="bi bi-{{ $reminder->type == 'email' ? 'envelope' : 'whatsapp' }}"></i>
                                        {{ $reminder->type == 'email' ? 'Email' : 'WhatsApp' }}
                                        @if($reminder->sent_at)
                                            - {{ $reminder->sent_at->format('d/m/Y H:i') }}
                                        @endif
                                        @if($reminder->status == 'sent')
                                            <span class="badge bg-success">Enviado</span>
                                        @elseif($reminder->status == 'failed')
                                            <span class="badge bg-danger">Fallido</span>
                                        @else
                                            <span class="badge bg-warning">Pendiente</span>
                                        @endif
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
