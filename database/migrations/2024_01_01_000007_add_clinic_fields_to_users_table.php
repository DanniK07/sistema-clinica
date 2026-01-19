<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar clinic_id si no existe
            if (!Schema::hasColumn('users', 'clinic_id')) {
                $table->foreignId('clinic_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
            
            // Cambiar role a enum si no está ya configurado
            // Primero verificamos si la columna existe y qué tipo tiene
            if (Schema::hasColumn('users', 'role')) {
                // Si existe pero no es enum, necesitamos cambiar el tipo
                // Por ahora, solo verificamos que existe
            } else {
                // Si no existe, la creamos
                $table->enum('role', ['admin', 'doctor'])->default('doctor')->after('password');
            }
            
            // Agregar phone si no existe
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            
            // Agregar specialty si no existe
            if (!Schema::hasColumn('users', 'specialty')) {
                $table->string('specialty')->nullable()->after('phone');
            }
            
            // Agregar active si no existe
            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(true)->after('role');
            }
            
            // Agregar soft deletes si no existe
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
            
            // Agregar índice único para email por clínica (si no existe)
            // Nota: Laravel puede agregar esto automáticamente, pero lo verificamos
            try {
                $table->unique(['clinic_id', 'email']);
            } catch (\Exception $e) {
                // El índice ya existe, no hacemos nada
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir cambios si es necesario
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('users', 'active')) {
                $table->dropColumn('active');
            }
            if (Schema::hasColumn('users', 'specialty')) {
                $table->dropColumn('specialty');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            // No revertimos clinic_id y role porque son necesarios
        });
    }
};
