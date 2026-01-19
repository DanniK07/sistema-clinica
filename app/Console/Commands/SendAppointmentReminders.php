<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Jobs\SendAppointmentReminder;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders 
                            {--hours=24 : Horas antes de la cita para enviar el recordatorio}
                            {--type=email : Tipo de recordatorio (email o whatsapp)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios automáticos de citas médicas 24 horas antes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $type = $this->option('type');

        if (!in_array($type, ['email', 'whatsapp'])) {
            $this->error('El tipo debe ser "email" o "whatsapp"');
            return 1;
        }

        // Calcular la fecha y hora objetivo (24 horas antes de la cita)
        $targetDateTime = Carbon::now()->addHours($hours);
        $targetDate = $targetDateTime->toDateString();
        $targetTime = $targetDateTime->format('H:i');

        $this->info("Buscando citas para el {$targetDate} a las {$targetTime}...");

        // Buscar citas que:
        // 1. Estén en estado pending o confirmed
        // 2. Sean en la fecha objetivo
        // 3. Tengan hora de inicio similar a la hora objetivo (con margen de 1 hora)
        // 4. No tengan un recordatorio del mismo tipo ya enviado
        $appointments = Appointment::whereIn('status', ['pending', 'confirmed'])
            ->whereDate('date', $targetDate)
            ->whereTime('start_time', '>=', Carbon::parse($targetTime)->subHour()->format('H:i'))
            ->whereTime('start_time', '<=', Carbon::parse($targetTime)->addHour()->format('H:i'))
            ->with(['patient', 'doctor', 'clinic', 'reminders'])
            ->get();

        $this->info("Se encontraron {$appointments->count()} citas.");

        $sent = 0;
        $skipped = 0;

        foreach ($appointments as $appointment) {
            // Verificar si ya se envió un recordatorio del mismo tipo
            $hasReminder = $appointment->reminders()
                ->where('type', $type)
                ->where('status', 'sent')
                ->exists();

            if ($hasReminder) {
                $this->line("  - Cita #{$appointment->id}: Ya tiene recordatorio {$type} enviado. Omitida.");
                $skipped++;
                continue;
            }

            // Verificar que el paciente tenga el contacto necesario
            if ($type === 'email' && empty($appointment->patient->email)) {
                $this->line("  - Cita #{$appointment->id}: Paciente sin email. Omitida.");
                $skipped++;
                continue;
            }

            if ($type === 'whatsapp' && empty($appointment->patient->phone)) {
                $this->line("  - Cita #{$appointment->id}: Paciente sin teléfono. Omitida.");
                $skipped++;
                continue;
            }

            // Encolar el job para enviar el recordatorio
            SendAppointmentReminder::dispatch($appointment, $type);
            
            $this->info("  ✓ Cita #{$appointment->id}: Recordatorio {$type} encolado para {$appointment->patient->full_name}");
            $sent++;
        }

        $this->newLine();
        $this->info("Resumen:");
        $this->info("  - Recordatorios encolados: {$sent}");
        $this->info("  - Citas omitidas: {$skipped}");

        return 0;
    }
}
