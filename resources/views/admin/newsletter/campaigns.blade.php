@extends('admin.layouts.app')
@section('title', 'Campañas')
@section('breadcrumb', 'Newsletter / Campañas')
@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title">Campañas</h1>
        <p class="page-subtitle">Crea y envía campañas de email a tus suscriptores.</p>
    </div>
    <a href="{{ route('admin.newsletter.campaign.create') }}" class="btn btn-primary" style="padding: 10px 24px;">
        <i class="icon-plus"></i> Nueva Campaña
    </a>
</div>

@if(!$mailReady)
<div style="background: var(--warning-light); color: #92400e; padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 14px; border: 1px solid #fcd34d; display:flex; align-items:center; gap:10px;">
    <i class="icon-alert-triangle" style="font-size:18px;"></i>
    <span><strong>SMTP no configurado.</strong> Puedes crear campañas como borrador, pero no podrás enviarlas hasta configurar el correo. <a href="{{ route('admin.newsletter.mail-status') }}" style="color: #92400e; text-decoration: underline;">Ver estado del correo</a></span>
</div>
@endif

<div class="card">
    <div class="card-body">
        @if($campaigns->count() > 0)
        <table class="table">
            <thead><tr>
                <th>Asunto</th><th>Estado</th><th>Enviados</th><th>Fecha</th><th style="width:120px;">Acciones</th>
            </tr></thead>
            <tbody>
            @foreach($campaigns as $c)
            <tr>
                <td><a href="{{ route('admin.newsletter.campaign.show', $c) }}" style="color:var(--primary); font-weight:600;">{{ $c->subject }}</a></td>
                <td>
                    @if($c->status === 'draft') <span class="badge badge-info">Borrador</span>
                    @elseif($c->status === 'sending') <span class="badge badge-warning">Enviando…</span>
                    @elseif($c->status === 'sent') <span class="badge badge-success">Enviada</span>
                    @else <span class="badge badge-danger">Fallida</span>
                    @endif
                </td>
                <td>
                    @if($c->total_recipients > 0)
                        <span style="color:var(--success); font-weight:600;">{{ $c->sent_count }}</span> / {{ $c->total_recipients }}
                        @if($c->failed_count > 0) <span style="color:var(--danger); font-size:12px;">({{ $c->failed_count }} fallidos)</span> @endif
                    @else
                        <span style="color:var(--text-muted);">—</span>
                    @endif
                </td>
                <td style="color:var(--text-muted); font-size:13px;">{{ $c->created_at->format('d/m/Y') }}</td>
                <td>
                    <div style="display:flex; gap:6px;">
                        <a href="{{ route('admin.newsletter.campaign.show', $c) }}" class="btn btn-sm btn-outline" style="padding:6px 10px;"><i class="icon-eye" style="font-size:13px;"></i></a>
                        @if($c->status === 'draft')
                        <a href="{{ route('admin.newsletter.campaign.edit', $c) }}" class="btn btn-sm btn-outline" style="padding:6px 10px;"><i class="icon-edit" style="font-size:13px;"></i></a>
                        @endif
                        <form method="POST" action="{{ route('admin.newsletter.campaign.destroy', $c) }}" onsubmit="return confirm('¿Eliminar esta campaña?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="color:var(--danger); background:var(--danger-light); border:none; padding:6px 10px; border-radius:6px; cursor:pointer;">
                                <i class="icon-trash-2" style="font-size:13px;"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <div style="padding: 60px 20px; text-align: center; color: var(--text-muted);">
            <i class="icon-send" style="font-size: 32px; margin-bottom: 12px; display:block;"></i>
            <p>No hay campañas aún. <a href="{{ route('admin.newsletter.campaign.create') }}" style="color:var(--primary);">Crear primera campaña</a></p>
        </div>
        @endif
    </div>
</div>
<div style="margin-top: 16px;">{{ $campaigns->links() }}</div>
@endsection
