@extends('admin.layouts.app')
@section('title', 'Editar Característica')
@section('breadcrumb', 'Gestión / Editar Característica')

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <h1 class="page-title">Editar Característica</h1>
    <p class="page-subtitle">Modifica los datos de la característica «{{ $feature->name }}».</p>
</div>

@if ($errors->any())
<div style="background:#fef2f2; color:#991b1b; padding:16px 20px; margin-bottom:24px; border-radius:12px; font-weight:500; border: 1px solid #fecaca;">
    <ul style="margin:0; padding-left:20px; font-size: 14px;">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
</div>
@endif

<form action="{{ route('admin.features.update', $feature->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card" style="overflow: hidden; background: white;">
        <div class="form-wrapper">
            <div class="form-section">
                <div class="form-section-header"><h3 class="form-section-title"><i class="icon-settings" style="color:#3b82f6;"></i> Datos de la Característica</h3></div>
                <div class="form-grid">
                    <div style="grid-column: 1 / -1;">
                        <label class="form-label">Nombre de la Característica <span style="color:#ef4444;">*</span></label>
                        <div class="input-wrapper"><input type="text" name="name" class="form-control" value="{{ old('name', $feature->name) }}" required></div>
                    </div>
                    <div>
                        <label class="form-label">Categoría</label>
                        <div class="input-wrapper"><input type="text" name="category" class="form-control" value="{{ old('category', $feature->category) }}" placeholder="Ej: Seguridad, Confort"></div>
                    </div>
                    <div>
                        <label class="form-label">Icono (opcional)</label>
                        <div class="input-wrapper"><input type="text" name="icon" class="form-control" value="{{ old('icon', $feature->icon ?? '') }}" placeholder="Clase de Lucide o FontAwesome"></div>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding: 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: flex-end; gap: 16px;">
            <a href="{{ route('admin.features.index') }}" class="btn btn-outline" style="background:white; border: 1px solid #cbd5e1; color:#475569; padding:12px 24px;">Cancelar</a>
            <button type="submit" class="btn btn-primary" style="padding:12px 32px;"><i class="icon-save" style="margin-right:8px;"></i> Actualizar Característica</button>
        </div>
    </div>
</form>
@endsection