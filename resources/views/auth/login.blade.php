<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h4 class="fw-bold text-dark mb-4">Iniciar Sesión</h4>

        <!-- Email Address -->
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
                       autofocus 
                       autocomplete="username"
                       placeholder="tu@email.com">
            </div>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
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
                       autocomplete="current-password"
                       placeholder="••••••••">
            </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input type="checkbox" 
                   class="form-check-input" 
                   id="remember_me" 
                   name="remember">
            <label class="form-check-label" for="remember_me">
                Recordarme
            </label>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-decoration-none small">
                    ¿No tienes cuenta? <strong>Regístrate</strong>
                </a>
            @endif

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
        </button>
    </form>
</x-guest-layout>
