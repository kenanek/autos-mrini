@extends('admin.layouts.app')
@section('title', 'Editar Vehículo')
@section('breadcrumb', 'Vehículos / Editar')
@section('content')

@foreach($vehicle->images as $img)
<form id="del-img-{{$img->id}}" action="{{ route('admin.vehicles.images.destroy', [$vehicle->id, $img->id]) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
@endforeach

<form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="page-header">
        <h1 class="page-title">Editar: {{ $vehicle->title }}</h1>
        <p class="page-subtitle">Modificando la información pública del anuncio.</p>
    </div>
    <div class="card" style="background: white; overflow: hidden;">
        @if ($errors->any())
        <div style="padding:15px; background:#ef4444; color:white; margin:15px; border-radius:8px;">
            <ul style="margin:0; padding-left:20px;">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
        @endif
        @include('admin.vehicles._form')
        <div style="padding: 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: flex-end; gap: 16px;">
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline" style="background:white; border: 1px solid #cbd5e1; color:#475569; padding:12px 24px;">Cancelar</a>
            <button type="submit" class="btn" style="background:#3b82f6; color:white; border:none; padding:12px 32px; font-weight:700;">Guardar Cambios</button>
        </div>
    </div>
</form>
@endsection