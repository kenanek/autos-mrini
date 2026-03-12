<?php

$premiumStyle = <<<'CSS'
<style>
    .form-wrapper { padding: 32px; display: flex; flex-direction: column; gap: 40px; }
    .form-section { border-bottom: 1px solid #e2e8f0; padding-bottom: 32px; }
    .form-section:last-child { border-bottom: none; padding-bottom: 0; }
    .form-section-header { margin-bottom: 24px; }
    .form-section-title { font-size: 18px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
    .form-section-desc { font-size: 14px; color: #64748b; margin-top: 0; }
    .form-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
    @media (min-width: 768px) { .form-grid { grid-template-columns: repeat(2, 1fr); } }
    .form-group-full { grid-column: 1 / -1; }
    .input-wrapper { position: relative; }
    .input-error { border-color: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important; }
    .error-msg { color: #ef4444; font-size: 12px; font-weight: 500; margin-top: 6px; display: block; }
</style>
CSS;

function createFormView($module, $varName, $isEdit)
{
    global $premiumStyle;
    $title = $isEdit ? 'Editar ' . ucfirst($module) : 'Añadir ' . ucfirst($module);
    $routeBase = 'admin.' . $module;
    $method = $isEdit ? "@method('PUT')" : "";
    $action = $isEdit ? "{{ route('$routeBase.update', \$$varName->id) }}" : "{{ route('$routeBase.store') }}";

    // Module specific fields
    $fieldsHTML = '';
    if ($module === 'brands') {
        $fieldsHTML = <<<HTML
            <div class="form-group-full">
                <label class="form-label" style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:#1e293b;">Nombre de la Marca *</label>
                <div class="input-wrapper">
                    <input type="text" name="name" class="form-control" style="width:100%;padding:12px;border-radius:8px;border:1px solid #e2e8f0;font-size:15px;" value="{{ old('name', (\$$varName->name ?? '')) }}" required>
                </div>
            </div>
            <div>
                <label class="form-label" style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:#1e293b;">País de Origen</label>
                <div class="input-wrapper">
                    <input type="text" name="country" class="form-control" style="width:100%;padding:12px;border-radius:8px;border:1px solid #e2e8f0;font-size:15px;" value="{{ old('country', (\$$varName->country ?? '')) }}">
                </div>
            </div>
            <div>
                <label class="form-label" style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:#1e293b;">Estado</label>
                <select name="is_active" class="form-control" style="width:100%;padding:12px;border-radius:8px;border:1px solid #e2e8f0;font-size:15px;background:white;">
                    <option value="1" @if(old('is_active', (\$$varName->is_active ?? 1)) == 1) selected @endif>Activo</option>
                    <option value="0" @if(old('is_active', (\$$varName->is_active ?? 1)) == 0) selected @endif>Inactivo</option>
                </select>
            </div>
HTML;
    }
    elseif ($module === 'models') {
        $fieldsHTML = <<<HTML
            <div class="form-group-full">
                <label class="form-label" style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:#1e293b;">Nombre del Modelo *</label>
                <div class="input-wrapper">
                    <input type="text" name="name" class="form-control" style="width:100%;padding:12px;border-radius:8px;border:1px solid #e2e8f0;font-size:15px;" value="{{ old('name', (\$$varName->name ?? '')) }}" required>
                </div>
            </div>
            <div class="form-group-full">
                <label class="form-label" style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:#1e293b;">Marca Requerida *</label>
                <select name="brand_id" class="form-control" style="width:100%;padding:12px;border-radius:8px;border:1px solid #e2e8f0;font-size:15px;background:white;" required>
                    <option value="">-- Seleccionar Marca --</option>
                    @foreach(\App\Models\Brand::all() as \$brand)
                        <option value="{{ \$brand->id }}" @if(old('brand_id', (\$$varName->brand_id ?? '')) == \$brand->id) selected @endif>{{ \$brand->name }}</option>
                    @endforeach
                </select>
            </div>
HTML;
    }
    elseif ($module === 'features') {
        $fieldsHTML = <<<HTML
            <div class="form-group-full">
                <label class="form-label" style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:#1e293b;">Nombre de la Característica *</label>
                <div class="input-wrapper">
                    <input type="text" name="name" class="form-control" style="width:100%;padding:12px;border-radius:8px;border:1px solid #e2e8f0;font-size:15px;" value="{{ old('name', (\$$varName->name ?? '')) }}" required>
                </div>
            </div>
            <div>
                <label class="form-label" style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:#1e293b;">Categoría</label>
                <div class="input-wrapper">
                    <input type="text" name="category" class="form-control" style="width:100%;padding:12px;border-radius:8px;border:1px solid #e2e8f0;font-size:15px;" value="{{ old('category', (\$$varName->category ?? 'General')) }}" placeholder="Ej: Seguridad, Confort">
                </div>
            </div>
            <div>
                <label class="form-label" style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:#1e293b;">Icono (opcional)</label>
                <div class="input-wrapper">
                    <input type="text" name="icon" class="form-control" style="width:100%;padding:12px;border-radius:8px;border:1px solid #e2e8f0;font-size:15px;" value="{{ old('icon', (\$$varName->icon ?? '')) }}" placeholder="Clase de FontAwesome o similar">
                </div>
            </div>
HTML;
    }

    return <<<BLADE
@extends('admin.layouts.app')
@section('title', '$title')
@section('breadcrumb', 'Gestión / $title')

@section('content')
$premiumStyle

<div class="page-header">
    <h1 class="page-title">$title</h1>
</div>

@if (\$errors->any())
<div style="background:#7f1d1d; color:#fca5a5; padding:16px; margin-bottom:24px; border-radius:12px; font-weight:500;">
    <ul style="margin:0; padding-left:20px;">
        @foreach (\$errors->all() as \$error) <li>{{\$error}}</li> @endforeach
    </ul>
</div>
@endif

<form action="$action" method="POST">
    @csrf $method
    <div class="card" style="overflow: hidden; background: white;">
        <div class="form-wrapper">
            <div class="form-section">
                <div class="form-section-header">
                    <h3 class="form-section-title">Información Básica</h3>
                </div>
                <!-- Inline style override to ensure stacking visually matches -->
                <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
$fieldsHTML
                </div>
            </div>
        </div>
        <div style="padding: 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: flex-end; gap: 16px;">
            <a href="{{ route('$routeBase.index') }}" class="btn btn-outline" style="background:white; border: 1px solid #cbd5e1; color:#475569; padding:12px 24px;">Cancelar</a>
            <button type="submit" class="btn" style="background:#3b82f6; color:white; border:none; padding:12px 32px; font-weight:700;">Guardar Datos</button>
        </div>
    </div>
</form>
@endsection
BLADE;
}

$replacements = [
    ['admin/brands/create.blade.php', "brands", "brand", false],
    ['admin/brands/edit.blade.php', "brands", "brand", true],
    ['admin/models/create.blade.php', "models", "carModel", false],
    ['admin/models/edit.blade.php', "models", "carModel", true],
    ['admin/features/create.blade.php', "features", "vehicleFeature", false],
    ['admin/features/edit.blade.php', "features", "vehicleFeature", true],
];

foreach ($replacements as $r) {
    $path = __DIR__ . "/resources/views/" . $r[0];
    file_put_contents($path, createFormView($r[1], $r[2], $r[3]));
}
echo "Forms upgraded successfully\n";
