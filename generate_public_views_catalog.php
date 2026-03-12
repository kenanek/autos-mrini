<?php

$dir = __DIR__ . '/resources/views/vehicles';
if (!is_dir($dir))
    mkdir($dir, 0755, true);

$files = [];

$files['index.blade.php'] = <<<'HTML'
@extends('layouts.public')
@section('title', 'Todos los Vehículos')
@push('styles')
<style>
    .k-header { padding: 60px 0; background: var(--primary); color: white; margin-bottom: 40px; }
    .k-header h1 { font-size: 40px; margin-bottom: 10px; color: white; }
    .k-layout { display: grid; grid-template-columns: 300px 1fr; gap: 40px; }
    .k-filters { background: white; padding: 24px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); align-self: start; position: sticky; top: 100px; }
    .k-filters h3 { font-size: 18px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px; }
    .fg { margin-bottom: 16px; }
    .fg label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: var(--primary-light); }
    .fc { width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; }
    .vehicle-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; }
    @media (max-width: 900px) {
        .k-layout { grid-template-columns: 1fr; }
        .k-filters { position: static; }
    }
</style>
@endpush

@section('content')
<div class="k-header">
    <div class="container">
        <h1>Nuestros Vehículos</h1>
        <p style="color:#cbd5e1; font-size:16px;">Descubre nuestro inventario premium de coches de ocasión.</p>
    </div>
</div>

<div class="container">
    <div class="k-layout">
        <aside class="k-filters">
            <h3>Filtros</h3>
            <form action="{{ route('vehicles.index') }}" method="GET">
                <div class="fg">
                    <label>Marca</label>
                    <select name="brand_id" class="fc">
                        <option value="">Todas</option>
                        @foreach($brands as $b) <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option> @endforeach
                    </select>
                </div>
                <div class="fg">
                    <label>Combustible</label>
                    <select name="fuel_type" class="fc">
                        <option value="">Todos</option>
                        <option value="gasoline" {{ request('fuel_type')=='gasoline' ? 'selected' : '' }}>Gasolina</option>
                        <option value="diesel" {{ request('fuel_type')=='diesel' ? 'selected' : '' }}>Diésel</option>
                        <option value="hybrid" {{ request('fuel_type')=='hybrid' ? 'selected' : '' }}>Híbrido</option>
                        <option value="electric" {{ request('fuel_type')=='electric' ? 'selected' : '' }}>Eléctrico</option>
                    </select>
                </div>
                <div class="fg">
                    <label>Transmisión</label>
                    <select name="transmission" class="fc">
                        <option value="">Todas</option>
                        <option value="manual" {{ request('transmission')=='manual' ? 'selected' : '' }}>Manual</option>
                        <option value="automatic" {{ request('transmission')=='automatic' ? 'selected' : '' }}>Automático</option>
                    </select>
                </div>
                <div class="fg">
                    <label>Precio Máximo</label>
                    <select name="price_max" class="fc">
                        <option value="">Sin límite</option>
                        @foreach([10000, 20000, 30000, 50000, 80000, 100000] as $p)
                            <option value="{{ $p }}" {{ request('price_max') == $p ? 'selected' : '' }}>{{ number_format($p, 0, ',', '.') }} €</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;">Aplicar Filtros</button>
                <a href="{{ route('vehicles.index') }}" class="btn btn-outline" style="width:100%; margin-top:10px; text-align:center;">Limpiar</a>
            </form>
        </aside>
        
        <main>
            <div style="margin-bottom: 20px; font-weight: 500; color: var(--text-muted);">
                Mostrando {{ $vehicles->firstItem() ?? 0 }} - {{ $vehicles->lastItem() ?? 0 }} de {{ $vehicles->total() }} vehículos
            </div>
            
            @if($vehicles->count() > 0)
                <div class="vehicle-grid">
                    @foreach($vehicles as $vehicle)
                        @include('components.vehicle-card', ['vehicle' => $vehicle])
                    @endforeach
                </div>
                <div style="margin-top: 40px;">
                    {{ $vehicles->links() }}
                </div>
            @else
                <div style="background: white; padding: 40px; text-align: center; border-radius: var(--radius-lg); border: 1px solid var(--border);">
                    <h3 style="margin-bottom: 10px; font-size: 20px;">No se encontraron vehículos</h3>
                    <p style="color: var(--text-muted);">Intenta ajustar los filtros para ver más resultados.</p>
                </div>
            @endif
        </main>
    </div>
</div>
<br><br>
@endsection
HTML;

$files['show.blade.php'] = <<<'HTML'
@extends('layouts.public')
@section('title', $vehicle->title . ' - Autos Mrini')
@push('styles')
<style>
    .v-header { padding: 40px 0; background: var(--bg-color); border-bottom: 1px solid var(--border); }
    .v-title-row { display: flex; justify-content: space-between; align-items: flex-end; gap: 20px; flex-wrap: wrap; }
    .v-title { font-size: 36px; margin-bottom: 8px; }
    .v-price { font-size: 40px; font-weight: 700; color: var(--accent); font-family: 'Outfit'; }
    .v-old-price { font-size: 20px; text-decoration: line-through; color: var(--text-muted); margin-right: 12px; }
    
    .v-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin-top: 40px; margin-bottom: 80px; }
    
    .v-gallery { background: #000; border-radius: var(--radius-lg); overflow: hidden; position: relative; }
    .v-main-img { width: 100%; aspect-ratio: 16/10; object-fit: cover; display: block; }
    .v-thumbs { display: flex; gap: 10px; padding: 10px; background: #111; overflow-x: auto; }
    .v-thumb { height: 80px; width: 120px; object-fit: cover; border-radius: 4px; cursor: pointer; opacity: 0.6; transition: 0.2s; }
    .v-thumb:hover, .v-thumb.active { opacity: 1; border: 2px solid var(--accent); }
    
    .v-box { background: white; padding: 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--border); margin-bottom: 30px; }
    .v-box h3 { font-size: 20px; margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 12px; }
    
    .v-specs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 40px; }
    .v-spec { display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border); padding-bottom: 8px; }
    .v-spec-l { color: var(--text-muted); font-size: 14px; }
    .v-spec-v { font-weight: 600; font-size: 14px; color: var(--primary); text-align: right;}
    
    .v-features { display: flex; flex-wrap: wrap; gap: 10px; }
    .v-feat { background: var(--bg-color); border: 1px solid var(--border); padding: 8px 16px; border-radius: 999px; font-size: 14px; font-weight: 500; }
    
    .v-contact-box { background: var(--primary); color: white; padding: 32px; border-radius: var(--radius-lg); position: sticky; top: 100px; }
    .v-contact-box h3 { border-color: rgba(255,255,255,0.1); color: white; }
    
    @media (max-width: 900px) {
        .v-layout { grid-template-columns: 1fr; }
        .v-specs-grid { grid-template-columns: 1fr; }
        .v-contact-box { position: static; }
    }
</style>
@endpush

@section('content')
<div class="v-header">
    <div class="container v-title-row">
        <div>
            <div style="font-weight:600; color:var(--accent); font-size:14px; text-transform:uppercase; margin-bottom:4px;">{{ $vehicle->brand->name }} &bull; {{ $vehicle->carModel->name }}</div>
            <h1 class="v-title">{{ $vehicle->title }}</h1>
            <div style="color:var(--text-muted);">{{ $vehicle->year }} &bull; {{ number_format($vehicle->mileage, 0, ',', '.') }} km &bull; {{ $vehicle->transmission == 'automatic' ? 'Automático' : 'Manual' }}</div>
        </div>
        <div>
            <div class="v-price">
                @if($vehicle->old_price > 0) <span class="v-old-price">{{ number_format($vehicle->old_price, 0, ',', '.') }} €</span> @endif
                {{ number_format($vehicle->price, 0, ',', '.') }} €
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="v-layout">
        <div>
            <!-- Gallery -->
            <div class="v-gallery">
                @if($vehicle->images->count() > 0)
                    <img src="{{ asset('storage/' . $vehicle->images->first()->image_path) }}" class="v-main-img" id="mainImage">
                    <div class="v-thumbs">
                        @foreach($vehicle->images as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="v-thumb" onclick="document.getElementById('mainImage').src=this.src;">
                        @endforeach
                    </div>
                @else
                    <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800&auto=format&fit=crop" class="v-main-img">
                @endif
            </div>

            <!-- Desc -->
            @if($vehicle->description)
            <div class="v-box" style="margin-top:40px;">
                <h3>Descripción</h3>
                <div style="line-height:1.7; color:var(--text-main);">
                    {!! nl2br(e($vehicle->description)) !!}
                </div>
            </div>
            @endif

            <!-- Tech Specs -->
            <div class="v-box">
                <h3>Resumen Técnico</h3>
                <div class="v-specs-grid">
                    <div class="v-spec"><span class="v-spec-l">Marca</span><span class="v-spec-v">{{ $vehicle->brand->name }}</span></div>
                    <div class="v-spec"><span class="v-spec-l">Modelo</span><span class="v-spec-v">{{ $vehicle->carModel->name }}</span></div>
                    <div class="v-spec"><span class="v-spec-l">Año</span><span class="v-spec-v">{{ $vehicle->year }}</span></div>
                    <div class="v-spec"><span class="v-spec-l">Kilómetros</span><span class="v-spec-v">{{ number_format($vehicle->mileage, 0, ',', '.') }} km</span></div>
                    <div class="v-spec"><span class="v-spec-l">Combustible</span><span class="v-spec-v">{{ ucfirst($vehicle->fuel_type) }}</span></div>
                    <div class="v-spec"><span class="v-spec-l">Transmisión</span><span class="v-spec-v">{{ ucfirst($vehicle->transmission) }}</span></div>
                    @if($vehicle->horsepower)<div class="v-spec"><span class="v-spec-l">Potencia</span><span class="v-spec-v">{{ $vehicle->horsepower }} cv</span></div>@endif
                    @if($vehicle->engine_size)<div class="v-spec"><span class="v-spec-l">Motor</span><span class="v-spec-v">{{ $vehicle->engine_size }} cm³</span></div>@endif
                    @if($vehicle->doors)<div class="v-spec"><span class="v-spec-l">Puertas</span><span class="v-spec-v">{{ $vehicle->doors }}</span></div>@endif
                    @if($vehicle->seats)<div class="v-spec"><span class="v-spec-l">Plazas</span><span class="v-spec-v">{{ $vehicle->seats }}</span></div>@endif
                    @if($vehicle->color)<div class="v-spec"><span class="v-spec-l">Color ext.</span><span class="v-spec-v">{{ $vehicle->color }}</span></div>@endif
                    
                    @foreach($vehicle->customAttributes as $attr)
                    <div class="v-spec"><span class="v-spec-l">{{ $attr->name }}</span><span class="v-spec-v">{{ $attr->pivot->value }} {{ $attr->unit }}</span></div>
                    @endforeach
                </div>
            </div>

            <!-- Features -->
            @if($vehicle->features->count() > 0)
            <div class="v-box">
                <h3>Equipamiento</h3>
                <div class="v-features">
                    @foreach($vehicle->features as $f)
                        <span class="v-feat">✓ {{ $f->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div>
            <!-- Contact Sidebar -->
            <div class="v-contact-box">
                <h3>¿Te interesa este coche?</h3>
                <p style="color:#cbd5e1; font-size:14px; margin-bottom:24px;">Contáctanos ahora y asegura esta oportunidad.</p>
                
                @if(session('success'))
                    <div style="background:#10b981; color:white; padding:12px; border-radius:6px; margin-bottom:20px; font-size:14px; text-align:center;">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="vehicle">
                    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                    <input type="hidden" name="subject" value="Interés en: {{ $vehicle->title }}">
                    
                    <div style="margin-bottom:16px;">
                        <input type="text" name="name" required placeholder="Tu Nombre" style="width:100%; padding:12px; border:none; border-radius:6px; font-family:inherit;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <input type="email" name="email" required placeholder="Tu Email" style="width:100%; padding:12px; border:none; border-radius:6px; font-family:inherit;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <input type="text" name="phone" placeholder="Teléfono" style="width:100%; padding:12px; border:none; border-radius:6px; font-family:inherit;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <textarea name="message" required placeholder="Me gustaría recibir más información sobre..." style="width:100%; padding:12px; border:none; border-radius:6px; font-family:inherit; min-height:100px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; background:white; color:var(--primary); box-shadow:none;">Enviar Mensaje</button>
                    
                    <a href="https://wa.me/34954000000?text=Hola,%20me%20interesa%20el%20vehículo%20{{ urlencode($vehicle->title) }}" target="_blank" class="btn" style="width:100%; margin-top:12px; background:#25D366; color:white;">WhatsApp Directo</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
HTML;

foreach ($files as $name => $content) {
    file_put_contents($dir . '/' . $name, $content);
}

echo "Vehicle catalog views generated.\n";
