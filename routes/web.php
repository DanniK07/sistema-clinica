<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClinicController;
use App\Http\Middleware\EnsureClinicAccess;
use App\Http\Middleware\VerifyClinicOwnership;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// Ruta de prueba para verificar que Laravel responde
Route::get('/ping', function () {
    return 'pong';
});
// Ruta raíz - redirige a login si no está autenticado o a home si está autenticado
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('patients.index');
    }
    return redirect()->route('login');
});

// Incluir rutas de autenticación
require __DIR__.'/auth.php';

// Rutas de perfil (solo autenticación, sin verificación de clínica)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas con autenticación y verificación de clínica
Route::middleware(['auth', EnsureClinicAccess::class])->group(function () {
    // Rutas de recursos (el middleware VerifyClinicOwnership se aplicará automáticamente 
    // en los controladores para rutas con parámetros)
    Route::resource('patients', PatientController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('appointments', AppointmentController::class);
    
    // Rutas de clínicas (solo ver, no crear/editar - se crea en el registro)
    Route::get('clinics', [ClinicController::class, 'index'])->name('clinics.index');
    Route::get('clinics/{clinic}', [ClinicController::class, 'show'])->name('clinics.show');
});