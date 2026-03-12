<?php

$dir = __DIR__ . '/resources/views/pages';
if (!is_dir($dir))
    mkdir($dir, 0755, true);

$files = [];

$files['about.blade.php'] = <<<'HTML'
@extends('layouts.public')
@section('title', 'Sobre Nosotros - Autos Mrini')
@push('styles')
<style>
    .page-header { padding: 100px 0 60px; background: linear-gradient(135deg, var(--primary) 0%, #0f172a 100%); color: white; text-align: center; }
    .page-header h1 { font-size: 48px; margin-bottom: 16px; color: white; }
    .page-header p { font-size: 18px; color: #cbd5e1; max-width: 600px; margin: 0 auto; }
    .content-section { padding: 80px 0; }
    .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
    @media (max-width: 900px) { .about-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Sobre Autos Mrini</h1>
        <p>Tu concesionario premium de confianza en Sevilla, combinando pasión, transparencia y calidad automotriz.</p>
    </div>
</div>

<div class="content-section">
    <div class="container about-grid">
        <div>
            <h2 style="font-size:32px; margin-bottom:24px;">Exigencia y transparencia en cada vehículo</h2>
            <p style="color:var(--text-muted); font-size:16px; line-height:1.8; margin-bottom:20px;">
                En Autos Mrini sabemos que la compra de un coche es una decisión importante. Por eso, hemos revolucionado la forma de entender el vehículo de ocasión, ofreciendo unidades exclusivas que superan estrictos controles de calidad.
            </p>
            <p style="color:var(--text-muted); font-size:16px; line-height:1.8; margin-bottom:20px;">
                Nuestro equipo cuenta con más de dos décadas de experiencia en el sector automotriz. Seleccionamos únicamente vehículos con mantenimientos certificados y un historial transparente para ofrecerte coches que se sienten y conducen como el primer día.
            </p>
            <ul style="margin-top:30px; display:flex; flex-direction:column; gap:16px;">
                <li style="display:flex; align-items:center; gap:12px; font-weight:500;"><span style="color:var(--accent); font-size:20px;">✓</span> Mantenimientos al día</li>
                <li style="display:flex; align-items:center; gap:12px; font-weight:500;"><span style="color:var(--accent); font-size:20px;">✓</span> Kilómetros certificados</li>
                <li style="display:flex; align-items:center; gap:12px; font-weight:500;"><span style="color:var(--accent); font-size:20px;">✓</span> Garantía Premium a nivel nacional</li>
            </ul>
        </div>
        <div>
            <img src="https://images.unsplash.com/photo-1542282088-fe8426682b8f?w=800&auto=format&fit=crop" alt="Autos Mrini Concesionario" style="border-radius:var(--radius-lg); box-shadow:var(--shadow-lg);">
        </div>
    </div>
</div>
@endsection
HTML;

$files['contact.blade.php'] = <<<'HTML'
@extends('layouts.public')
@section('title', 'Contacto - Autos Mrini')
@push('styles')
<style>
    .k-header { padding: 60px 0; background: var(--bg-color); border-bottom: 1px solid var(--border); text-align: center; }
    .k-header h1 { font-size: 36px; margin-bottom: 10px; }
    .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; padding: 60px 0; }
    .c-info { background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--border); }
    .c-form { background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border); }
    .c-item { display: flex; gap: 16px; margin-bottom: 30px; }
    .c-icon { font-size: 24px; color: var(--accent); }
    .c-text h4 { font-size: 18px; margin-bottom: 4px; }
    .c-text p { color: var(--text-muted); }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-family: inherit; }
    @media (max-width: 900px) { .contact-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="k-header">
    <div class="container">
        <h1>Contacta con Autos Mrini</h1>
        <p style="color:var(--text-muted);">Estamos aquí para ayudarte a encontrar tu próximo vehículo.</p>
    </div>
</div>

<div class="container contact-grid">
    <div class="c-info">
        <h2 style="font-size:28px; margin-bottom:30px;">Información de Contacto</h2>
        
        <div class="c-item">
            <div class="c-icon">📍</div>
            <div class="c-text"><h4>Dirección</h4><p>Av. de la Innovación, 45<br>41020 Sevilla, España</p></div>
        </div>
        <div class="c-item">
            <div class="c-icon">📞</div>
            <div class="c-text"><h4>Teléfono</h4><p>+34 954 00 00 00<br>+34 600 00 00 00 (WhatsApp)</p></div>
        </div>
        <div class="c-item">
            <div class="c-icon">✉️</div>
            <div class="c-text"><h4>Email</h4><p>info@autosmrini.es<br>ventas@autosmrini.es</p></div>
        </div>
        <div class="c-item">
            <div class="c-icon">🕒</div>
            <div class="c-text"><h4>Horario</h4><p>Lunes - Viernes: 10:00h - 14:00h y 16:30h - 20:30h<br>Sábados: 10:00h - 14:00h</p></div>
        </div>
    </div>
    
    <div class="c-form">
        <h2 style="font-size:28px; margin-bottom:30px;">Envíanos un Mensaje</h2>
        @if(session('success'))
            <div style="background:#10b981; color:white; padding:15px; border-radius:6px; margin-bottom:20px;">{{ session('success') }}</div>
        @endif
        
        <form action="{{ route('contact.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="general">
            <div class="form-group"><label>Nombre</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="form-group"><label>Teléfono</label><input type="text" name="phone" class="form-control"></div>
            <div class="form-group"><label>Asunto</label><input type="text" name="subject" class="form-control"></div>
            <div class="form-group"><label>Mensaje</label><textarea name="message" class="form-control" rows="5" required></textarea></div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Enviar Mensaje</button>
        </form>
    </div>
</div>
@endsection
HTML;

$files['location.blade.php'] = <<<'HTML'
@extends('layouts.public')
@section('title', 'Ubicación - Autos Mrini')
@section('content')
<div style="padding: 60px 0; background: var(--bg-color); text-align: center; border-bottom: 1px solid var(--border);">
    <div class="container">
        <h1 style="font-size:36px; margin-bottom:10px;">Nuestra Exposición</h1>
        <p style="color:var(--text-muted);">Te esperamos en Sevilla para que conozcas nuestros vehículos en persona.</p>
    </div>
</div>

<div class="container" style="padding: 60px 0;">
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden;">
        <!-- Embedded Google Maps Placeholder (Sevilla coords used as demo) -->
        <div style="width: 100%; height: 500px; background: #e2e8f0; position: relative;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d101408.21721869805!2d-6.064509172605822!3d37.37535004149959!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd126c1114be6291%3A0x34f018621cfe5648!2sSeville!5e0!3m2!1sen!2ses!4v1700000000000!5m2!1sen!2ses" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <div style="position: absolute; bottom: 20px; left: 20px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                <h3 style="margin-bottom: 8px;">Autos Mrini</h3>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 8px;">Av. de la Innovación, 45, Sevilla</p>
                <a href="https://maps.google.com/?q=Sevilla" target="_blank" style="color: var(--accent); font-size: 14px; font-weight: 600;">Abrir en Google Maps &rarr;</a>
            </div>
        </div>
    </div>
</div>
@endsection
HTML;

$files['financing.blade.php'] = <<<'HTML'
@extends('layouts.public')
@section('title', 'Financiación a Medida - Autos Mrini')
@section('content')
<div style="padding: 100px 0 60px; background: linear-gradient(135deg, #10b981 0%, #047857 100%); color: white; text-align: center;">
    <div class="container">
        <h1 style="font-size:48px; margin-bottom:16px; color:white;">Financiación a tu medida</h1>
        <p style="font-size:18px; color:#d1fae5; max-width:600px; margin:0 auto;">En Autos Mrini hacemos que la compra de tu próximo coche sea fácil, cómoda y adaptada a tus necesidades financieras.</p>
    </div>
</div>

<div class="container" style="padding: 80px 0;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
        <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border);">
            <div style="font-size:40px; margin-bottom:20px;">💯</div>
            <h3 style="font-size:24px; margin-bottom:16px;">100% Financiable</h3>
            <p style="color:var(--text-muted); line-height:1.6;">Podemos financiar hasta el 100% del valor del vehículo seleccionado sin necesidad de entrada, permitiéndote estrenar coche al instante.</p>
        </div>
        <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border);">
            <div style="font-size:40px; margin-bottom:20px;">⏱️</div>
            <h3 style="font-size:24px; margin-bottom:16px;">Respuesta rápida</h3>
            <p style="color:var(--text-muted); line-height:1.6;">Estudio de viabilidad ágil con las principales entidades bancarias (Santander, BBVA, Cetelem) para que tengas tu respuesta en menos de 24 horas.</p>
        </div>
        <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border);">
            <div style="font-size:40px; margin-bottom:20px;">📉</div>
            <h3 style="font-size:24px; margin-bottom:16px;">TIN desde 4.99%</h3>
            <p style="color:var(--text-muted); line-height:1.6;">Conseguimos de las mejores condiciones del mercado gracias a nuestros acuerdos directos de concesionario. Transparencia total sin comisiones ocultas.</p>
        </div>
    </div>
    
    <div style="margin-top: 60px; text-align: center; background: var(--bg-color); padding: 60px; border-radius: var(--radius-lg);">
        <h2 style="font-size:32px; margin-bottom:20px;">¿Hablamos sobre tu financiación?</h2>
        <p style="color:var(--text-muted); margin-bottom:30px;">Ponte en contacto con nuestro departamento de atención financiera para estudiar tu caso sin ningún tipo de compromiso.</p>
        <a href="{{ route('contact') }}" class="btn btn-primary" style="font-size:18px; padding:16px 32px;">Solicitar Estudio Financiero</a>
    </div>
</div>
@endsection
HTML;

foreach ($files as $name => $content) {
    file_put_contents($dir . '/' . $name, $content);
}

echo "Static pages generated.\n";
