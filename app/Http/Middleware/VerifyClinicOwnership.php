<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para verificar que los recursos pertenezcan a la clínica del usuario.
 * Se aplica a rutas con parámetros de modelo (route model binding).
 */
class VerifyClinicOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $clinicId = $user->clinic_id;

        // Verificar recursos en la ruta (solo si hay parámetros)
        if ($request->route() && $request->route()->parameters()) {
            $routeParameters = $request->route()->parameters();

            foreach ($routeParameters as $key => $model) {
                // Si el modelo tiene clinic_id, verificar que pertenezca a la clínica del usuario
                if (is_object($model)) {
                    $modelClinicId = null;
                    
                    if (method_exists($model, 'getAttribute')) {
                        $modelClinicId = $model->getAttribute('clinic_id');
                    } elseif (property_exists($model, 'clinic_id')) {
                        $modelClinicId = $model->clinic_id;
                    }

                    if ($modelClinicId !== null && $modelClinicId !== $clinicId) {
                        abort(403, 'No tienes acceso a este recurso.');
                    }
                }
            }
        }

        return $next($request);
    }
}
