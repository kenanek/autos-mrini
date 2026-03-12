@extends('admin.layouts.app')

@section('title', 'Ajustes de Portada')
@section('breadcrumb', 'Ajustes de Portada')

@push('styles')
<style>
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .media-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        position: relative;
        cursor: grab;
    }
    .media-card:active {
        cursor: grabbing;
    }
    .media-preview {
        aspect-ratio: 16/9;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .media-preview img, .media-preview video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .media-actions {
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--surface);
    }
    .badge-type {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0,0,0,0.6);
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        backdrop-filter: blur(4px);
    }
    .drag-handle {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255,255,255,0.9);
        color: #000;
        width: 28px; height: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')
<div class="page-header flex justify-between align-center">
    <div>
        <h1 class="page-title">Ajustes de Portada</h1>
        <div class="page-subtitle">Gestiona las imágenes y vídeos del hero de la página principal.</div>
    </div>
</div>

<div class="card" style="margin-bottom: 30px;">
    <h3>Subir nuevo archivo</h3>
    <form action="{{ route('admin.hero-media.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 16px; display: flex; gap: 16px; align-items: flex-end;">
        @csrf
        <div style="flex: 1;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Archivo (JPG, PNG, WEBP, MP4, WEBM)</label>
            <input type="file" name="file" required class="form-control" accept="image/*,video/mp4,video/webm">
        </div>
        <button type="submit" class="btn btn-primary"><i class="icon-upload"></i> Subir</button>
    </form>
    @error('file')
        <p style="color: #ef4444; font-size: 13px; margin-top: 8px;">{{ $message }}</p>
    @enderror
</div>

<div class="card">
    <h3>Archivos Actuales</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Arrastra las tarjetas para reordenarlas.</p>
    
    @if($media->count() > 0)
        <div class="media-grid" id="sortable-grid">
            @foreach($media as $item)
                <div class="media-card" data-id="{{ $item->id }}">
                    <div class="badge-type"><i class="{{ $item->type == 'video' ? 'icon-video' : 'icon-image' }}"></i> {{ $item->type }}</div>
                    <div class="drag-handle"><i class="icon-move"></i></div>
                    <div class="media-preview">
                        @if($item->type == 'video')
                            <video src="{{ asset('storage/' . $item->file_path) }}" muted loop></video>
                        @else
                            <img src="{{ asset('storage/' . $item->file_path) }}" alt="Hero">
                        @endif
                    </div>
                    <div class="media-actions">
                        <form action="{{ route('admin.hero-media.toggle', $item) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $item->is_active ? 'btn-primary' : '' }}" style="padding: 6px 12px; font-size: 12px;">
                                {{ $item->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.hero-media.destroy', $item) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este archivo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="color: #ef4444; border: 1px solid #fca5a5; background: #fef2f2; padding: 6px 12px; font-size: 12px;">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="icon-image"></i>
            <h3>Sin Archivos</h3>
            <p>Sube el primer archivo de portada usando el formulario superior.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var grid = document.getElementById('sortable-grid');
        if (grid) {
            new Sortable(grid, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: function () {
                    var order = [];
                    grid.querySelectorAll('.media-card').forEach(function(card) {
                        order.push(card.getAttribute('data-id'));
                    });
                    
                    fetch("{{ route('admin.hero-media.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({order: order})
                    });
                }
            });
        }
    });
</script>
@endpush
