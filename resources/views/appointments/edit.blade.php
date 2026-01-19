@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-calendar-event-fill text-primary me-2"></i>Editar Cita
                </h2>
                <p class="text-muted mb-0">Modifica la información de la cita</p>
            </div>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Información de la Cita
        </h5>
    </div>
    <div class="card-body">
                <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="patient_id" class="form-label">Paciente <span class="text-danger">*</span></label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" 
                                    id="patient_id" 
                                    name="patient_id" 
                                    required>
                                <option value="">Seleccione un paciente...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }} - {{ strtoupper($patient->document_type) }}: {{ $patient->document_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="doctor_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                            <select class="form-select @error('doctor_id') is-invalid @enderror" 
                                    id="doctor_id" 
                                    name="doctor_id" 
                                    required>
                                <option value="">Seleccione un doctor...</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}@if($doctor->specialty) - {{ $doctor->specialty }}@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', $appointment->date->format('Y-m-d')) }}" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="start_time" class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" 
                                   name="start_time" 
                                   value="{{ old('start_time', $appointment->start_time) }}" 
                                   required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="end_time" class="form-label">Hora Fin <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" 
                                   name="end_time" 
                                   value="{{ old('end_time', $appointment->end_time) }}" 
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                                <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                <option value="attended" {{ old('status', $appointment->status) == 'attended' ? 'selected' : '' }}>Atendida</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label">Tipo de Consulta</label>
                            <input type="text" 
                                   class="form-control @error('type') is-invalid @enderror" 
                                   id="type" 
                                   name="type" 
                                   value="{{ old('type', $appointment->type) }}"
                                   placeholder="Ej: Consulta general, Control, etc.">
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3" id="cancellation_reason_row" style="display: {{ old('status', $appointment->status) == 'cancelled' ? 'block' : 'none' }};">
                        <div class="col-md-12">
                            <label for="cancellation_reason" class="form-label">Motivo de Cancelación</label>
                            <textarea class="form-control @error('cancellation_reason') is-invalid @enderror" 
                                      id="cancellation_reason" 
                                      name="cancellation_reason" 
                                      rows="2">{{ old('cancellation_reason', $appointment->cancellation_reason) }}</textarea>
                            @error('cancellation_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle-fill me-2"></i>Actualizar Cita
                </button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('status').addEventListener('change', function() {
        const cancellationRow = document.getElementById('cancellation_reason_row');
        const cancellationReason = document.getElementById('cancellation_reason');
        
        if (this.value === 'cancelled') {
            cancellationRow.style.display = 'block';
            cancellationReason.required = true;
        } else {
            cancellationRow.style.display = 'none';
            cancellationReason.required = false;
            cancellationReason.value = '';
        }
    });
</script>
@endpush
@endsection
