@extends('admin.layouts.app')
@section('title', 'Consultas de Clientes')
@section('breadcrumb', 'Comunicación / Consultas')
@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title">Consultas Entrantes</h1>
        <p class="page-subtitle">Gestiona los mensajes de contacto de clientes potenciales.</p>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Contacto</th>
                    <th>Asunto / Mensaje</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
        <tbody>
            @foreach($inquiries as $i)
            <tr style="{{ $i->status=='new' ? 'background:#f8fafc;' : '' }}">
                <td>
                    <div style="font-weight: 700; color: #1e293b; font-size: 14px;">{{ $i->name }}</div>
                    <div style="font-size: 13px; color: #64748b;">{{ $i->email }}</div>
                </td>
                <td style="color: #475569; font-size: 14px;">{{ Str::limit($i->subject ?? $i->message, 50) }}</td>
                <td><span class="badge badge-info">{{ ucfirst($i->type) }}</span></td>
                <td>
                    @if($i->status=='new') <span class="badge badge-danger">Nuevo</span>
                    @elseif($i->status=='read') <span class="badge badge-warning">Leído</span>
                    @else <span class="badge badge-success">Respondido</span> @endif
                </td>
                <td style="color: #64748b; font-size: 13px;">{{ $i->created_at->format('d/m/Y H:i') }}</td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.inquiries.show', $i->id) }}" class="btn btn-sm btn-outline" style="border-color: #cbd5e1; color: #475569; margin-right: 8px;">Ver Mensaje</a>
                    <form action="{{ route('admin.inquiries.destroy', $i->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este mensaje?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444; color:white; border:none; padding: 6px 12px; font-weight: 600;">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @if($inquiries->isEmpty())
        <div class="empty-state">
            <i class="icon-mail"></i>
            <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 8px;">Bandeja vacía</h3>
            <p style="font-size: 14px;">No tienes ningún mensaje ni consulta actualmente.</p>
        </div>
    @endif
    @if($inquiries->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
        {{ $inquiries->links() }}
    </div>
    @endif
</div>
@endsection