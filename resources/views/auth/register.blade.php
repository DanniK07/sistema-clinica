<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h4 class="fw-bold text-dark mb-4">Crear Nueva Cuenta</h4>

        <!-- User Information -->
        <div class="mb-4">
            <h6 class="fw-semibold text-muted mb-3 small text-uppercase">Información del Usuario</h6>
            
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nombre Completo</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control border-start-0 @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus 
                           autocomplete="name"
                           placeholder="Tu nombre completo">
                </div>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control border-start-0 @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="username"
                           placeholder="tu@email.com">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control border-start-0 @error('password') is-invalid @enderror" 
                           required 
                           autocomplete="new-password"
                           placeholder="••••••••">
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock-fill text-muted"></i>
                    </span>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control border-start-0" 
                           required 
                           autocomplete="new-password"
                           placeholder="••••••••">
                </div>
            </div>
        </div>

        <hr class="my-4">

        <!-- Clinic Information -->
        <div class="mb-4">
            <h6 class="fw-semibold text-muted mb-3 small text-uppercase">
                <i class="bi bi-building me-1"></i>Información de la Clínica
            </h6>

            <div class="mb-3">
                <label for="clinic_name" class="form-label fw-semibold">Nombre de la Clínica</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-hospital text-muted"></i>
                    </span>
                    <input type="text" 
                           id="clinic_name" 
                           name="clinic_name" 
                           class="form-control border-start-0 @error('clinic_name') is-invalid @enderror" 
                           value="{{ old('clinic_name') }}" 
                           required
                           placeholder="Nombre de tu clínica">
                </div>
                @error('clinic_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="clinic_email" class="form-label fw-semibold">Email de la Clínica</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-envelope-at text-muted"></i>
                    </span>
                    <input type="email" 
                           id="clinic_email" 
                           name="clinic_email" 
                           class="form-control border-start-0 @error('clinic_email') is-invalid @enderror" 
                           value="{{ old('clinic_email') }}" 
                           required
                           placeholder="info@clinica.com">
                </div>
                @error('clinic_email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('login') }}" class="text-decoration-none small">
                ¿Ya tienes cuenta? <strong>Inicia sesión</strong>
            </a>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="bi bi-person-plus-fill me-2"></i>Registrarse
        </button>
    </form>
</x-guest-layout>
