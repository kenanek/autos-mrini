@extends('admin.layouts.app')
@section('title', 'Vehículos')
@section('breadcrumb', 'Gestión / Vehículos')
@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title">Vehículos</h1>
        <p class="page-subtitle">Gestiona el inventario de coches de la plataforma.</p>
    </div>
    <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary" style="padding: 10px 24px;">
        <i class="icon-plus"></i> Añadir Vehículo
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 100px;">Imagen</th>
                    <th>Detalles del Vehículo</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
        <tbody>
            @foreach($vehicles as $v)
            <tr>
                <td style="width: 80px;">
                    @if($v->primary_image)
                        <img src="{{ asset('storage/'.$v->primary_image) }}" alt="Image" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                    @else
                        <div style="width:70px; height:50px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:20px; border: 1px dashed #cbd5e1;">
                            <i class="icon-image"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <div style="font-weight: 700; font-size: 15px; color: #1e293b; display:flex; align-items:center; gap:8px;">
                        {{ Str::limit($v->title, 40) }}
                        @if($v->is_featured) <span title="Destacado" style="color: #fbbf24; font-size: 14px;"><i class="icon-star"></i></span> @endif
                    </div>
                    <div style="font-size: 13px; color: #64748b; margin-top: 4px;">
                        {{ $v->brand->name }} &bull; {{ $v->year }} &bull; {{ number_format($v->mileage, 0, ',', '.') }} km
                    </div>
                </td>
                <td style="font-weight: 700; color: #10b981; font-size: 15px;">{{ $v->formatted_price }}</td>
                <td>
                    @if($v->status=='available') <span class="badge badge-success">Disponible</span>
                    @elseif($v->status=='sold') <span class="badge badge-danger">Vendido</span>
                    @else <span class="badge badge-warning">{{ ucfirst($v->status) }}</span> @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.vehicles.edit', $v->id) }}" class="btn btn-sm btn-outline" style="border-color: #cbd5e1; color: #475569; margin-right: 8px;">Editar</a>
                    <form action="{{ route('admin.vehicles.destroy', $v->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este vehículo?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:#ef4444; color:white; border:none; padding: 6px 12px; font-weight: 600;">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @if($vehicles->isEmpty())
        <div class="empty-state">
            <i class="icon-car"></i>
            <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 8px;">No hay vehículos</h3>
            <p style="font-size: 14px;">Aún no has añadido ningún vehículo a tu inventario catalogado.</p>
        </div>
    @endif
    @if($vehicles->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
        {{ $vehicles->links() }}
    </div>
    @endif
</div>
@endsection