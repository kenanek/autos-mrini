@extends('layouts.public')
@section('title', 'Sobre Nosotros - Autos Mrini')
@push('styles')
<style>
    .page-hero { padding: 80px 0 60px; background: var(--surface); border-bottom: 1px solid var(--border); text-align: center; }
    .page-hero h1 { font-size: 44px; margin-bottom: 16px; letter-spacing: -0.03em; }
    .page-hero p { font-size: 17px; color: var(--text-muted); max-width: 600px; margin: 0 auto; line-height: 1.6; }
    .about-section { padding: 100px 0; }
    .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
    .about-img { border-radius: var(--radius-lg); border: 1px solid var(--border); }
    .check-item { display: flex; align-items: center; gap: 14px; font-weight: 500; font-size: 15px; color: var(--text-main); }
    .check-icon { width: 32px; height: 32px; background: rgba(139,92,246,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--accent); flex-shrink: 0; }
    .values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px; padding: 80px 0; }
    .value-card { background: var(--surface); padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border); text-align: center; transition: all 0.3s; }
    .value-card:hover { border-color: var(--border-light); transform: translateY(-4px); }
    .value-icon { width: 64px; height: 64px; background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.2); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 20px; color: var(--accent); }
    @media (max-width: 900px) { .about-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Sobre {{ \App\Models\Setting::getVal('site_name', 'Autos Mrini') }}</h1>
        <p>Tu concesionario premium de confianza en Sevilla, combinando pasión, transparencia y calidad automotriz.</p>
    </div>
</div>

<div class="about-section">
    <div class="container about-grid">
        <div>
            <h2 style="font-size:34px; margin-bottom:24px; letter-spacing:-0.03em; line-height:1.2;">Exigencia y transparencia en cada vehículo</h2>
            <div style="color:var(--text-muted); font-size:16px; line-height:1.8; margin-bottom:32px;">{!! nl2br(e(\App\Models\Setting::getVal('about_page_text', "En Autos Mrini sabemos que la compra de un coche es una decisión importante. Por eso, hemos revolucionado la forma de entender el vehículo de ocasión, ofreciendo unidades exclusivas que superan estrictos controles de calidad.\n\nNuestro equipo cuenta con más de dos décadas de experiencia en el sector automotriz. Seleccionamos únicamente vehículos con mantenimientos certificados y un historial transparente para ofrecerte coches que se sienten y conducen como el primer día."))) !!}</div>
            <ul style="display:flex; flex-direction:column; gap:16px;">
                <li class="check-item"><span class="check-icon"><i class="icon-check" style="font-size:14px;"></i></span> Todos los mantenimientos al día</li>
                <li class="check-item"><span class="check-icon"><i class="icon-check" style="font-size:14px;"></i></span> Kilómetros certificados y verificados</li>
                <li class="check-item"><span class="check-icon"><i class="icon-check" style="font-size:14px;"></i></span> Garantía Premium a nivel nacional</li>
                <li class="check-item"><span class="check-icon"><i class="icon-check" style="font-size:14px;"></i></span> Asesoramiento financiero personalizado</li>
            </ul>
        </div>
        <div>
            <img src="https://images.unsplash.com/photo-1542282088-fe8426682b8f?w=800&auto=format&fit=crop" alt="Autos Mrini Concesionario" class="about-img">
        </div>
    </div>
</div>

<div style="border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
    <div class="container values-grid">
        <div class="value-card">
            <div class="value-icon"><i class="icon-shield-check"></i></div>
            <h3 style="font-size: 20px; margin-bottom: 12px;">Confianza</h3>
            <p style="color: var(--text-muted); line-height: 1.7; font-size: 14px;">Más de dos décadas de experiencia nos avalan. Cada vehículo pasa rigurosos controles para tu total tranquilidad.</p>
        </div>
        <div class="value-card">
            <div class="value-icon"><i class="icon-users"></i></div>
            <h3 style="font-size: 20px; margin-bottom: 12px;">Cercanía</h3>
            <p style="color: var(--text-muted); line-height: 1.7; font-size: 14px;">Te acompañamos en todo el proceso; desde la selección del coche hasta la postventa. Trato humano y personalizado.</p>
        </div>
        <div class="value-card">
            <div class="value-icon"><i class="icon-star"></i></div>
            <h3 style="font-size: 20px; margin-bottom: 12px;">Calidad</h3>
            <p style="color: var(--text-muted); line-height: 1.7; font-size: 14px;">Solo seleccionamos vehículos que cumplen nuestros altos estándares de calidad, presentación y kilometraje.</p>
        </div>
    </div>
</div>
@endsection