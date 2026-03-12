@extends('layouts.public')
@section('title', 'Suscripción Cancelada')
@section('content')
<div style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div style="text-align: center; max-width: 500px; padding: 60px 24px;">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(139,92,246,0.15); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <i class="icon-check" style="font-size: 28px; color: var(--accent);"></i>
        </div>
        <h1 style="font-size: 28px; margin-bottom: 12px;">Suscripción Cancelada</h1>
        <p style="color: var(--text-muted); font-size: 16px; margin-bottom: 32px;">
            Tu dirección <strong style="color: var(--text-bright);">{{ $subscriber->email }}</strong> ha sido eliminada de nuestra lista de correo. No recibirás más emails promocionales.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 14px 32px;">Volver al Inicio</a>
    </div>
</div>
@endsection
