@extends('admin.layouts.app')
@section('title', $campaign->exists ? 'Editar Campaña' : 'Nueva Campaña')
@section('breadcrumb', 'Newsletter / Campañas / ' . ($campaign->exists ? 'Editar' : 'Nueva'))
@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <h1 class="page-title">{{ $campaign->exists ? 'Editar Campaña' : 'Nueva Campaña' }}</h1>
    <p class="page-subtitle">{{ $subscriberCount }} suscriptores activos recibirán esta campaña.</p>
</div>

<form method="POST" action="{{ $campaign->exists ? route('admin.newsletter.campaign.update', $campaign) : route('admin.newsletter.campaign.store') }}">
    @csrf
    @if($campaign->exists) @method('PUT') @endif

    <div class="card" style="padding: 28px; margin-bottom: 20px;">
        <div style="margin-bottom: 20px;">
            <label class="form-label">Asunto del Email *</label>
            <input type="text" name="subject" class="form-control" value="{{ old('subject', $campaign->subject) }}" placeholder="Ej: Nuevas llegadas de septiembre" required>
            @error('subject') <span style="color:var(--danger); font-size:13px;">{{ $message }}</span> @enderror
        </div>
        <div style="margin-bottom: 20px;">
            <label class="form-label">Texto de Vista Previa</label>
            <input type="text" name="preview_text" class="form-control" value="{{ old('preview_text', $campaign->preview_text) }}" placeholder="Texto corto que aparece en la bandeja de entrada" maxlength="300">
        </div>
        <div style="margin-bottom: 20px;">
            <label class="form-label">Contenido del Email (HTML permitido) *</label>
            <textarea name="body" class="form-control" rows="15" placeholder="Escribe el contenido del email..." required style="font-family: monospace; font-size: 13px;">{{ old('body', $campaign->body) }}</textarea>
            @error('body') <span style="color:var(--danger); font-size:13px;">{{ $message }}</span> @enderror
            <p style="font-size:12px; color:var(--text-muted); margin-top:8px;">Puedes usar HTML. Variables disponibles: el link de cancelar suscripción se añade automáticamente al footer.</p>
        </div>
    </div>

    <div style="display: flex; gap: 12px;">
        <button type="submit" class="btn btn-primary" style="padding: 12px 32px;">
            <i class="icon-save" style="font-size:14px;"></i> {{ $campaign->exists ? 'Guardar Cambios' : 'Crear Borrador' }}
        </button>
        <a href="{{ route('admin.newsletter.campaigns') }}" class="btn btn-outline" style="padding: 12px 24px;">Cancelar</a>
    </div>
</form>
@endsection
