<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8"><title>Nueva Contraseña — Autos Mrini</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: white; overflow: hidden; }
        .card { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); padding: 40px; border-radius: 16px; width: 100%; max-width: 440px; }
        .form-input { width: 100%; padding: 14px 16px; background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.1); border-radius: 8px; color: white; margin-bottom: 20px; outline:none; font-size: 15px; }
        .btn { width: 100%; padding: 14px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="margin-bottom:20px; font-size:24px;">Nueva contraseña</h2>
        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <label style="display:block; font-size:13px; color:#cbd5e1; margin-bottom:8px;">Correo Electrónico</label>
            <input type="email" name="email" class="form-input" value="{{ $email ?? old('email') }}" required readonly>
            
            <label style="display:block; font-size:13px; color:#cbd5e1; margin-bottom:8px;">Nueva contraseña</label>
            <input type="password" name="password" class="form-input" required autofocus placeholder="mínimo 8 caracteres">
            
            <label style="display:block; font-size:13px; color:#cbd5e1; margin-bottom:8px;">Confirma tu contraseña</label>
            <input type="password" name="password_confirmation" class="form-input" required placeholder="repite tu contraseña">
            
            @error('email') <div style="color:#fca5a5; font-size:13px; margin-bottom:10px;">{{ $message }}</div> @enderror
            @error('password') <div style="color:#fca5a5; font-size:13px; margin-bottom:10px;">{{ $message }}</div> @enderror
            
            <button type="submit" class="btn" style="margin-top:20px;">Restablecer contraseña</button>
        </form>
    </div>
</body>
</html>