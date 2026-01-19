<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('patients.index') }}">
            <i class="bi bi-hospital-fill text-primary fs-4 me-2"></i>
            <span class="fw-bold text-dark">Sistema Clínica</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Navigation Links -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('clinics.*') ? 'active' : '' }}" 
                       href="{{ route('clinics.index') }}">
                        <i class="bi bi-building nav-icon"></i>
                        <span class="nav-text">Clínicas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('patients.*') ? 'active' : '' }}" 
                       href="{{ route('patients.index') }}">
                        <i class="bi bi-people nav-icon"></i>
                        <span class="nav-text">Pacientes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('doctors.*') ? 'active' : '' }}" 
                       href="{{ route('doctors.index') }}">
                        <i class="bi bi-person-badge nav-icon"></i>
                        <span class="nav-text">Doctores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('appointments.*') ? 'active' : '' }}" 
                       href="{{ route('appointments.index') }}">
                        <i class="bi bi-calendar-check nav-icon"></i>
                        <span class="nav-text">Citas</span>
                    </a>
                </li>
            </ul>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" 
                        type="button" 
                        id="userDropdown" 
                        data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i>
                    <span>{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person nav-icon me-2"></i>
                            <span>Perfil</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center w-100">
                                <i class="bi bi-box-arrow-right nav-icon me-2"></i>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
/* Iconos alineados con ancho fijo */
.nav-icon {
    width: 1.25rem;
    text-align: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.nav-text {
    font-size: 0.95rem;
    font-weight: 500;
    letter-spacing: 0.01em;
}

/* Estado activo más visible */
.nav-link {
    padding: 0.625rem 1rem;
    margin: 0 0.25rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
    color: #495057;
    position: relative;
}

.nav-link:hover {
    background-color: #f8f9fa;
    color: #0d6efd;
}

.nav-link.active {
    color: #0d6efd !important;
    background-color: #e7f1ff;
    font-weight: 600;
}

.nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 70%;
    background-color: #0d6efd;
    border-radius: 0 2px 2px 0;
}

/* Tipografía consistente en dropdown */
.dropdown-item {
    font-size: 0.95rem;
    padding: 0.625rem 1rem;
    transition: all 0.15s ease-in-out;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item i {
    width: 1.25rem;
    text-align: center;
}

/* Responsive: ajustes para móvil */
@media (max-width: 991.98px) {
    .nav-link {
        padding: 0.75rem 1rem;
        margin: 0.125rem 0;
    }
    
    .nav-link.active::before {
        width: 4px;
        height: 100%;
        top: 0;
        transform: none;
    }
}
</style>
