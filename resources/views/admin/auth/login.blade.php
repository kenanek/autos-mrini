<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inicia Sesión — Autos Mrini Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%); overflow: hidden; }
        .login-wrapper { position: relative; z-index: 10; width: 100%; max-width: 440px; padding: 16px; animation: fadeIn .6s ease; }
        .login-brand { text-align: center; margin-bottom: 40px; }
        .login-logo { width: 64px; height: 64px; background: linear-gradient(135deg, #3b82f6, #f59e0b); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-weight: 900; color: white; font-size: 24px; margin-bottom: 16px; box-shadow: 0 8px 32px rgba(59,130,246,.3); }
        .login-brand h1 { font-size: 28px; font-weight: 800; color: white; letter-spacing: -.03em; }
        .login-brand p { color: #94a3b8; font-size: 14px; margin-top: 4px; }
        .login-card { background: rgba(255,255,255,.04); backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,.08); border-radius: 20px; padding: 40px; box-shadow: 0 25px 50px rgba(0,0,0,.25); }
        .form-group { margin-bottom: 24px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; }
        .form-input { width: 100%; padding: 14px 16px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); border-radius: 10px; color: white; font-size: 15px; outline: none; transition: 0.2s; }
        .form-input:focus { border-color: #3b82f6; background: rgba(59,130,246,.08); }
        .btn-login { width: 100%; padding: 14px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(59,130,246,.4); }
        .form-error { color: #fca5a5; font-size: 13px; margin-top: 6px; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-brand">
            <div class="login-logo">AM</div>
            <h1>Autos Mrini</h1>
            <p>Acceso al Panel de Control</p>
        </div>
        <div class="login-card">
            @if(session('success'))
                <div style="background:#065f46; color:#34d399; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#991b1b; color:#fca5a5; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-input" placeholder="admin@autosmrini.com" value="{{ old('email') }}" required autofocus>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div style="text-align:right; margin-bottom:16px;">
                    <a href="{{ route('admin.password.request') }}" style="color:#60a5fa; font-size:13px; text-decoration:none;">¿Olvidaste tu contraseña?</a>
                </div>
                <button type="submit" class="btn-login">Inicia Sesión</button>
            </form>
        </div>
        <div style="text-align:center; margin-top:32px;"><a href="/" style="color:#64748b; text-decoration:none; font-size:13px;">← Volver a la web pública</a></div>
    </div>
</body>
</html>
