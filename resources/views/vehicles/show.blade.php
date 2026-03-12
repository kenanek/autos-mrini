@extends('layouts.public')
@section('title', $vehicle->title . ' - Autos Mrini')
@push('styles')
<style>
    .v-header { padding: 40px 0; background: var(--surface); border-bottom: 1px solid var(--border); }
    .v-title-row { display: flex; justify-content: space-between; align-items: flex-end; gap: 20px; flex-wrap: wrap; }
    .v-title { font-size: 32px; margin-bottom: 8px; }
    .v-price { font-size: 36px; font-weight: 700; color: var(--accent); font-family: 'Outfit'; }
    .v-old-price { font-size: 18px; text-decoration: line-through; color: var(--text-muted); margin-right: 12px; }
    
    .v-app-layout { padding-bottom: 40px; }
    
    /* App Gallery */
    .v-gallery-section { max-width: 1200px; margin: 32px auto 0; padding: 0 20px; }
    .v-gallery { background: #000; border-radius: var(--radius-xl); overflow: hidden; border: 1px solid var(--border); box-shadow: var(--shadow); position: relative; }
    .v-main-img { width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block; transition: transform 0.3s ease; }
    
    .v-thumbs { display: flex; gap: 8px; padding: 12px; background: var(--surface); overflow-x: auto; scrollbar-width: none; }
    .v-thumbs::-webkit-scrollbar { display: none; }
    .v-thumb { height: 70px; min-width: 100px; object-fit: cover; border-radius: 8px; cursor: pointer; opacity: 0.5; transition: 0.2s; border: 2px solid transparent; box-shadow: var(--shadow-sm); }
    .v-thumb:hover, .v-thumb.active { opacity: 1; border-color: var(--accent); }
    
    /* App Header (Title and Price) */
    .v-app-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
    .v-header-section { padding: 28px 0 24px; display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; border-bottom: 1px solid var(--border); margin-bottom: 24px; }
    .v-title-meta { font-weight: 700; color: var(--accent); font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px; }
    .v-title { font-size: 32px; font-weight: 800; color: var(--text-bright); line-height: 1.2; margin-bottom: 16px; letter-spacing: -0.02em; }
    .v-price-block { text-align: right; flex-shrink: 0; }
    .v-price { font-size: 38px; font-weight: 800; color: var(--accent); font-family: 'Outfit', sans-serif; line-height: 1; }
    .v-old-price { font-size: 16px; text-decoration: line-through; color: var(--text-muted); display: block; margin-bottom: 6px; }
    
    /* Quick Specs row below title */
    .v-quick-specs { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 0; }
    .v-quick-badge { background: var(--primary-light); border: 1px solid var(--border); padding: 7px 14px; border-radius: 99px; font-size: 13px; font-weight: 600; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; white-space: nowrap; }
    
    /* Grid Layout */
    .v-app-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }
    
    .v-box { background: var(--surface); padding: 32px; border-radius: var(--radius-xl); border: 1px solid var(--border); margin-bottom: 24px; box-shadow: var(--shadow-sm); }
    .v-box h3 { font-size: 20px; margin-bottom: 24px; color: var(--text-bright); font-weight: 800; display: flex; align-items: center; gap: 10px; }
    
    .v-specs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 32px; }
    .v-spec { display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 12px; align-items: center; }
    .v-spec-l { color: var(--text-muted); font-size: 14px; }
    .v-spec-v { font-weight: 700; font-size: 14px; color: var(--text-bright); text-align: right; }
    
    .v-features { display: flex; flex-wrap: wrap; gap: 10px; }
    .v-feat { background: var(--primary); border: 1px solid var(--border); padding: 10px 18px; border-radius: 12px; font-size: 14px; font-weight: 600; color: var(--text-main); display: inline-flex; align-items: center; gap: 8px; }
    
    .v-contact-box { position: sticky; top: 100px; padding: 32px; }
    .v-contact-box input, .v-contact-box textarea { width: 100%; padding: 14px 16px; background: var(--primary-light); border: 1px solid var(--border); border-radius: 12px; font-family: inherit; color: var(--text-main); font-size: 15px; margin-bottom: 16px; transition: all 0.2s; }
    .v-contact-box input:focus, .v-contact-box textarea:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 3px var(--accent-glow); }
    .v-contact-box .btn-primary { padding: 16px; border-radius: 12px; font-size: 16px; }
    
    .cta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 24px; }
    .cta-btn { display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 700; padding: 14px; border-radius: 12px; border: none; font-size: 15px; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; color: white; cursor: pointer; }
    .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.2); }
    .cta-whatsapp { background: #25D366; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3); }
    .cta-call { background: #007AFF; box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3); }
    
    .mobile-sticky-cta {
        display: none; position: fixed; bottom: 0; left: 0; right: 0; background: var(--surface); padding: 16px 20px; padding-bottom: calc(16px + env(safe-area-inset-bottom, 0px));
        border-top: 1px solid var(--border); box-shadow: 0 -4px 30px rgba(0,0,0,0.15); z-index: 90; gap: 16px;
    }
    .mobile-sticky-cta .cta-btn { flex: 1; padding: 16px; font-size: 16px; border-radius: 16px; }

    /* Mini Map */
    .v-map-wrap { border-radius: var(--radius-xl); overflow: hidden; border: 1px solid var(--border); box-shadow: var(--shadow-sm); }
    .v-map-wrap iframe { width: 100%; height: 220px; border: 0; display: block; filter: brightness(0.9) contrast(1.05); }
    .v-map-info { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 20px; background: var(--surface); }
    .v-map-addr { display: flex; align-items: center; gap: 10px; color: var(--text-main); font-size: 14px; font-weight: 600; }
    .v-map-addr i { color: var(--accent); font-size: 16px; }
    .v-map-link { color: var(--accent); font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap; display: flex; align-items: center; gap: 4px; }
    .v-map-link:hover { text-decoration: underline; }

    /* Mobile Overrides */
    @media (max-width: 900px) {
        /* Gallery edge-to-edge on mobile */
        .v-gallery-section { margin-top: 0; padding: 0; }
        .v-gallery { border-radius: 0; border-left: none; border-right: none; border-top: none; }
        .v-main-img { aspect-ratio: 4/3; }
        .v-thumbs { padding: 10px 14px; }
        .v-thumb { height: 60px; min-width: 85px; }

        /* Title block stacked on mobile */
        .v-app-container { padding: 0 16px; }
        .v-header-section { 
            flex-direction: column;
            align-items: flex-start;
            gap: 0;
            padding: 20px 0 0;
            border-bottom: none;
            margin-bottom: 16px;
        }
        .v-header-left { width: 100%; }
        .v-title { font-size: 22px; margin-bottom: 14px; }
        .v-quick-specs { gap: 8px; margin-bottom: 16px; }
        .v-quick-badge { font-size: 12px; padding: 6px 12px; }

        /* Price block full-width accent card on mobile */
        .v-price-block { 
            width: 100%;
            text-align: left;
            background: linear-gradient(135deg, rgba(139,92,246,0.08), rgba(139,92,246,0.03));
            border: 1px solid rgba(139,92,246,0.25);
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 16px;
        }
        .v-price { font-size: 32px; }
        
        /* Grid single col on mobile */
        .v-app-grid { grid-template-columns: 1fr; gap: 0; }
        .v-specs-grid { grid-template-columns: 1fr; }
        .v-spec { padding: 12px 0; }
        .v-contact-box { position: static; padding: 20px; }
        .desktop-cta { display: none; }
        
        .v-box { padding: 20px; border-radius: 18px; margin-bottom: 16px; }
        .v-box h3 { font-size: 17px; margin-bottom: 18px; }
        
        /* Sticky CTA on mobile */
        .mobile-sticky-cta { display: flex; animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        body { padding-bottom: 100px; }
    }
    
    @media (max-width: 480px) {
        .v-title { font-size: 20px; }
        .v-quick-badge { font-size: 11px; padding: 5px 10px; }
        .v-price { font-size: 28px; }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endpush

@section('content')
@php
    $vehicleUrl = route('vehicles.show', $vehicle->slug);
    $vehicleImg = $vehicle->images->count() > 0 ? asset('storage/' . $vehicle->images->first()->image_path) : '';
    $waMsg = "Hola! Me interesa este vehículo:\n\n"
        . "🚗 *" . $vehicle->title . "*\n"
        . "💰 Precio: " . $vehicle->formatted_price . "\n"
        . "📅 Año: " . $vehicle->year . "\n"
        . "📍 Km: " . number_format($vehicle->mileage, 0, ',', '.') . "\n\n"
        . ($vehicleImg ? "📸 Foto: " . $vehicleImg . "\n" : '')
        . "🔗 Ver ficha: " . $vehicleUrl . "\n\n"
        . "¿Podrían darme más información?";
    $waUrl = 'https://wa.me/' . \App\Models\Setting::getVal('whatsapp', '34954000000') . '?text=' . urlencode($waMsg);
@endphp
<div class="v-app-layout">
    <!-- Gallery First (App-style) -->
    <div class="v-gallery-section">
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
    </div>

    <!-- Details Container -->
    <div class="v-app-container">
        
        <!-- Header -->
        <div class="v-header-section">
            <div class="v-header-left">
                <div class="v-title-meta">{{ $vehicle->brand->name }} &bull; {{ $vehicle->carModel->name }}</div>
                <h1 class="v-title">{{ $vehicle->title }}</h1>
                <div class="v-quick-specs">
                    <span class="v-quick-badge"><i class="icon-calendar"></i> {{ $vehicle->year }}</span>
                    <span class="v-quick-badge"><i class="icon-gauge"></i> {{ number_format($vehicle->mileage, 0, ',', '.') }} km</span>
                    <span class="v-quick-badge"><i class="icon-settings"></i> {{ $vehicle->transmission == 'automatic' ? 'Automático' : 'Manual' }}</span>
                </div>
            </div>
            <div class="v-price-block">
                @if($vehicle->old_price > 0) <span class="v-old-price">{{ number_format($vehicle->old_price, 0, ',', '.') }} {{ \App\Models\Setting::getVal('currency', '€') }}</span> @endif
                <div class="v-price">{{ $vehicle->formatted_price }}</div>
            </div>
        </div>

        <!-- Grid Body -->
        <div class="v-app-grid">
            
            <div class="v-main-content">
                <!-- Desc -->
                @if($vehicle->description)
                <div class="v-box">
                    <h3><i class="icon-file-text" style="color:var(--accent);"></i> Descripción</h3>
                    <div style="line-height:1.8; color:var(--text-main); font-size:15px;">
                        {!! nl2br(e($vehicle->description)) !!}
                    </div>
                </div>
                @endif

                <!-- Tech Specs -->
                <div class="v-box">
                    <h3><i class="icon-sliders" style="color:var(--accent);"></i> Resumen Técnico</h3>
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
                    <h3><i class="icon-star" style="color:var(--accent);"></i> Equipamiento Destacado</h3>
                    <div class="v-features">
                        @foreach($vehicle->features as $f)
                            <span class="v-feat"><i class="icon-check" style="color:#10b981;"></i> {{ $f->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Mini Map -->
                <div class="v-box" style="padding:0; overflow:hidden;">
                    <h3 style="padding: 20px 20px 0;"><i class="icon-map-pin" style="color:var(--accent);"></i> Nuestra Ubicación</h3>
                    <div class="v-map-wrap" style="border:none; border-radius:0; box-shadow:none;">
                        <iframe src="{{ \App\Models\Setting::getVal('google_maps_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d101408.21721869805!2d-6.064509172605822!3d37.37535004149959!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd126c1114be6291%3A0x34f018621cfe5648!2sSeville!5e0!3m2!1sen!2ses!4v1700000000000!5m2!1sen!2ses') }}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <div class="v-map-info">
                            <div class="v-map-addr">
                                <i class="icon-map-pin"></i>
                                {{ \App\Models\Setting::getVal('address', 'Sevilla, España') }}
                            </div>
                            <a href="{{ \App\Models\Setting::getVal('google_maps_link', 'https://maps.google.com/?q=Sevilla') }}" target="_blank" class="v-map-link">
                                Cómo llegar <i class="icon-external-link"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="v-sidebar">
                <!-- Contact Sidebar -->
                <div class="v-box v-contact-box">
                    <h3>¿Te interesa este coche?</h3>
                    <p style="color:var(--text-muted); font-size:14px; margin-bottom:24px; line-height:1.6;">Nuestro equipo de expertos te asesorará de inmediato para asegurar esta oportunidad.</p>
                    
                    @if(session('success'))
                        <div style="background:rgba(16,185,129,0.15); color:#10b981; padding:16px; border-radius:12px; margin-bottom:20px; font-size:14px; text-align:center; border: 1px solid rgba(16,185,129,0.3); font-weight:600;">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="vehicle">
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                        <input type="hidden" name="subject" value="Interés en: {{ $vehicle->title }}">
                        
                        <input type="text" name="name" required placeholder="Tu Nombre">
                        <input type="email" name="email" required placeholder="Tu Email">
                        <input type="text" name="phone" placeholder="Teléfono Móvil">
                        <textarea name="message" required placeholder="Hola, me gustaría recibir más información..." rows="3"></textarea>
                        
                        <button type="submit" class="btn btn-primary" style="width:100%;"><b>Enviar Consulta Mágica</b></button>
                        
                        <div class="cta-grid desktop-cta">
                            <a href="{{ $waUrl }}" target="_blank" class="cta-btn cta-whatsapp">
                                <i class="icon-message-circle"></i> WhatsApp
                            </a>
                            <a href="tel:{{ \App\Models\Setting::getVal('phone', '34954000000') }}" class="cta-btn cta-call">
                                <i class="icon-phone"></i> Llamar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Sticky Mobile Action Bar -->
<div class="mobile-sticky-cta">
    <a href="{{ $waUrl }}" target="_blank" class="cta-btn cta-whatsapp">
        <i class="icon-message-circle"></i> WhatsApp
    </a>
    <a href="tel:{{ \App\Models\Setting::getVal('phone', '34954000000') }}" class="cta-btn cta-call">
        <i class="icon-phone"></i> Llamar
    </a>
</div>
@endsection