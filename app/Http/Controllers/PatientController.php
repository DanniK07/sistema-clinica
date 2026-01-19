<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clinicId = Auth::user()->clinic_id;
        
        $query = Patient::where('clinic_id', $clinicId);

        // Búsqueda (sanitizada para prevenir inyección SQL)
        if ($request->filled('search')) {
            $search = $request->input('search');
            // Sanitizar input: remover caracteres peligrosos pero mantener búsqueda funcional
            $search = preg_replace('/[<>"\']/', '', $search);
            $search = trim($search);
            
            if (strlen($search) > 0 && strlen($search) <= 255) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('document_number', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }
        }

        $patients = $query->orderBy('last_name')
                         ->orderBy('first_name')
                         ->paginate(15);

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:dni,passport,other',
            'document_number' => 'required|string|max:50|regex:/^[a-zA-Z0-9]+$/',
            'first_name' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'email' => 'nullable|email:rfc,dns|max:255',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'birth_date' => 'nullable|date|before:today|after:1900-01-01',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ], [
            'document_type.required' => 'El tipo de documento es obligatorio.',
            'document_type.in' => 'El tipo de documento seleccionado no es válido.',
            'document_number.required' => 'El número de documento es obligatorio.',
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'gender.in' => 'El género seleccionado no es válido.',
        ]);

        $clinicId = Auth::user()->clinic_id;

        // Verificar que el documento no exista en esta clínica
        $exists = Patient::where('clinic_id', $clinicId)
                         ->where('document_type', $validated['document_type'])
                         ->where('document_number', $validated['document_number'])
                         ->exists();

        if ($exists) {
            return back()->withErrors([
                'document_number' => 'Ya existe un paciente con este documento en la clínica.'
            ])->withInput();
        }

        $validated['clinic_id'] = $clinicId;
        
        Patient::create($validated);

        return redirect()->route('patients.index')
                        ->with('success', 'Paciente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $this->authorizeAccess($patient);
        
        $patient->load('appointments.doctor', 'appointments.patient');
        
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $this->authorizeAccess($patient);
        
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $this->authorizeAccess($patient);

        $validated = $request->validate([
            'document_type' => 'required|in:dni,passport,other',
            'document_number' => 'required|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            'document_type.required' => 'El tipo de documento es obligatorio.',
            'document_type.in' => 'El tipo de documento seleccionado no es válido.',
            'document_number.required' => 'El número de documento es obligatorio.',
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'gender.in' => 'El género seleccionado no es válido.',
        ]);

        // Verificar que el documento no exista en otra paciente de la misma clínica
        // Asegurar que siempre se filtre por clinic_id del usuario autenticado
        $clinicId = Auth::user()->clinic_id;
        if ($patient->clinic_id !== $clinicId) {
            abort(403, 'No tienes acceso a este paciente.');
        }
        
        $exists = Patient::where('clinic_id', $clinicId)
                         ->where('document_type', $validated['document_type'])
                         ->where('document_number', $validated['document_number'])
                         ->where('id', '!=', $patient->id)
                         ->exists();

        if ($exists) {
            return back()->withErrors([
                'document_number' => 'Ya existe otro paciente con este documento en la clínica.'
            ])->withInput();
        }

        $patient->update($validated);

        return redirect()->route('patients.index')
                        ->with('success', 'Paciente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $this->authorizeAccess($patient);

        // Verificar si tiene citas
        if ($patient->appointments()->count() > 0) {
            return redirect()->route('patients.index')
                            ->with('error', 'No se puede eliminar el paciente porque tiene citas asociadas.');
        }

        $patient->delete();

        return redirect()->route('patients.index')
                        ->with('success', 'Paciente eliminado exitosamente.');
    }

    /**
     * Authorize access to the patient (multi-tenant security).
     */
    private function authorizeAccess(Patient $patient)
    {
        if ($patient->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes acceso a este paciente.');
        }
    }
}
