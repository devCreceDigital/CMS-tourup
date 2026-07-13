<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Nueva reserva</title></head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="background: #059669; padding: 30px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 24px;">Nueva Reserva Recibida</h1>
        </div>
        <div style="padding: 30px;">
            <p>Se ha registrado una nueva reserva en el sistema.</p>
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee; color: #666;">Referencia</td><td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">{{ $booking->reference }}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee; color: #666;">Viaje</td><td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $booking->trip->name }}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee; color: #666;">Viajero</td><td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $booking->traveler->first_name }} {{ $booking->traveler->last_name }}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee; color: #666;">Email</td><td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $booking->traveler->email }}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee; color: #666;">Estado</td><td style="padding: 8px; border-bottom: 1px solid #eee;">{{ ucfirst($booking->booking_status) }}</td></tr>
            </table>
            <p style="color: #666; font-size: 14px;">Accede al panel de administración para gestionar la reserva.</p>
        </div>
        <div style="background: #f9fafb; padding: 20px; text-align: center; color: #999; font-size: 12px;">
            <p>© {{ date('Y') }} TOUR UP CMS. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
