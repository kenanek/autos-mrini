@extends('layouts.public')
@section('title', 'Ubicación - ' . \App\Models\Setting::getVal('site_name', 'Autos Mrini'))
@push('styles')
<style>
    .page-hero { padding: 80px 0 60px; background: var(--surface); border-bottom: 1px solid var(--border); text-align: center; }
    .page-hero h1 { font-size: 44px; margin-bottom: 16px; letter-spacing: -0.03em; }
    .page-hero p { font-size: 17px; color: var(--text-muted); max-width: 600px; margin: 0 auto; }
    .map-card { background: var(--surface); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow: hidden; }
    .map-info-bar { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 28px; padding: 32px; border-top: 1px solid var(--border); }
    .map-info-item { display: flex; align-items: flex-start; gap: 14px; }
    .map-info-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(139,92,246,0.1); border-radius: 10px; color: var(--accent); flex-shrink: 0; }
    .map-info-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .map-info-value { font-size: 14px; font-weight: 500; color: var(--text-main); line-height: 1.5; }
</style>
@endpush
@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Nuestra Exposición</h1>
        <p>Te esperamos en Sevilla para que conozcas nuestros vehículos en persona.</p>
    </div>
</div>

<div class="container" style="padding: 80px 0;">
    <div class="map-card">
        <div style="width: 100%; height: 450px; background: var(--primary-light);">
            <iframe src="{{ \App\Models\Setting::getVal('google_maps_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d101408.21721869805!2d-6.064509172605822!3d37.37535004149959!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd126c1114be6291%3A0x34f018621cfe5648!2sSeville!5e0!3m2!1sen!2ses!4v1700000000000!5m2!1sen!2ses') }}" width="100%" height="100%" style="border:0; filter: brightness(0.85) contrast(1.1);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="map-info-bar">
            <div class="map-info-item">
                <div class="map-info-icon"><i class="icon-map-pin" style="font-size:16px;"></i></div>
                <div>
                    <div class="map-info-label">Dirección</div>
                    <div class="map-info-value">{{ \App\Models\Setting::getVal('address', 'Av. de la Innovación, 45, Sevilla') }}</div>
                </div>
            </div>
            <div class="map-info-item">
                <div class="map-info-icon"><i class="icon-phone" style="font-size:16px;"></i></div>
                <div>
                    <div class="map-info-label">Teléfono</div>
                    <div class="map-info-value">{{ \App\Models\Setting::getVal('phone', '+34 954 00 00 00') }}</div>
                </div>
            </div>
            <div class="map-info-item">
                <div class="map-info-icon"><i class="icon-clock" style="font-size:16px;"></i></div>
                <div>
                    <div class="map-info-label">Horario</div>
                    <div class="map-info-value">{!! nl2br(e(\App\Models\Setting::getVal('opening_hours', "L-V: 10:00 - 20:30\nS: 10:00 - 14:00"))) !!}</div>
                </div>
            </div>
            <div class="map-info-item">
                <div class="map-info-icon" style="background: rgba(16,185,129,0.1); color: #10b981;"><i class="icon-external-link" style="font-size:16px;"></i></div>
                <div>
                    <div class="map-info-label">Google Maps</div>
                    <div class="map-info-value"><a href="{{ \App\Models\Setting::getVal('google_maps_link', 'https://maps.google.com/?q=Sevilla') }}" target="_blank" style="color: var(--accent); font-weight: 600;">Cómo llegar →</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection