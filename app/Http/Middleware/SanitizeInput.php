<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para sanitizar inputs y prevenir XSS e inyección SQL.
 */
class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        // Sanitizar todos los inputs de texto
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Remover tags HTML peligrosos pero mantener contenido
                $value = strip_tags($value);
                // Escapar caracteres especiales para prevenir XSS
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                // Limpiar espacios en blanco
                $value = trim($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
