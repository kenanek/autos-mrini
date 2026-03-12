@extends('admin.layouts.app')
@section('title', 'Suscriptores')
@section('breadcrumb', 'Newsletter / Suscriptores')
@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title">Suscriptores</h1>
        <p class="page-subtitle">Gestiona los suscriptores del boletín.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 20px; text-align: center;">
        <div style="font-size: 28px; font-weight: 800; color: var(--primary);">{{ $stats['total'] }}</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Total</div>
    </div>
    <div class="card" style="padding: 20px; text-align: center;">
        <div style="font-size: 28px; font-weight: 800; color: var(--success);">{{ $stats['active'] }}</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Activos</div>
    </div>
    <div class="card" style="padding: 20px; text-align: center;">
        <div style="font-size: 28px; font-weight: 800; color: var(--danger);">{{ $stats['unsub'] }}</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Dados de baja</div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Lista de Suscriptores</h3></div>
    <div class="card-body">
        @if($subscribers->count() > 0)
        <table class="table">
            <thead><tr>
                <th>Email</th><th>Estado</th><th>Fecha</th><th style="width:80px;">Acciones</th>
            </tr></thead>
            <tbody>
            @foreach($subscribers as $s)
            <tr>
                <td><strong>{{ $s->email }}</strong></td>
                <td>
                    @if($s->status === 'active')
                        <span class="badge badge-success">Activo</span>
                    @else
                        <span class="badge badge-danger">Baja</span>
                    @endif
                </td>
                <td style="color:var(--text-muted); font-size:13px;">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.newsletter.subscribers.destroy', $s) }}" onsubmit="return confirm('¿Eliminar este suscriptor?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="color: var(--danger); background:var(--danger-light); border:none; padding:6px 10px; border-radius:6px; cursor:pointer; font-size:12px;">
                            <i class="icon-trash-2" style="font-size:13px;"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <div style="padding: 60px 20px; text-align: center; color: var(--text-muted);">
            <i class="icon-users" style="font-size: 32px; margin-bottom: 12px; display:block;"></i>
            <p>No hay suscriptores aún.</p>
        </div>
        @endif
    </div>
</div>
<div style="margin-top: 16px;">{{ $subscribers->links() }}</div>
@endsection
