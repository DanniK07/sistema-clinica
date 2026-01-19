<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Clinic;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'clinic_name' => ['required', 'string', 'max:255'],
            'clinic_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:clinics,email'],
        ], [
            'clinic_name.required' => 'El nombre de la clínica es obligatorio.',
            'clinic_email.required' => 'El email de la clínica es obligatorio.',
            'clinic_email.unique' => 'Ya existe una clínica con este email.',
        ]);

        // Crear la clínica primero
        $clinic = Clinic::create([
            'name' => $request->clinic_name,
            'email' => $request->clinic_email,
            'active' => true,
        ]);

        // Crear el usuario y asociarlo a la clínica
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'clinic_id' => $clinic->id,
            'role' => 'admin', // El primer usuario es admin
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('patients.index');
    }
}
