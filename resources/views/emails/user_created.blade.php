<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenido a Autos Mrini</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; color: #1e293b; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <div style="background-color: #1e293b; padding: 20px; text-align: center; color: white;">
            <h2 style="margin: 0;">Autos Mrini</h2>
        </div>
        <div style="padding: 30px;">
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            <p>Se ha creado una nueva cuenta para ti en el panel de administración de Autos Mrini.</p>
            
            <div style="background-color: #f1f5f9; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <p style="margin: 0 0 10px 0;"><strong>Detalles de acceso:</strong></p>
                <ul style="margin: 0; padding-left: 20px;">
                    <li><strong>Rol de usuario:</strong> {{ ucfirst(str_replace('_', ' ', $user->role)) }}</li>
                    <li><strong>Correo electrónico:</strong> {{ $user->email }}</li>
                    <li><strong>Contraseña temporal:</strong> <span style="font-family: monospace;">{{ $plainPassword }}</span></li>
                </ul>
            </div>
            
            <p>Puedes iniciar sesión en el siguiente enlace:</p>
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/admin/login') }}" style="background-color: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">Acceder al Panel</a>
            </p>
            
            <p style="color: #ef4444; font-size: 14px;">
                <strong>Importante:</strong> Recomendamos encarecidamente que cambies tu contraseña después de tu primer inicio de sesión por motivos de seguridad.
            </p>
            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;">
            <p style="font-size: 12px; color: #64748b; text-align: center;">
                Este es un mensaje automático, por favor no respondas a este correo.
            </p>
        </div>
    </div>
</body>
</html>
