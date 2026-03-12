@extends('admin.layouts.app')
@section('title', 'Estado del Correo')
@section('breadcrumb', 'Newsletter / Estado del Correo')
@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <h1 class="page-title">Estado del Correo</h1>
    <p class="page-subtitle">Verifica que la configuración SMTP está lista para enviar correos.</p>
</div>

<div class="card" style="padding: 28px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 16px; padding: 20px; border-radius: var(--radius-sm); {{ $isConfigured ? 'background: var(--success-light); border: 1px solid #a7f3d0;' : 'background: var(--warning-light); border: 1px solid #fcd34d;' }}">
        <i class="{{ $isConfigured ? 'icon-check-circle' : 'icon-alert-triangle' }}" style="font-size: 28px; {{ $isConfigured ? 'color: #065f46;' : 'color: #92400e;' }}"></i>
        <div>
            <div style="font-size: 16px; font-weight: 700; {{ $isConfigured ? 'color: #065f46;' : 'color: #92400e;' }}">
                {{ $isConfigured ? 'Correo Configurado' : 'SMTP No Configurado' }}
            </div>
            <div style="font-size: 14px; {{ $isConfigured ? 'color: #065f46;' : 'color: #92400e;' }}">
                {{ $statusMessage }}
            </div>
        </div>
    </div>
</div>

<div class="card" style="padding: 28px;">
    <h3 style="font-size: 16px; margin-bottom: 20px; color: var(--text-primary);">Variables de Configuración (.env)</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">
        Estas variables deben configurarse en el archivo <code>.env</code> de tu servidor para habilitar el envío de correos.
    </p>
    <table class="table">
        <thead><tr><th>Variable</th><th>Valor Actual</th></tr></thead>
        <tbody>
        @foreach($vars as $key => $val)
        <tr>
            <td><code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 13px;">{{ $key }}</code></td>
            <td style="font-size: 14px; {{ $val === '(vacío)' ? 'color: var(--danger);' : '' }}">{{ $val ?: '(vacío)' }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

    @if(!$isConfigured)
    <div style="margin-top: 24px; padding: 20px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius-sm);">
        <h4 style="font-size: 14px; color: #1e40af; margin-bottom: 12px;">Ejemplo de configuración .env</h4>
        <pre style="background: #1e293b; color: #e2e8f0; padding: 16px; border-radius: 8px; font-size: 13px; overflow-x: auto; line-height: 1.8;">MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_de_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="Autos Mrini"</pre>
    </div>
    @endif
</div>
@endsection
