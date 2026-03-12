@extends('admin.layouts.app')
@section('title', 'Detalle de Consulta')
@section('breadcrumb', 'Comunicación / Detalle de Consulta')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title">Detalle de Consulta</h1>
        <p class="page-subtitle">Mensaje recibido de {{ $inquiry->name }}.</p>
    </div>
    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline" style="background:white; border: 1px solid #cbd5e1; color:#475569; padding:10px 24px;">
        <i class="icon-arrow-left" style="margin-right:8px;"></i> Volver al Listado
    </a>
</div>

<div class="card" style="max-width: 900px; overflow: hidden; background: white;">
    <div style="padding: 32px;">
        <!-- Contact Info -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px; margin-bottom: 32px; padding-bottom: 32px; border-bottom: 1px solid #e2e8f0;">
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Nombre</div>
                <div style="font-size: 16px; font-weight: 700; color: #1e293b;">{{ $inquiry->name }}</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Email</div>
                <div style="font-size: 16px; font-weight: 500; color: #3b82f6;">{{ $inquiry->email }}</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Teléfono</div>
                <div style="font-size: 16px; font-weight: 500; color: #1e293b;">{{ $inquiry->phone ?? '—' }}</div>
            </div>
        </div>

        <!-- Meta Info -->
        <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px;">
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #eff6ff; color: #2563eb; border-radius: 8px; font-weight: 600; font-size: 13px; border: 1px solid #bfdbfe;">
                <i class="icon-tag"></i> {{ ucfirst($inquiry->type) }}
            </span>
            @if($inquiry->status=='new')
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #fef2f2; color: #dc2626; border-radius: 8px; font-weight: 600; font-size: 13px; border: 1px solid #fecaca;">Nuevo</span>
            @elseif($inquiry->status=='read')
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #fffbeb; color: #d97706; border-radius: 8px; font-weight: 600; font-size: 13px; border: 1px solid #fde68a;">Leído</span>
            @else
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #ecfdf5; color: #059669; border-radius: 8px; font-weight: 600; font-size: 13px; border: 1px solid #a7f3d0;">Respondido</span>
            @endif
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 500; font-size: 13px;">
                <i class="icon-calendar"></i> {{ $inquiry->created_at->format('d/m/Y H:i') }}
            </span>
        </div>

        <!-- Message Body -->
        <div style="padding: 28px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
            @if($inquiry->subject)
            <h4 style="margin-bottom: 16px; color: #1e293b; font-size: 18px; font-weight: 700;">{{ $inquiry->subject }}</h4>
            @endif
            <p style="color: #475569; line-height: 1.8; font-size: 15px; white-space: pre-wrap;">{{ $inquiry->message }}</p>
        </div>
    </div>

    <!-- Footer Actions -->
    <div style="padding: 20px 32px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
        <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este mensaje?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm" style="background:#ef4444; color:white; border:none; padding: 8px 18px; font-weight: 600;">
                <i class="icon-trash-2" style="margin-right:6px;"></i> Eliminar Mensaje
            </button>
        </form>
        @if($inquiry->email)
        <a href="mailto:{{ $inquiry->email }}" class="btn btn-primary" style="padding: 10px 24px;">
            <i class="icon-mail" style="margin-right:8px;"></i> Responder por Email
        </a>
        @endif
    </div>
</div>
@endsection