<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Models\Reminder;
use App\Mail\AppointmentReminderMail;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $appointment;
    public $type;

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment, string $type = 'email')
    {
        $this->appointment = $appointment;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Cargar relaciones necesarias
            $this->appointment->load(['patient', 'doctor', 'clinic']);

            // Determinar el destinatario según el tipo
            $recipient = $this->getRecipient();

            if (!$recipient) {
                $this->createReminderRecord('failed', null, 'No se encontró destinatario para el recordatorio.');
                return;
            }

            // Enviar recordatorio según el tipo
            if ($this->type === 'email') {
                $this->sendEmailReminder($recipient);
            } elseif ($this->type === 'whatsapp') {
                $this->sendWhatsAppReminder($recipient);
            } else {
                $this->createReminderRecord('failed', $recipient, 'Tipo de recordatorio no válido.');
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar recordatorio de cita', [
                'appointment_id' => $this->appointment->id,
                'type' => $this->type,
                'error' => $e->getMessage()
            ]);

            $this->createReminderRecord(
                'failed',
                $this->getRecipient(),
                $e->getMessage()
            );
        }
    }

    /**
     * Enviar recordatorio por correo electrónico.
     */
    private function sendEmailReminder(string $email): void
    {
        try {
            Mail::to($email)->send(new AppointmentReminderMail($this->appointment));
            
            $this->createReminderRecord('sent', $email);
        } catch (\Exception $e) {
            Log::error('Error al enviar recordatorio por email', [
                'appointment_id' => $this->appointment->id,
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            $this->createReminderRecord('failed', $email, $e->getMessage());
        }
    }

    /**
     * Enviar recordatorio por WhatsApp.
     */
    private function sendWhatsAppReminder(string $phone): void
    {
        try {
            $whatsappService = new WhatsAppService();
            $message = $this->buildWhatsAppMessage();
            
            $result = $whatsappService->sendMessage($phone, $message);
            
            if ($result['success']) {
                $this->createReminderRecord('sent', $phone);
            } else {
                $this->createReminderRecord('failed', $phone, $result['error'] ?? 'Error desconocido');
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar recordatorio por WhatsApp', [
                'appointment_id' => $this->appointment->id,
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            $this->createReminderRecord('failed', $phone, $e->getMessage());
        }
    }

    /**
     * Obtener el destinatario según el tipo de recordatorio.
     */
    private function getRecipient(): ?string
    {
        if ($this->type === 'email') {
            return $this->appointment->patient->email;
        } elseif ($this->type === 'whatsapp') {
            return $this->appointment->patient->phone;
        }

        return null;
    }

    /**
     * Construir el mensaje de WhatsApp.
     */
    private function buildWhatsAppMessage(): string
    {
        $clinicName = $this->appointment->clinic->name;
        $patientName = $this->appointment->patient->full_name;
        $doctorName = $this->appointment->doctor->name;
        $date = $this->appointment->date->format('d/m/Y');
        $time = $this->appointment->start_time;
        $type = $this->appointment->type ?? 'Consulta médica';

        return "Hola {$patientName},\n\n" .
               "Este es un recordatorio de su cita médica:\n\n" .
               "📅 Fecha: {$date}\n" .
               "🕐 Hora: {$time}\n" .
               "👨‍⚕️ Doctor: {$doctorName}\n" .
               "🏥 Clínica: {$clinicName}\n" .
               "📋 Tipo: {$type}\n\n" .
               "Por favor, confirme su asistencia.\n\n" .
               "Gracias.";
    }

    /**
     * Crear registro de recordatorio en la base de datos.
     */
    private function createReminderRecord(string $status, ?string $recipient, ?string $errorMessage = null): void
    {
        Reminder::create([
            'clinic_id' => $this->appointment->clinic_id,
            'appointment_id' => $this->appointment->id,
            'type' => $this->type,
            'recipient' => $recipient ?? '',
            'sent_at' => $status === 'sent' ? now() : null,
            'status' => $status,
            'error_message' => $errorMessage,
        ]);
    }
}
