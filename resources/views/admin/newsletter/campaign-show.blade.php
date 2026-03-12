@extends('admin.layouts.app')
@section('title', 'Campaña: ' . $campaign->subject)
@section('breadcrumb', 'Newsletter / Campañas / Detalle')
@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
    <div>
        <h1 class="page-title">{{ $campaign->subject }}</h1>
        <p class="page-subtitle">Creada el {{ $campaign->created_at->format('d/m/Y H:i') }}</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
        @if($campaign->status === 'draft')
            <span class="badge badge-info" style="font-size:13px; padding:8px 16px;">Borrador</span>
        @elseif($campaign->status === 'sent')
            <span class="badge badge-success" style="font-size:13px; padding:8px 16px;">Enviada {{ $campaign->sent_at?->format('d/m/Y H:i') }}</span>
        @elseif($campaign->status === 'sending')
            <span class="badge badge-warning" style="font-size:13px; padding:8px 16px;">Enviando…</span>
        @else
            <span class="badge badge-danger" style="font-size:13px; padding:8px 16px;">Fallida</span>
        @endif
    </div>
</div>

@if($campaign->total_recipients > 0)
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 20px; text-align: center;">
        <div style="font-size: 28px; font-weight: 800; color: var(--primary);">{{ $campaign->total_recipients }}</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Destinatarios</div>
    </div>
    <div class="card" style="padding: 20px; text-align: center;">
        <div style="font-size: 28px; font-weight: 800; color: var(--success);">{{ $campaign->sent_count }}</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Enviados</div>
    </div>
    <div class="card" style="padding: 20px; text-align: center;">
        <div style="font-size: 28px; font-weight: 800; color: var(--danger);">{{ $campaign->failed_count }}</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Fallidos</div>
    </div>
</div>
@endif

<div style="display: grid; grid-template-columns: 1fr 360px; gap: 24px;">
    {{-- Left: Content preview --}}
    <div>
        <div class="card" style="padding: 28px;">
            <h3 style="margin-bottom: 16px; font-size: 15px; color: var(--text-secondary);">Vista Previa del Contenido</h3>
            <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 24px; font-size: 14px; line-height: 1.7;">
                {!! $campaign->body !!}
            </div>
        </div>

        @if($logs->count() > 0)
        <div class="card" style="margin-top: 20px;">
            <div class="card-header"><h3 class="card-title">Registro de Envíos</h3></div>
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Email</th><th>Estado</th><th>Fecha</th><th>Error</th></tr></thead>
                    <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->subscriber->email ?? '—' }}</td>
                        <td>
                            @if($log->status === 'sent') <span class="badge badge-success">Enviado</span>
                            @else <span class="badge badge-danger">Fallido</span> @endif
                        </td>
                        <td style="font-size:13px; color:var(--text-muted);">{{ $log->sent_at?->format('H:i:s') }}</td>
                        <td style="font-size:12px; color:var(--danger);">{{ Str::limit($log->error, 60) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin-top: 12px;">{{ $logs->links() }}</div>
        @endif
    </div>

    {{-- Right: Actions --}}
    <div>
        @if($campaign->status === 'draft')
        <div class="card" style="padding: 24px; margin-bottom: 16px;">
            <h3 style="font-size: 15px; margin-bottom: 16px; color: var(--text-secondary);">Enviar Email de Prueba</h3>
            <form method="POST" action="{{ route('admin.newsletter.campaign.send-test', $campaign) }}">
                @csrf
                <input type="email" name="test_email" class="form-control" placeholder="tu@email.com" required style="margin-bottom: 12px;">
                <button type="submit" class="btn btn-outline" style="width:100%; padding: 10px;">
                    <i class="icon-send" style="font-size:14px;"></i> Enviar Prueba
                </button>
            </form>
        </div>

        <div class="card" style="padding: 24px; margin-bottom: 16px;">
            <h3 style="font-size: 15px; margin-bottom: 12px; color: var(--text-secondary);">Enviar Campaña</h3>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
                Se enviará a <strong>{{ \App\Models\Subscriber::active()->count() }}</strong> suscriptores activos.
            </p>
            <form method="POST" action="{{ route('admin.newsletter.campaign.send', $campaign) }}" onsubmit="return confirm('¿Enviar esta campaña a todos los suscriptores activos? Esta acción no se puede deshacer.')">
                @csrf
                <button type="submit" class="btn btn-primary" style="width:100%; padding: 12px;">
                    <i class="icon-send" style="font-size:14px;"></i> Enviar a Todos
                </button>
            </form>
        </div>

        <div class="card" style="padding: 24px;">
            <a href="{{ route('admin.newsletter.campaign.edit', $campaign) }}" class="btn btn-outline" style="width:100%; padding:10px; margin-bottom:10px;">
                <i class="icon-edit" style="font-size:14px;"></i> Editar Campaña
            </a>
            <form method="POST" action="{{ route('admin.newsletter.campaign.destroy', $campaign) }}" onsubmit="return confirm('¿Eliminar?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn" style="width:100%; padding:10px; color:var(--danger); background:var(--danger-light); border:1px solid #fecaca; border-radius:var(--radius-sm); cursor:pointer;">
                    <i class="icon-trash-2" style="font-size:14px;"></i> Eliminar
                </button>
            </form>
        </div>
        @else
        <div class="card" style="padding: 24px;">
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Esta campaña ya fue enviada y no se puede modificar.</p>
            <a href="{{ route('admin.newsletter.campaigns') }}" class="btn btn-outline" style="width:100%; padding:10px;">
                ← Volver a Campañas
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
