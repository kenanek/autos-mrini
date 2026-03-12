@extends('layouts.public')
@section('title', 'Financiación a Medida - Autos Mrini')
@push('styles')
<style>
    .page-hero { padding: 80px 0 60px; background: var(--surface); border-bottom: 1px solid var(--border); text-align: center; }
    .page-hero h1 { font-size: 44px; margin-bottom: 16px; letter-spacing: -0.03em; }
    .page-hero p { font-size: 17px; color: var(--text-muted); max-width: 640px; margin: 0 auto; line-height: 1.6; }
    .fin-card { background: var(--surface); padding: 44px; border-radius: var(--radius-lg); border: 1px solid var(--border); transition: all 0.3s; text-align: center; }
    .fin-card:hover { border-color: var(--border-light); transform: translateY(-4px); }
    .fin-icon { width: 72px; height: 72px; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 24px; border-radius: 16px; }
    .fin-card h3 { font-size: 22px; margin-bottom: 14px; }
    .fin-card p { color: var(--text-muted); line-height: 1.7; font-size: 15px; }
    .cta-block { margin-top: 80px; background: var(--surface); border: 1px solid var(--border); padding: 80px; border-radius: var(--radius-xl); text-align: center; }
</style>
@endpush
@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Financiación a tu medida</h1>
        <p>En Autos Mrini hacemos que la compra de tu próximo coche sea fácil, cómoda y completamente adaptada a tus necesidades financieras.</p>
    </div>
</div>

<div class="container" style="padding: 80px 0;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px;">
        <div class="fin-card">
            <div class="fin-icon" style="background: rgba(139,92,246,0.1); color: var(--accent);"><i class="icon-percent"></i></div>
            <h3>100% Financiable</h3>
            <p>Podemos financiar hasta el 100% del valor del vehículo seleccionado sin necesidad de entrada, permitiéndote estrenar coche al instante.</p>
        </div>
        <div class="fin-card">
            <div class="fin-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;"><i class="icon-zap"></i></div>
            <h3>Respuesta Rápida</h3>
            <p>Estudio de viabilidad ágil con las principales entidades bancarias ({{ \App\Models\Setting::getVal('financing_page_text', 'Santander, BBVA, Cetelem') }}) para que tengas tu respuesta en menos de 24 horas.</p>
        </div>
        <div class="fin-card">
            <div class="fin-icon" style="background: rgba(16,185,129,0.1); color: #10b981;"><i class="icon-trending-down"></i></div>
            <h3>TIN desde 4.99%</h3>
            <p>Conseguimos las mejores condiciones del mercado gracias a nuestros acuerdos directos de concesionario. Transparencia total, sin comisiones ocultas.</p>
        </div>
    </div>
    
    <div class="cta-block">
        <h2 style="font-size:34px; margin-bottom:16px; letter-spacing:-0.03em;">¿Hablamos sobre tu financiación?</h2>
        <p style="color:var(--text-muted); margin-bottom:36px; font-size:16px; max-width:500px; margin-left:auto; margin-right:auto; line-height:1.6;">Ponte en contacto con nuestro departamento de atención financiera para estudiar tu caso sin ningún tipo de compromiso.</p>
        <a href="{{ route('contact') }}" class="btn btn-primary" style="font-size:16px; padding:16px 36px;">Solicitar Estudio Financiero</a>
    </div>
</div>
@endsection