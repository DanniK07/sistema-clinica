<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clinicId = Auth::user()->clinic_id;
        
        $query = User::where('clinic_id', $clinicId)
                     ->where('role', 'doctor');

        // Búsqueda (sanitizada para prevenir inyección SQL)
        if ($request->filled('search')) {
            $search = $request->input('search');
            // Sanitizar input: remover caracteres peligrosos pero mantener búsqueda funcional
            $search = preg_replace('/[<>"\']/', '', $search);
            $search = trim($search);
            
            if (strlen($search) > 0 && strlen($search) <= 255) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('specialty', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }
        }

        // Filtro por estado (validado para prevenir inyección)
        if ($request->filled('active')) {
            $active = filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($active !== null) {
                $query->where('active', $active);
            }
        }

        $doctors = $query->orderBy('name')
                         ->paginate(15);

        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:255',
            'active' => 'boolean',
            'schedule' => 'nullable|array',
            'schedule.*.day' => 'required_with:schedule|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule.*.start_time' => 'required_with:schedule|date_format:H:i',
            'schedule.*.end_time' => 'required_with:schedule|date_format:H:i|after:schedule.*.start_time',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'schedule.*.day.required_with' => 'El día es obligatorio.',
            'schedule.*.day.in' => 'El día seleccionado no es válido.',
            'schedule.*.start_time.required_with' => 'La hora de inicio es obligatoria.',
            'schedule.*.start_time.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'schedule.*.end_time.required_with' => 'La hora de fin es obligatoria.',
            'schedule.*.end_time.date_format' => 'La hora de fin debe tener el formato HH:MM.',
            'schedule.*.end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);

        $clinicId = Auth::user()->clinic_id;

        // Verificar que el email no exista en esta clínica
        $exists = User::where('clinic_id', $clinicId)
                      ->where('email', $validated['email'])
                      ->exists();

        if ($exists) {
            return back()->withErrors([
                'email' => 'Ya existe un usuario con este email en la clínica.'
            ])->withInput();
        }

        $validated['clinic_id'] = $clinicId;
        $validated['role'] = 'doctor';
        $validated['password'] = Hash::make($validated['password']);
        $validated['active'] = $request->has('active') ? true : false;

        // Limpiar horarios vacíos
        if (isset($validated['schedule'])) {
            $validated['schedule'] = array_values(array_filter($validated['schedule'], function($schedule) {
                return !empty($schedule['day']) && !empty($schedule['start_time']) && !empty($schedule['end_time']);
            }));
        }

        User::create($validated);

        return redirect()->route('doctors.index')
                        ->with('success', 'Doctor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $doctor)
    {
        $this->authorizeAccess($doctor);
        
        if ($doctor->role !== 'doctor') {
            abort(404, 'El usuario no es un doctor.');
        }
        
        $doctor->load('appointments.patient', 'clinic');
        
        return view('doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $doctor)
    {
        $this->authorizeAccess($doctor);
        
        if ($doctor->role !== 'doctor') {
            abort(404, 'El usuario no es un doctor.');
        }
        
        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $doctor)
    {
        $this->authorizeAccess($doctor);
        
        if ($doctor->role !== 'doctor') {
            abort(404, 'El usuario no es un doctor.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->where(function ($query) use ($doctor) {
                    return $query->where('clinic_id', $doctor->clinic_id);
                })->ignore($doctor->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:255',
            'active' => 'boolean',
            'schedule' => 'nullable|array',
            'schedule.*.day' => 'required_with:schedule|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule.*.start_time' => 'required_with:schedule|date_format:H:i',
            'schedule.*.end_time' => 'required_with:schedule|date_format:H:i|after:schedule.*.start_time',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'email.unique' => 'Ya existe un usuario con este email en la clínica.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'schedule.*.day.required_with' => 'El día es obligatorio.',
            'schedule.*.day.in' => 'El día seleccionado no es válido.',
            'schedule.*.start_time.required_with' => 'La hora de inicio es obligatoria.',
            'schedule.*.start_time.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'schedule.*.end_time.required_with' => 'La hora de fin es obligatoria.',
            'schedule.*.end_time.date_format' => 'La hora de fin debe tener el formato HH:MM.',
            'schedule.*.end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);

        // Si no se proporciona contraseña, no actualizarla
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['active'] = $request->has('active') ? true : false;

        // Limpiar horarios vacíos
        if (isset($validated['schedule'])) {
            $validated['schedule'] = array_values(array_filter($validated['schedule'], function($schedule) {
                return !empty($schedule['day']) && !empty($schedule['start_time']) && !empty($schedule['end_time']);
            }));
        } else {
            $validated['schedule'] = null;
        }

        $doctor->update($validated);

        return redirect()->route('doctors.index')
                        ->with('success', 'Doctor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $doctor)
    {
        $this->authorizeAccess($doctor);
        
        if ($doctor->role !== 'doctor') {
            abort(404, 'El usuario no es un doctor.');
        }

        // Verificar si tiene citas
        if ($doctor->appointments()->count() > 0) {
            return redirect()->route('doctors.index')
                            ->with('error', 'No se puede eliminar el doctor porque tiene citas asociadas.');
        }

        $doctor->delete();

        return redirect()->route('doctors.index')
                        ->with('success', 'Doctor eliminado exitosamente.');
    }

    /**
     * Authorize access to the doctor (multi-tenant security).
     */
    private function authorizeAccess(User $doctor)
    {
        if ($doctor->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes acceso a este doctor.');
        }
    }
}
