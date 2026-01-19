<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Ejecutar el comando de recordatorios cada hora
        // Esto verifica citas que están a 24 horas de ocurrir
        $schedule->command('appointments:send-reminders')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Opcional: También puedes ejecutar cada 30 minutos para mayor precisión
        // $schedule->command('appointments:send-reminders')
        //          ->everyThirtyMinutes()
        //          ->withoutOverlapping()
        //          ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
