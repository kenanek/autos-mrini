<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8"><title>Recuperar Contraseña — Autos Mrini</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: white; overflow: hidden; }
        .card { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); padding: 40px; border-radius: 16px; width: 100%; max-width: 440px; box-shadow: 0 20px 40px rgba(0,0,0,.3); }
        .form-input { width: 100%; padding: 14px 16px; background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.1); border-radius: 8px; color: white; margin-bottom: 20px; outline:none; font-size: 15px; }
        .btn { width: 100%; padding: 14px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn:hover { background: #2563eb; transform: translateY(-1px); }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="margin-bottom:10px; font-size:24px;">Recuperar acceso</h2>
        <p style="color:#94a3b8; font-size:14px; margin-bottom:30px;">Introduce tu correo electrónico para recibir un enlace de recuperación seguro.</p>
        
        @if(session('status'))
            <div style="background:#065f46; color:#34d399; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;">{{ session('status') }}</div>
        @endif
        
        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf
            <label style="display:block; font-size:13px; color:#cbd5e1; margin-bottom:8px;">Correo Electrónico</label>
            <input type="email" name="email" class="form-input" required autofocus placeholder="admin@autosmrini.com">
            @error('email') <div style="color:#fca5a5; font-size:13px; margin-top:-15px; margin-bottom:20px;">{{ $message }}</div> @enderror
            <button type="submit" class="btn">Enviar enlace de acceso</button>
        </form>
        <div style="text-align:center; margin-top:20px;"><a href="{{ route('admin.login') }}" style="color:#60a5fa; text-decoration:none; font-size:14px;">← Volver al inicio de sesión</a></div>
    </div>
</body>
</html>