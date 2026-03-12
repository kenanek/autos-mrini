@extends('layouts.public')
@section('title', 'Contacto - Autos Mrini')
@push('styles')
<style>
    .page-hero { padding: 80px 0 60px; background: var(--surface); border-bottom: 1px solid var(--border); text-align: center; }
    .page-hero h1 { font-size: 44px; margin-bottom: 16px; letter-spacing: -0.03em; }
    .page-hero p { font-size: 17px; color: var(--text-muted); max-width: 600px; margin: 0 auto; }
    .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; padding: 80px 0; }
    .c-info { background: var(--surface); padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border); }
    .c-form { background: var(--surface); padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border); }
    .c-item { display: flex; gap: 18px; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--border); }
    .c-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .c-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--accent); background: rgba(139,92,246,0.1); border-radius: 12px; flex-shrink: 0; }
    .c-text h4 { font-size: 16px; margin-bottom: 4px; font-weight: 600; }
    .c-text p { color: var(--text-muted); font-size: 14px; line-height: 1.6; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
    .form-control { width: 100%; padding: 14px 16px; border: 1px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: var(--primary); color: var(--text-main); transition: all 0.2s; }
    .form-control:focus { outline:none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
    textarea.form-control { resize: vertical; min-height: 100px; }
    @media (max-width: 900px) { .contact-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Contacta con Autos Mrini</h1>
        <p>Estamos aquí para ayudarte a encontrar tu próximo vehículo ideal.</p>
    </div>
</div>

<div class="container contact-grid">
    <div class="c-info">
        <h2 style="font-size:24px; margin-bottom:32px;">Información de Contacto</h2>
        
        <div class="c-item">
            <div class="c-icon"><i class="icon-map-pin"></i></div>
            <div class="c-text"><h4>Dirección</h4><p>{!! nl2br(e(\App\Models\Setting::getVal('address', "Av. de la Innovación, 45\n41020 Sevilla, España"))) !!}</p></div>
        </div>
        <div class="c-item">
            <div class="c-icon"><i class="icon-phone"></i></div>
            <div class="c-text"><h4>Teléfono</h4><p>{{ \App\Models\Setting::getVal('phone', '+34 954 00 00 00') }}
            @if(\App\Models\Setting::getVal('whatsapp'))<br><a href="https://wa.me/{{ \App\Models\Setting::getVal('whatsapp') }}" style="color:#10b981; font-weight:600;">WhatsApp →</a>@endif</p></div>
        </div>
        <div class="c-item">
            <div class="c-icon"><i class="icon-mail"></i></div>
            <div class="c-text"><h4>Email</h4><p>{{ \App\Models\Setting::getVal('email', 'info@autosmrini.es') }}</p></div>
        </div>
        <div class="c-item">
            <div class="c-icon"><i class="icon-clock"></i></div>
            <div class="c-text"><h4>Horario</h4><p>{!! nl2br(e(\App\Models\Setting::getVal('opening_hours', "Lunes - Viernes: 10:00h - 14:00h y 16:30h - 20:30h\nSábados: 10:00h - 14:00h"))) !!}</p></div>
        </div>
    </div>
    
    <div class="c-form">
        <h2 style="font-size:24px; margin-bottom:32px;">Envíanos un Mensaje</h2>
        @if(session('success'))
            <div style="background:rgba(16,185,129,0.15); color:#10b981; padding:14px 18px; border-radius:10px; margin-bottom:20px; font-weight:600; font-size:14px; border: 1px solid rgba(16,185,129,0.3);">{{ session('success') }}</div>
        @endif
        
        <form action="{{ route('contact.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="general">
            <div class="form-group"><label>Nombre</label><input type="text" name="name" class="form-control" placeholder="Tu nombre completo" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" placeholder="tu@email.com" required></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group"><label>Teléfono</label><input type="text" name="phone" class="form-control" placeholder="+34 600 000 000"></div>
                <div class="form-group"><label>Asunto</label><input type="text" name="subject" class="form-control" placeholder="Asunto"></div>
            </div>
            <div class="form-group"><label>Mensaje</label><textarea name="message" class="form-control" rows="5" placeholder="Cuéntanos en qué podemos ayudarte..." required></textarea></div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding: 14px;">Enviar Mensaje</button>
        </form>
    </div>
</div>
@endsection