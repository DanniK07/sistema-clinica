@extends('layouts.app')

@section('title', 'Nuevo Paciente')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-person-plus-fill text-primary me-2"></i>Nuevo Paciente
                </h2>
                <p class="text-muted mb-0">Registra un nuevo paciente en el sistema</p>
            </div>
            <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Información del Paciente
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('patients.store') }}" method="POST">
            @csrf

            <!-- Document Information -->
            <div class="mb-4">
                <h6 class="fw-semibold text-muted mb-3">
                    <i class="bi bi-card-text me-2"></i>Documento de Identidad
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="document_type" class="form-label fw-semibold">
                            Tipo de Documento <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg @error('document_type') is-invalid @enderror" 
                                id="document_type" 
                                name="document_type" 
                                required>
                            <option value="">Seleccione...</option>
                            <option value="dni" {{ old('document_type') == 'dni' ? 'selected' : '' }}>DNI</option>
                            <option value="passport" {{ old('document_type') == 'passport' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="other" {{ old('document_type') == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('document_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label for="document_number" class="form-label fw-semibold">
                            Número de Documento <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg @error('document_number') is-invalid @enderror" 
                               id="document_number" 
                               name="document_number" 
                               value="{{ old('document_number') }}" 
                               required>
                        @error('document_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- Personal Information -->
            <div class="mb-4">
                <h6 class="fw-semibold text-muted mb-3">
                    <i class="bi bi-person-fill me-2"></i>Información Personal
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label fw-semibold">
                            Nombre <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg @error('first_name') is-invalid @enderror" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}" 
                               required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="last_name" class="form-label fw-semibold">
                            Apellido <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg @error('last_name') is-invalid @enderror" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}" 
                               required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mb-4">
                <h6 class="fw-semibold text-muted mb-3">
                    <i class="bi bi-telephone-fill me-2"></i>Información de Contacto
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold">Teléfono</label>
                        <input type="text" 
                               class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mb-4">
                <h6 class="fw-semibold text-muted mb-3">
                    <i class="bi bi-info-circle-fill me-2"></i>Información Adicional
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="birth_date" class="form-label fw-semibold">Fecha de Nacimiento</label>
                        <input type="date" 
                               class="form-control form-control-lg @error('birth_date') is-invalid @enderror" 
                               id="birth_date" 
                               name="birth_date" 
                               value="{{ old('birth_date') }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="gender" class="form-label fw-semibold">Género</label>
                        <select class="form-select form-select-lg @error('gender') is-invalid @enderror" 
                                id="gender" 
                                name="gender">
                            <option value="">Seleccione...</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femenino</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-12">
                        <label for="address" class="form-label fw-semibold">Dirección</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-12">
                        <label for="notes" class="form-label fw-semibold">Notas</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Notas adicionales sobre el paciente...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle-fill me-2"></i>Guardar Paciente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
