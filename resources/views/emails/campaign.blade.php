<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $campaign->subject }}</title>
<style>
body { margin:0; padding:0; background:#f4f4f7; font-family:'Helvetica Neue',Arial,sans-serif; }
.wrapper { max-width:600px; margin:0 auto; background:#ffffff; border-radius:8px; overflow:hidden; margin-top:24px; margin-bottom:24px; }
.header { background:#0f172a; padding:32px; text-align:center; }
.header h1 { color:#ffffff; font-size:22px; margin:0; }
.header .accent { color:#8b5cf6; font-style:italic; }
.content { padding:32px; font-size:15px; line-height:1.7; color:#334155; }
.footer { background:#f8fafc; padding:24px 32px; text-align:center; font-size:12px; color:#94a3b8; border-top:1px solid #e2e8f0; }
.footer a { color:#8b5cf6; }
.btn { display:inline-block; background:#8b5cf6; color:#ffffff; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600; margin:16px 0; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>AUTOS <span class="accent">MRINI</span></h1>
    </div>
    <div class="content">
        {!! $campaign->body !!}
    </div>
    <div class="footer">
        <p>Has recibido este email porque estás suscrito a nuestro boletín.</p>
        <p><a href="{{ url('/newsletter/unsubscribe/' . $subscriber->token) }}">Cancelar suscripción</a></p>
        <p>&copy; {{ date('Y') }} Autos Mrini. Todos los derechos reservados.</p>
    </div>
</div>
</body>
</html>
