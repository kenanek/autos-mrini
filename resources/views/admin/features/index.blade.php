@extends('admin.layouts.app')
@section('title', 'Características')
@section('breadcrumb', 'Gestión / Características')
@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title">Características</h1>
        <p class="page-subtitle">Gestiona las características y equipamiento de los vehículos.</p>
    </div>
    <a href="{{ route('admin.features.create') }}" class="btn btn-primary" style="padding: 10px 24px;">
        <i class="icon-plus"></i> Añadir Característica
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nombre de Característica</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
        <tbody>
            @foreach($features as $f)
            <tr>
                <td style="font-weight: 700; color: #1e293b; font-size: 15px;">{{ $f->name }}</td>
                <td>
                    <span style="display: inline-flex; align-items: center; padding: 4px 10px; background: #e0e7ff; color: #3730a3; border-radius: 6px; font-weight: 600; font-size: 12px; border: 1px solid #c7d2fe;">
                        {{ ucfirst($f->category ?? 'General') }}
                    </span>
                </td>
                <td>@if($f->is_active) <span class="badge badge-success">Activo</span> @else <span class="badge badge-danger">Inactivo</span> @endif</td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.features.edit', $f->id) }}" class="btn btn-sm btn-outline" style="border-color: #cbd5e1; color: #475569; margin-right: 8px;">Editar</a>
                    <form action="{{ route('admin.features.destroy', $f->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta característica?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444; color:white; border:none; padding: 6px 12px; font-weight: 600;">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @if($features->isEmpty())
        <div class="empty-state">
            <i class="icon-settings-2"></i>
            <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 8px;">No hay características</h3>
            <p style="font-size: 14px;">Aún no has registrado ningún equipamiento o característica.</p>
        </div>
    @endif
    @if($features->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
        {{ $features->links() }}
    </div>
    @endif
</div>
@endsection