<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clinicId = Auth::user()->clinic_id;
        
        $query = Appointment::where('clinic_id', $clinicId)
                           ->with(['patient', 'doctor']);

        // Filtro por fecha (validado)
        if ($request->filled('date')) {
            $date = $request->input('date');
            // Validar formato de fecha
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $query->whereDate('date', $date);
            }
        } else {
            // Por defecto mostrar citas desde hoy
            $query->whereDate('date', '>=', now()->toDateString());
        }

        // Filtro por doctor (validado y verificado que pertenece a la clínica)
        if ($request->filled('doctor_id')) {
            $doctorId = (int) $request->input('doctor_id');
            if ($doctorId > 0) {
                // Verificar que el doctor pertenece a la clínica
                $doctorExists = User::where('id', $doctorId)
                                   ->where('clinic_id', $clinicId)
                                   ->where('role', 'doctor')
                                   ->exists();
                if ($doctorExists) {
                    $query->where('doctor_id', $doctorId);
                }
            }
        }

        // Filtro por estado (validado contra valores permitidos)
        if ($request->filled('status')) {
            $status = $request->input('status');
            if (in_array($status, ['pending', 'confirmed', 'cancelled', 'attended'])) {
                $query->where('status', $status);
            }
        }

        // Filtro por paciente (validado y verificado que pertenece a la clínica)
        if ($request->filled('patient_id')) {
            $patientId = (int) $request->input('patient_id');
            if ($patientId > 0) {
                // Verificar que el paciente pertenece a la clínica
                $patientExists = Patient::where('id', $patientId)
                                       ->where('clinic_id', $clinicId)
                                       ->exists();
                if ($patientExists) {
                    $query->where('patient_id', $patientId);
                }
            }
        }

        // Ordenar por fecha y hora
        $appointments = $query->orderBy('date')
                              ->orderBy('start_time')
                              ->paginate(20);

        // Obtener doctores y pacientes para los filtros
        $doctors = User::where('clinic_id', $clinicId)
                      ->where('role', 'doctor')
                      ->where('active', true)
                      ->orderBy('name')
                      ->get();

        $patients = Patient::where('clinic_id', $clinicId)
                          ->orderBy('last_name')
                          ->orderBy('first_name')
                          ->get();

        return view('appointments.index', compact('appointments', 'doctors', 'patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clinicId = Auth::user()->clinic_id;

        $patients = Patient::where('clinic_id', $clinicId)
                          ->orderBy('last_name')
                          ->orderBy('first_name')
                          ->get();

        $doctors = User::where('clinic_id', $clinicId)
                      ->where('role', 'doctor')
                      ->where('active', true)
                      ->orderBy('name')
                      ->get();

        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:pending,confirmed,cancelled,attended',
            'type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ], [
            'patient_id.required' => 'El paciente es obligatorio.',
            'patient_id.exists' => 'El paciente seleccionado no existe.',
            'doctor_id.required' => 'El doctor es obligatorio.',
            'doctor_id.exists' => 'El doctor seleccionado no existe.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser una fecha válida.',
            'date.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
            'start_time.required' => 'La hora de inicio es obligatoria.',
            'start_time.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'end_time.required' => 'La hora de fin es obligatoria.',
            'end_time.date_format' => 'La hora de fin debe tener el formato HH:MM.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
        ]);

        $clinicId = Auth::user()->clinic_id;

        // Verificar que el paciente pertenece a la clínica
        $patient = Patient::where('id', $validated['patient_id'])
                         ->where('clinic_id', $clinicId)
                         ->firstOrFail();

        // Verificar que el doctor pertenece a la clínica y es doctor
        $doctor = User::where('id', $validated['doctor_id'])
                      ->where('clinic_id', $clinicId)
                      ->where('role', 'doctor')
                      ->where('active', true)
                      ->firstOrFail();

        // Validar solapamiento de citas para el mismo doctor
        $overlapping = $this->checkOverlapping($clinicId, $validated['doctor_id'], $validated['date'], $validated['start_time'], $validated['end_time']);

        if ($overlapping) {
            return back()->withErrors([
                'start_time' => 'El doctor ya tiene una cita en este horario. Por favor, seleccione otro horario.'
            ])->withInput();
        }

        $validated['clinic_id'] = $clinicId;
        $validated['created_by'] = Auth::id();

        Appointment::create($validated);

        return redirect()->route('appointments.index')
                        ->with('success', 'Cita creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $this->authorizeAccess($appointment);
        
        $appointment->load(['patient', 'doctor', 'creator', 'reminders']);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $this->authorizeAccess($appointment);
        
        $clinicId = Auth::user()->clinic_id;

        $patients = Patient::where('clinic_id', $clinicId)
                          ->orderBy('last_name')
                          ->orderBy('first_name')
                          ->get();

        $doctors = User::where('clinic_id', $clinicId)
                      ->where('role', 'doctor')
                      ->where('active', true)
                      ->orderBy('name')
                      ->get();

        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeAccess($appointment);

        $clinicId = Auth::user()->clinic_id;
        
        $validated = $request->validate([
            'patient_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($clinicId) {
                    $exists = Patient::where('id', $value)
                                   ->where('clinic_id', $clinicId)
                                   ->exists();
                    if (!$exists) {
                        $fail('El paciente seleccionado no existe o no pertenece a su clínica.');
                    }
                },
            ],
            'doctor_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($clinicId) {
                    $exists = User::where('id', $value)
                                ->where('clinic_id', $clinicId)
                                ->where('role', 'doctor')
                                ->where('active', true)
                                ->exists();
                    if (!$exists) {
                        $fail('El doctor seleccionado no existe, no pertenece a su clínica o está inactivo.');
                    }
                },
            ],
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:pending,confirmed,cancelled,attended',
            'type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'cancellation_reason' => 'nullable|string|required_if:status,cancelled',
        ], [
            'patient_id.required' => 'El paciente es obligatorio.',
            'patient_id.exists' => 'El paciente seleccionado no existe.',
            'doctor_id.required' => 'El doctor es obligatorio.',
            'doctor_id.exists' => 'El doctor seleccionado no existe.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser una fecha válida.',
            'start_time.required' => 'La hora de inicio es obligatoria.',
            'start_time.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'end_time.required' => 'La hora de fin es obligatoria.',
            'end_time.date_format' => 'La hora de fin debe tener el formato HH:MM.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
            'cancellation_reason.required_if' => 'El motivo de cancelación es obligatorio cuando el estado es cancelado.',
        ]);

        $clinicId = Auth::user()->clinic_id;

        // Verificar que el paciente pertenece a la clínica
        $patient = Patient::where('id', $validated['patient_id'])
                         ->where('clinic_id', $clinicId)
                         ->firstOrFail();

        // Verificar que el doctor pertenece a la clínica y es doctor
        $doctor = User::where('id', $validated['doctor_id'])
                      ->where('clinic_id', $clinicId)
                      ->where('role', 'doctor')
                      ->where('active', true)
                      ->firstOrFail();

        // Validar solapamiento de citas para el mismo doctor (excluyendo la cita actual)
        $overlapping = $this->checkOverlapping(
            $clinicId, 
            $validated['doctor_id'], 
            $validated['date'], 
            $validated['start_time'], 
            $validated['end_time'],
            $appointment->id
        );

        if ($overlapping) {
            return back()->withErrors([
                'start_time' => 'El doctor ya tiene otra cita en este horario. Por favor, seleccione otro horario.'
            ])->withInput();
        }

        // Si no está cancelada, limpiar el motivo de cancelación
        if ($validated['status'] !== 'cancelled') {
            $validated['cancellation_reason'] = null;
        }

        $appointment->update($validated);

        return redirect()->route('appointments.index')
                        ->with('success', 'Cita actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorizeAccess($appointment);

        $appointment->delete();

        return redirect()->route('appointments.index')
                        ->with('success', 'Cita eliminada exitosamente.');
    }

    /**
     * Check if there's an overlapping appointment for the same doctor.
     */
    private function checkOverlapping($clinicId, $doctorId, $date, $startTime, $endTime, $excludeId = null)
    {
        $query = Appointment::where('clinic_id', $clinicId)
                            ->where('doctor_id', $doctorId)
                            ->whereDate('date', $date)
                            ->where(function ($q) use ($startTime, $endTime) {
                                // Cita nueva empieza durante una cita existente
                                $q->where(function ($subQ) use ($startTime, $endTime) {
                                    $subQ->where('start_time', '<=', $startTime)
                                         ->where('end_time', '>', $startTime);
                                })
                                // Cita nueva termina durante una cita existente
                                ->orWhere(function ($subQ) use ($startTime, $endTime) {
                                    $subQ->where('start_time', '<', $endTime)
                                         ->where('end_time', '>=', $endTime);
                                })
                                // Cita nueva contiene completamente una cita existente
                                ->orWhere(function ($subQ) use ($startTime, $endTime) {
                                    $subQ->where('start_time', '>=', $startTime)
                                         ->where('end_time', '<=', $endTime);
                                });
                            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Authorize access to the appointment (multi-tenant security).
     */
    private function authorizeAccess(Appointment $appointment)
    {
        if ($appointment->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes acceso a esta cita.');
        }
    }
}
