@extends('admin.layouts.app')
@section('title', 'Gestión de Marcas')
@section('breadcrumb', 'Gestión / Marcas')
@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title">Marcas</h1>
        <p class="page-subtitle">Gestiona las marcas de vehículos disponibles.</p>
    </div>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary" style="padding: 10px 24px;">
        <i class="icon-plus"></i> Añadir Marca
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nombre de Marca</th>
                    <th>País de Origen</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
        <tbody>
            @foreach($brands as $brand)
            <tr>
                <td style="font-weight: 700; color: #1e293b; font-size: 15px;">{{ $brand->name }}</td>
                <td style="color: #64748b;">{{ $brand->country ?? '—' }}</td>
                <td>@if($brand->is_active) <span class="badge badge-success">Activo</span> @else <span class="badge badge-danger">Inactivo</span> @endif</td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-outline" style="border-color: #cbd5e1; color: #475569; margin-right: 8px;">Editar</a>
                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta marca?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444; color:white; border:none; padding: 6px 12px; font-weight: 600;">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @if($brands->isEmpty())
        <div class="empty-state">
            <i class="icon-tags"></i>
            <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 8px;">No hay marcas</h3>
            <p style="font-size: 14px;">Aún no has registrado ninguna marca de vehículos.</p>
        </div>
    @endif
    @if($brands->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
        {{ $brands->links() }}
    </div>
    @endif
</div>
@endsection