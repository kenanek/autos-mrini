@extends('admin.layouts.app')

@section('title', 'Editar Usuario')
@section('breadcrumb', 'Ajustes / Usuarios / Editar')

@section('content')
<div class="page-header">
    <h1 class="page-title">Actualizar Perfil y Permisos</h1>
    <p class="page-subtitle">Modificando la cuenta de {{ $user->name }}. Otorga o restringe acceso según las necesidades del equipo.</p>
</div>

@if ($errors->any())
<div style="background:#7f1d1d; color:#fca5a5; padding:16px; margin-bottom:24px; border-radius:12px; font-weight:500;">
    <ul style="margin:0; padding-left:20px;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card" style="overflow: hidden;">
        @include('admin.users._form')
        
        <div style="padding: 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: flex-end; gap: 16px;">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="background:white; border: 1px solid #cbd5e1; color:#475569; padding:12px 24px;">Descartar Cambios</a>
            <button type="submit" class="btn" style="background:#3b82f6; color:white; border:none; padding:12px 32px; font-weight:700; box-shadow: 0 4px 6px -1px rgba(59,130,246,.2);">Guardar Configuración</button>
        </div>
    </div>
</form>
@endsection
