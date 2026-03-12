@extends('admin.layouts.app')
@section('title', 'Gestión de Modelos')
@section('breadcrumb', 'Gestión / Modelos')
@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title">Modelos</h1>
        <p class="page-subtitle">Gestiona los modelos asociados a cada marca.</p>
    </div>
    <a href="{{ route('admin.models.create') }}" class="btn btn-primary" style="padding: 10px 24px;">
        <i class="icon-plus"></i> Añadir Modelo
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nombre del Modelo</th>
                    <th>Marca</th>
                    <th>Vehículos</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
        <tbody>
            @foreach($models as $model)
            <tr>
                <td style="font-weight: 700; color: #1e293b; font-size: 15px;">{{ $model->name }}</td>
                <td>
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: #f1f5f9; border-radius: 6px; font-weight: 600; color: #475569; font-size: 13px;">
                        {{ $model->brand->name }}
                    </span>
                </td>
                <td style="color: #64748b;">
                    <i class="icon-car" style="margin-right: 4px;"></i> {{ $model->vehicles_count ?? 0 }}
                </td>
                <td>@if($model->is_active) <span class="badge badge-success">Activo</span> @else <span class="badge badge-danger">Inactivo</span> @endif</td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.models.edit', $model->id) }}" class="btn btn-sm btn-outline" style="border-color: #cbd5e1; color: #475569; margin-right: 8px;">Editar</a>
                    <form action="{{ route('admin.models.destroy', $model->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este modelo?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444; color:white; border:none; padding: 6px 12px; font-weight: 600;">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @if($models->isEmpty())
        <div class="empty-state">
            <i class="icon-list"></i>
            <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 8px;">No hay modelos</h3>
            <p style="font-size: 14px;">Aún no has registrado ningún modelo de vehículo.</p>
        </div>
    @endif
    @if($models->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
        {{ $models->links() }}
    </div>
    @endif
</div>
@endsection