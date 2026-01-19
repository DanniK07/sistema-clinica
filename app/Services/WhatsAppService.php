<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Enviar mensaje por WhatsApp.
     * 
     * NOTA: Esta es una estructura preparada para implementar la integración con WhatsApp API.
     * Actualmente retorna un error simulado.
     * 
     * Para implementar, puedes usar:
     * - Twilio WhatsApp API
     * - 360dialog API
     * - WhatsApp Business API
     * - Otra API de WhatsApp
     * 
     * @param string $phone Número de teléfono del destinatario
     * @param string $message Mensaje a enviar
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function sendMessage(string $phone, string $message): array
    {
        try {
            // TODO: Implementar integración con WhatsApp API
            // Ejemplo de estructura para Twilio:
            /*
            $twilio = new \Twilio\Rest\Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $result = $twilio->messages->create(
                "whatsapp:{$phone}",
                [
                    'from' => 'whatsapp:' . config('services.twilio.whatsapp_from'),
                    'body' => $message
                ]
            );

            return [
                'success' => true,
                'message_id' => $result->sid
            ];
            */

            // Por ahora, solo logueamos el intento
            Log::info('WhatsApp reminder prepared (not implemented)', [
                'phone' => $phone,
                'message_length' => strlen($message)
            ]);

            // Retornar error simulado hasta que se implemente
            return [
                'success' => false,
                'error' => 'WhatsApp API no está configurada. Por favor, implemente la integración.'
            ];

        } catch (\Exception $e) {
            Log::error('Error en WhatsAppService', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validar formato de número de teléfono para WhatsApp.
     * 
     * @param string $phone Número de teléfono
     * @return bool
     */
    public function validatePhone(string $phone): bool
    {
        // Formato esperado: código de país + número (ej: +51987654321)
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        return preg_match('/^\+[1-9]\d{10,14}$/', $phone) === 1;
    }

    /**
     * Formatear número de teléfono para WhatsApp.
     * 
     * @param string $phone Número de teléfono
     * @param string $countryCode Código de país por defecto (ej: +51 para Perú)
     * @return string
     */
    public function formatPhone(string $phone, string $countryCode = '+51'): string
    {
        // Remover caracteres no numéricos excepto +
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Si no empieza con +, agregar código de país
        if (!str_starts_with($phone, '+')) {
            // Remover el 0 inicial si existe
            $phone = ltrim($phone, '0');
            $phone = $countryCode . $phone;
        }

        return $phone;
    }
}
