<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Cita Médica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .appointment-info {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-row {
            margin: 10px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Recordatorio de Cita Médica</h1>
        <p>{{ $clinic->name }}</p>
    </div>

    <div class="content">
        <p>Estimado/a <strong>{{ $patient->full_name }}</strong>,</p>

        <p>Le recordamos que tiene una cita médica programada:</p>

        <div class="appointment-info">
            <div class="info-row">
                <strong>📅 Fecha:</strong> {{ $appointment->date->format('d/m/Y') }}
            </div>
            <div class="info-row">
                <strong>🕐 Hora:</strong> {{ $appointment->start_time }}
            </div>
            <div class="info-row">
                <strong>👨‍⚕️ Doctor:</strong> {{ $doctor->name }}
                @if($doctor->specialty)
                    <br><small style="color: #6c757d;">Especialidad: {{ $doctor->specialty }}</small>
                @endif
            </div>
            @if($appointment->type)
                <div class="info-row">
                    <strong>📋 Tipo de Consulta:</strong> {{ $appointment->type }}
                </div>
            @endif
            <div class="info-row">
                <strong>🏥 Clínica:</strong> {{ $clinic->name }}
                @if($clinic->address)
                    <br><small style="color: #6c757d;">{{ $clinic->address }}</small>
                @endif
                @if($clinic->phone)
                    <br><small style="color: #6c757d;">Teléfono: {{ $clinic->phone }}</small>
                @endif
            </div>
        </div>

        <p>Por favor, confirme su asistencia o contáctenos si necesita reprogramar su cita.</p>

        <p style="margin-top: 30px;">
            <strong>Estado de la cita:</strong>
            @if($appointment->status === 'pending')
                <span style="color: #ffc107;">Pendiente</span>
            @elseif($appointment->status === 'confirmed')
                <span style="color: #0dcaf0;">Confirmada</span>
            @endif
        </p>
    </div>

    <div class="footer">
        <p>Este es un mensaje automático. Por favor, no responda a este correo.</p>
        <p>&copy; {{ date('Y') }} {{ $clinic->name }}. Todos los derechos reservados.</p>
    </div>
</body>
</html>
