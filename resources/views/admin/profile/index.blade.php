@extends('admin.layouts.app')
@section('title', 'Mi Perfil')
@section('breadcrumb', 'Mi Cuenta / Perfil')

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <h1 class="page-title">Mi Perfil</h1>
    <p class="page-subtitle">Gestiona tu información personal y contraseña.</p>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
    <!-- Profile Info Form -->
    <div class="card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="padding: 24px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="icon-user" style="color: #3b82f6;"></i> Información Personal
            </h3>
        </div>
        <div style="padding: 24px;">
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600; font-size:14px; color:#1e293b;">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; background: white; color: #1e293b;" required>
                        @error('name') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600; font-size:14px; color:#1e293b;">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; background: white; color: #1e293b;" required>
                        @error('email') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600; font-size:14px; color:#1e293b;">Rol (Solo Lectura)</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" class="form-control" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; background: #f1f5f9; color: #64748b;" readonly disabled>
                    </div>
                </div>
                <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding:10px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;">Guardar Perfil</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Form -->
    <div class="card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="padding: 24px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="icon-lock" style="color: #eab308;"></i> Cambiar Contraseña
            </h3>
        </div>
        <div style="padding: 24px;">
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600; font-size:14px; color:#1e293b;">Contraseña Actual</label>
                        <input type="password" name="current_password" class="form-control" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; background: white; color: #1e293b;" required>
                        @error('current_password') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600; font-size:14px; color:#1e293b;">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; background: white; color: #1e293b;" required minlength="8">
                        @error('password') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600; font-size:14px; color:#1e293b;">Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; background: white; color: #1e293b;" required minlength="8">
                    </div>
                </div>
                <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-warning" style="padding:10px 24px; background: #eab308; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;">Actualizar Contraseña</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn.btn-primary:hover { background: #2563eb !important; }
    .btn.btn-warning:hover { background: #ca8a04 !important; }
</style>
@endsection
