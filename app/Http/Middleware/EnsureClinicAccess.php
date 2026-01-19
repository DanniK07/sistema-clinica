<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para asegurar que el usuario solo acceda a recursos de su clínica.
 */
class EnsureClinicAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            abort(401, 'No autenticado.');
        }

        // Verificar que el usuario tenga una clínica asignada
        $user = Auth::user();
        if (!$user->clinic_id) {
            abort(403, 'Usuario sin clínica asignada.');
        }

        // Verificar que la clínica esté activa
        if ($user->clinic && !$user->clinic->active) {
            abort(403, 'Su clínica está inactiva.');
        }

        return $next($request);
    }
}
