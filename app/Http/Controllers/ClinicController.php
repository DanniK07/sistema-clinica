<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    /**
     * Display a listing of clinics (only the user's clinic for security).
     */
    public function index()
    {
        $clinicId = Auth::user()->clinic_id;
        
        // Solo mostrar la clínica del usuario actual
        $clinic = Clinic::where('id', $clinicId)
                      ->withCount(['users', 'doctors', 'patients', 'appointments'])
                      ->firstOrFail();
        
        return view('clinics.index', compact('clinic'));
    }

    /**
     * Display the specified clinic (only if it belongs to the user).
     */
    public function show(Clinic $clinic)
    {
        // Verificar que la clínica pertenece al usuario
        if ($clinic->id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes acceso a esta clínica.');
        }

        $clinic->loadCount(['users', 'doctors', 'patients', 'appointments']);
        
        // Cargar relaciones para mostrar estadísticas
        $clinic->load([
            'users' => function($query) {
                $query->latest()->limit(5);
            },
            'doctors' => function($query) {
                $query->where('active', true)->latest()->limit(5);
            },
        ]);
        
        return view('clinics.show', compact('clinic'));
    }
}
