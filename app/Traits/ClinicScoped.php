<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Trait para agregar scope automático de clínica a los modelos.
 * Asegura que todas las consultas filtren por clinic_id del usuario autenticado.
 */
trait ClinicScoped
{
    /**
     * Boot the trait.
     */
    protected static function bootClinicScoped()
    {
        // Aplicar scope global solo si hay un usuario autenticado
        static::addGlobalScope('clinic', function ($builder) {
            if (Auth::check() && Auth::user()->clinic_id) {
                $builder->where('clinic_id', Auth::user()->clinic_id);
            }
        });
    }

    /**
     * Scope para consultas sin filtro de clínica (usar con precaución).
     */
    public function scopeWithoutClinic($query)
    {
        return $query->withoutGlobalScope('clinic');
    }
}
