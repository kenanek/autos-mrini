@extends('admin.layouts.app')
@section('title', 'Editar Marca')
@section('breadcrumb', 'Gestión / Editar Marca')

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <h1 class="page-title">Editar Marca</h1>
    <p class="page-subtitle">Modifica los datos de la marca «{{ $brand->name }}».</p>
</div>

@if ($errors->any())
<div style="background:#fef2f2; color:#991b1b; padding:16px 20px; margin-bottom:24px; border-radius:12px; font-weight:500; border: 1px solid #fecaca;">
    <ul style="margin:0; padding-left:20px; font-size: 14px;">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
</div>
@endif

<form action="{{ route('admin.brands.update', $brand->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card" style="overflow: hidden; background: white;">
        <div class="form-wrapper">
            <div class="form-section">
                <div class="form-section-header"><h3 class="form-section-title"><i class="icon-tag" style="color:#3b82f6;"></i> Información de la Marca</h3></div>
                <div class="form-grid">
                    <div style="grid-column: 1 / -1;">
                        <label class="form-label">Nombre de la Marca <span style="color:#ef4444;">*</span></label>
                        <div class="input-wrapper"><input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}" required></div>
                    </div>
                    <div>
                        <label class="form-label">País de Origen</label>
                        <div class="input-wrapper"><input type="text" name="country" class="form-control" value="{{ old('country', $brand->country) }}" placeholder="Ej: Alemania, Japón"></div>
                    </div>
                    <div>
                        <label class="form-label">Estado</label>
                        <select name="is_active" class="form-control">
                            <option value="1" @if(old('is_active', $brand->is_active) == 1) selected @endif>Activo</option>
                            <option value="0" @if(old('is_active', $brand->is_active) == 0) selected @endif>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding: 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: flex-end; gap: 16px;">
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline" style="background:white; border: 1px solid #cbd5e1; color:#475569; padding:12px 24px;">Cancelar</a>
            <button type="submit" class="btn btn-primary" style="padding:12px 32px;"><i class="icon-save" style="margin-right:8px;"></i> Actualizar Marca</button>
        </div>
    </div>
</form>
@endsection