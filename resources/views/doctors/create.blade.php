@extends('layouts.app')

@section('title', 'Nuevo Doctor')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-person-plus-fill text-primary me-2"></i>Nuevo Doctor
                </h2>
                <p class="text-muted mb-0">Registra un nuevo doctor en el sistema</p>
            </div>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Información del Doctor
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('doctors.store') }}" method="POST" id="doctorForm">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="specialty" class="form-label">Especialidad</label>
                            <input type="text" 
                                   class="form-control @error('specialty') is-invalid @enderror" 
                                   id="specialty" 
                                   name="specialty" 
                                   value="{{ old('specialty') }}"
                                   placeholder="Ej: Cardiología, Pediatría, etc.">
                            @error('specialty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="active" 
                                       name="active" 
                                       value="1"
                                       {{ old('active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">
                                    Activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h5><i class="bi bi-clock"></i> Horarios de Atención</h5>
                        <p class="text-muted small">Configure los horarios de atención del doctor. Puede agregar múltiples días.</p>
                    </div>

                    <div id="scheduleContainer">
                        <div class="schedule-item mb-3 border p-3 rounded">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Día <span class="text-danger">*</span></label>
                                    <select class="form-select schedule-day" name="schedule[0][day]">
                                        <option value="">Seleccione...</option>
                                        <option value="monday">Lunes</option>
                                        <option value="tuesday">Martes</option>
                                        <option value="wednesday">Miércoles</option>
                                        <option value="thursday">Jueves</option>
                                        <option value="friday">Viernes</option>
                                        <option value="saturday">Sábado</option>
                                        <option value="sunday">Domingo</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           class="form-control schedule-start" 
                                           name="schedule[0][start_time]">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           class="form-control schedule-end" 
                                           name="schedule[0][end_time]">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-schedule" style="display: none;">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="addSchedule">
                        <i class="bi bi-plus-circle"></i> Agregar Otro Día
                    </button>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle-fill me-2"></i>Guardar Doctor
                </button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let scheduleIndex = 1;

    document.getElementById('addSchedule').addEventListener('click', function() {
        const container = document.getElementById('scheduleContainer');
        const newItem = document.createElement('div');
        newItem.className = 'schedule-item mb-3 border p-3 rounded';
        newItem.innerHTML = `
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Día <span class="text-danger">*</span></label>
                    <select class="form-select schedule-day" name="schedule[${scheduleIndex}][day]">
                        <option value="">Seleccione...</option>
                        <option value="monday">Lunes</option>
                        <option value="tuesday">Martes</option>
                        <option value="wednesday">Miércoles</option>
                        <option value="thursday">Jueves</option>
                        <option value="friday">Viernes</option>
                        <option value="saturday">Sábado</option>
                        <option value="sunday">Domingo</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                    <input type="time" class="form-control schedule-start" name="schedule[${scheduleIndex}][start_time]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                    <input type="time" class="form-control schedule-end" name="schedule[${scheduleIndex}][end_time]">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-schedule">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        scheduleIndex++;

        // Mostrar botones de eliminar si hay más de un elemento
        updateRemoveButtons();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-schedule')) {
            e.target.closest('.schedule-item').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.schedule-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-schedule');
            if (removeBtn) {
                removeBtn.style.display = items.length > 1 ? 'block' : 'none';
            }
        });
    }
</script>
@endpush
@endsection
