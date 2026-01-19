<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Primero agregar phone si no existe (necesario antes de specialty)
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            
            // Luego agregar specialty si no existe
            if (!Schema::hasColumn('users', 'specialty')) {
                $table->string('specialty')->nullable()->after('phone');
            }
            
            // Finalmente agregar schedule
            if (!Schema::hasColumn('users', 'schedule')) {
                $table->json('schedule')->nullable()->after('specialty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'schedule')) {
                $table->dropColumn('schedule');
            }
            // Opcional: eliminar specialty también si quieres revertir completamente
            // if (Schema::hasColumn('users', 'specialty')) {
            //     $table->dropColumn('specialty');
            // }
        });
    }
};

