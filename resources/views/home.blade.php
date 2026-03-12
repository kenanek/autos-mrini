@extends('layouts.public')

@push('styles')
<style>
    /* Hero */
    .hero { position: relative; min-height: 600px; display: flex; align-items: center; overflow: hidden; padding: 80px 0 120px; }
    .hero-bg { position: absolute; inset: 0; z-index: 0; background: #000; }
    .hero-media-item { position: absolute; inset: 0; opacity: 0; transition: opacity 1s ease-in-out; }
    .hero-media-item.active { opacity: 1; }
    .hero-media-item img, .hero-media-item video { width: 100%; height: 100%; object-fit: cover; }
    .hero-bg::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(10,10,26,0.92) 0%, rgba(10,10,26,0.7) 50%, rgba(10,10,26,0.4) 100%); z-index: 2; }
    .hero-content { position: relative; z-index: 10; max-width: 640px; color: white; }
    .hero-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: rgba(139,92,246,0.15); border: 1px solid rgba(139,92,246,0.3); border-radius: 8px; font-size: 12px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: #a78bfa; margin-bottom: 28px; }
    .hero-content h1 { font-size: 56px; font-weight: 800; color: white !important; margin-bottom: 24px; line-height: 1.1; letter-spacing: -0.04em; }
    .hero-content h1 em { font-style: italic; color: #a78bfa; }
    .hero-content p { font-size: 17px; color: #cbd5e1 !important; margin-bottom: 36px; line-height: 1.7; max-width: 520px; }
    .hero-buttons { display: flex; gap: 16px; }
    .hero-buttons .btn-outline { color: white !important; border-color: rgba(255,255,255,0.3) !important; background: rgba(255,255,255,0.05); }
    .hero-buttons .btn-outline:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.5) !important; }

    /* Search Block */
    .search-block { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 28px 32px; margin-top: -60px; position: relative; z-index: 10; }
    .search-form { display: flex; gap: 20px; align-items: end; }
    .search-form .fg { flex: 1; }
    .fg-label { display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.08em; }
    .fg-control { width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; color: var(--text-main); background: var(--primary); transition: all 0.2s; appearance: auto; }
    .fg-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }

    /* Section Titles */
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 48px; }
    .section-header h2 { font-size: 32px; letter-spacing: -0.03em; }
    .section-header a { color: var(--accent); font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; }
    .section-header a:hover { color: var(--accent-hover); }

    /* Vehicle Cards */
    .vehicle-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 32px; }
    .vehicle-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; transition: all 0.3s ease; display: flex; flex-direction: column; }
    .vehicle-card:hover { border-color: var(--border-light); transform: translateY(-4px); box-shadow: var(--shadow-float); }
    .vehicle-img-wrap { position: relative; aspect-ratio: 16/10; overflow: hidden; background: var(--primary-light); }
    .vehicle-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .vehicle-card:hover .vehicle-img-wrap img { transform: scale(1.05); }
    .vehicle-badge { position: absolute; top: 14px; left: 14px; z-index: 2; }
    .vehicle-info { padding: 24px; flex-grow: 1; display: flex; flex-direction: column; }
    .vehicle-title { font-size: 18px; font-weight: 700; margin-bottom: 8px; color: var(--text-bright); }
    .vehicle-price { font-size: 22px; font-weight: 800; color: var(--accent); margin-bottom: 16px; font-family: 'Outfit', sans-serif; }
    .vehicle-specs { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; color: var(--text-muted); font-size: 13px; }
    .spec-item { display: flex; align-items: center; gap: 6px; }
    .vehicle-action { margin-top: auto; padding-top: 16px; border-top: 1px solid var(--border); }
    .vehicle-action .btn-outline { width: 100%; padding: 10px; font-size: 14px; }

    /* Trust Section */
    .trust-section { padding: 100px 0; }
    .trust-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; }
    .trust-icon { width: 64px; height: 64px; background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.2); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px; color: var(--accent); }
    .trust-title { font-size: 20px; font-weight: 700; margin-bottom: 12px; color: var(--text-bright); }
    .trust-text { color: var(--text-muted); font-size: 15px; line-height: 1.7; }

    /* Newsletter */
    .newsletter { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-xl); padding: 60px; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; overflow: hidden; position: relative; }
    .newsletter h2 { font-size: 28px; margin-bottom: 16px; }
    .newsletter p { color: var(--text-muted); font-size: 15px; line-height: 1.7; margin-bottom: 28px; }
    .newsletter-form { display: flex; gap: 12px; }
    .newsletter-form input { flex: 1; padding: 14px 18px; background: var(--primary); border: 1px solid var(--border); border-radius: var(--radius-md); color: var(--text-main); font-family: inherit; font-size: 14px; }
    .newsletter-form input:focus { outline:none; border-color: var(--accent); }
    .newsletter-img { border-radius: var(--radius-lg); overflow: hidden; aspect-ratio: 4/3; }
    .newsletter-img img { width: 100%; height: 100%; object-fit: cover; }

    /* Animations */
    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

    @media (max-width: 900px) {
        .hero { min-height: 540px; padding: 60px 0 100px; }
        .hero-content h1 { font-size: 38px; margin-bottom: 20px; }
        .hero-content p { font-size: 16px; margin-bottom: 32px; }
        
        /* App-like Hero Buttons */
        .hero-buttons { flex-direction: row; flex-wrap: wrap; gap: 12px; }
        .hero-buttons .btn { flex: 1; min-width: 140px; padding: 14px 0 !important; font-size: 14px; justify-content: center; text-align: center; }
        
        /* Section Headers on Mobile */
        .section-header { flex-direction: column; align-items: flex-start; gap: 12px; margin-bottom: 32px; }
        .section-header h2 { font-size: 24px; }
        
        /* Premium Mobile Search Card */
        .search-block { 
            padding: 24px; margin-top: -40px; 
            border-radius: 20px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        .search-form { flex-direction: column; gap: 16px; align-items: stretch; }
        .search-form .fg { width: 100%; }
        .fg-label { font-size: 12px; margin-bottom: 10px; }
        .fg-control { padding: 14px 16px; font-size: 16px; border-radius: 12px; } /* 16px prevents iOS zoom */
        .search-form .btn { width: 100%; padding: 14px 16px; border-radius: 12px; margin-top: 8px; font-size: 16px; }

        .newsletter { grid-template-columns: 1fr; padding: 32px 24px; border-radius: 20px; text-align: center; }
        .newsletter-form { flex-direction: column; }
        .newsletter-img { display: none; }
        
        /* Vehicle Cards Layout - Mobile (2 per row) */
        .vehicle-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .vehicle-card .vehicle-info { padding: 16px; }
        .vehicle-card .vehicle-title { font-size: 14px; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .vehicle-card .vehicle-price { font-size: 16px; margin-bottom: 12px; }
        .vehicle-card .vehicle-specs { gap: 10px; font-size: 11px; margin-bottom: 12px; }
        .vehicle-card .vehicle-action .btn-outline { font-size: 12px; padding: 8px; }
        
        /* Trust benefits responsive stack */
        .trust-section { padding: 60px 0; }
        .trust-grid { gap: 32px; text-align: center; }
        .trust-icon { margin: 0 auto 16px; }
    }
    
    @media (max-width: 380px) {
        .vehicle-grid { gap: 10px; }
        .vehicle-card .vehicle-info { padding: 12px; }
        .vehicle-card .vehicle-specs { gap: 8px; flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg" id="hero-slider">
        @if(isset($heroMedia) && $heroMedia->count() > 0)
            @foreach($heroMedia as $index => $media)
                <div class="hero-media-item {{ $index === 0 ? 'active' : '' }}">
                    @if($media->type === 'video')
                        <video src="{{ asset('storage/' . $media->file_path) }}" autoplay loop muted playsinline poster=""></video>
                    @else
                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="Hero Background" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                    @endif
                </div>
            @endforeach
        @else
            <div class="hero-media-item active">
                <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Hero Background">
            </div>
        @endif
    </div>
    <div class="container hero-content">
        <div class="hero-badge"><i class="icon-star" style="font-size:12px;"></i> PREMIUM COLLECTION SEVILLA</div>
        <h1>Encuentra el coche de tus <em>sueños</em> hoy.</h1>
        <p>Descubre nuestra selección exclusiva de vehículos premium seminuevos y de ocasión en Sevilla. Calidad verificada y garantía total.</p>
        <div class="hero-buttons">
            <a href="{{ route('vehicles.index') }}" class="btn btn-primary" style="padding: 14px 32px;">Ver Catálogo <i class="icon-arrow-right" style="font-size:16px;"></i></a>
            <a href="{{ route('contact') }}" class="btn btn-outline" style="padding: 14px 32px;">Contáctanos</a>
        </div>
    </div>
</section>

<!-- Search Block -->
<div class="container">
    <div class="search-block">
        <form action="{{ route('vehicles.index') }}" method="GET" class="search-form">
            <div class="fg">
                <label class="fg-label"><i class="icon-car" style="font-size:12px; margin-right:4px;"></i> Marca</label>
                <select name="brand_id" id="f-brand" class="fg-control">
                    <option value="">Todas las marcas</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand['id'] }}">{{ $brand['name'] }} ({{ $brand['count'] }})</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label class="fg-label"><i class="icon-calendar" style="font-size:12px; margin-right:4px;"></i> Año Mínimo</label>
                <select name="year_min" id="f-year" class="fg-control">
                    <option value="">Cualquier año</option>
                    @foreach($years as $y)
                        <option value="{{ $y['value'] }}">{{ $y['value'] }} ({{ $y['count'] }})</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label class="fg-label"><i class="icon-wallet" style="font-size:12px; margin-right:4px;"></i> Precio Máximo</label>
                <select name="price_max" id="f-price" class="fg-control">
                    <option value="">Sin límite</option>
                    @foreach($priceSteps as $ps)
                        <option value="{{ $ps['value'] }}" {{ request('price_max') == $ps['value'] ? 'selected' : '' }}>{{ $ps['label'] }} ({{ $ps['count'] }})</option>
                    @endforeach
                </select>
            </div>
            <div style="flex-shrink:0;">
                <label class="fg-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary" style="height: 46px; padding: 12px 32px;"><i class="icon-search" style="font-size:16px;"></i> Buscar</button>
            </div>
        </form>
    </div>
</div>

@if($featuredVehicles->count() > 0)
<!-- Featured Vehicles -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Vehículos Destacados</h2>
            <a href="{{ route('vehicles.index') }}">Ver todos <i class="icon-arrow-right" style="font-size:14px;"></i></a>
        </div>
        <div class="vehicle-grid">
            @foreach($featuredVehicles as $vehicle)
                @include('components.vehicle-card', ['vehicle' => $vehicle])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Trust Section -->
<section class="trust-section">
    <div class="container">
        <div class="trust-grid">
            <div class="reveal">
                <div class="trust-icon"><i class="icon-shield-check"></i></div>
                <h3 class="trust-title">Garantía Total</h3>
                <p class="trust-text">Todos nuestros vehículos incluyen mínimo 12 meses de garantía premium para su tranquilidad y seguridad en carretera.</p>
            </div>
            <div class="reveal" style="transition-delay: 0.1s;">
                <div class="trust-icon"><i class="icon-settings"></i></div>
                <h3 class="trust-title">Revisión Exhaustiva</h3>
                <p class="trust-text">Sometemos cada coche a un estricto control mecánico y electrónico de más de 100 puntos antes de su entrega.</p>
            </div>
            <div class="reveal" style="transition-delay: 0.2s;">
                <div class="trust-icon"><i class="icon-wallet"></i></div>
                <h3 class="trust-title">Financiación a Medida</h3>
                <p class="trust-text">Condiciones excelentes de financiación. Estudiamos su caso para ofrecerle la cuota perfecta adaptada a usted.</p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Vehicles -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        <div class="section-header">
            <h2>Últimas Novedades</h2>
            <a href="{{ route('vehicles.index') }}">Ver todos <i class="icon-arrow-right" style="font-size:14px;"></i></a>
        </div>
        <div class="vehicle-grid">
            @foreach($recentVehicles as $vehicle)
                @include('components.vehicle-card', ['vehicle' => $vehicle])
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        <div class="newsletter">
            <div>
                <h2>Últimas Novedades</h2>
                <p>Suscríbete para recibir las últimas entradas en nuestro catálogo antes que nadie. Recibe ofertas exclusivas y alertas de nuevos modelos que encajen con tu perfil.</p>
                @if(session('newsletter_success'))
                    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                        <i class="icon-check" style="margin-right:8px;"></i> {{ session('newsletter_success') }}
                    </div>
                @elseif(session('newsletter_info'))
                    <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid #3b82f6; color: #3b82f6; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                        <i class="icon-info" style="margin-right:8px;"></i> {{ session('newsletter_info') }}
                    </div>
                @endif
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                    @csrf
                    <input type="email" name="email" placeholder="Tu correo electrónico" required>
                    <button type="submit" class="btn btn-primary">Suscríbete</button>
                </form>
            </div>
            <div class="newsletter-img">
                <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Interior premium">
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Dynamic Filter Updates (Hero Search)
    document.addEventListener("DOMContentLoaded", function() {
        var API = "{{ route('api.vehicle-filters') }}";
        var selects = {
            brand: document.getElementById('f-brand'),
            year:  document.getElementById('f-year'),
            price: document.getElementById('f-price'),
        };

        if (!selects.brand) return; // Not on a page with these filters

        function getParams() {
            var p = {};
            if (selects.brand.value) p.brand_id = selects.brand.value;
            if (selects.year && selects.year.value)  p.year_min = selects.year.value;
            if (selects.price && selects.price.value) p.price_max = selects.price.value;
            return p;
        }

        function rebuildSelect(el, options, currentVal, defaultText) {
            if (!el) return;
            el.innerHTML = '';
            var opt0 = document.createElement('option');
            opt0.value = ''; opt0.textContent = defaultText;
            el.appendChild(opt0);

            var stillValid = false;
            options.forEach(function(item) {
                var opt = document.createElement('option');
                var val = String(item.value || item.id || item);
                var label = item.label || item.name || item.value || item.id || item;
                var count = item.count;
                opt.value = val;
                opt.textContent = count !== undefined ? label + ' (' + count + ')' : label;
                if (val === String(currentVal)) { opt.selected = true; stillValid = true; }
                el.appendChild(opt);
            });
            if (!stillValid) el.value = '';
        }

        var defaults = {
            'f-brand': 'Todas las marcas',
            'f-year':  'Cualquier año',
            'f-price': 'Sin límite',
        };

        var fetchTimer = null;

        function refreshFilters(changedField) {
            clearTimeout(fetchTimer);
            fetchTimer = setTimeout(function() {
                var params = getParams();
                var qs = new URLSearchParams(params).toString();
                Object.values(selects).forEach(function(s) { if(s) s.classList.add('loading'); });

                fetch(API + '?' + qs)
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        var cv = {
                            brand: selects.brand.value, 
                            year: selects.year ? selects.year.value : '', 
                            price: selects.price ? selects.price.value : '',
                        };
                        if (changedField !== 'brand') rebuildSelect(selects.brand, data.brands, cv.brand, defaults['f-brand']);
                        if (changedField !== 'year')  rebuildSelect(selects.year, data.years, cv.year, defaults['f-year']);
                        if (changedField !== 'price') rebuildSelect(selects.price, data.priceSteps, cv.price, defaults['f-price']);
                    })
                    .finally(function() {
                        Object.values(selects).forEach(function(s) { if(s) s.classList.remove('loading'); });
                    });
            }, 80);
        }

        selects.brand.addEventListener('change', function() { refreshFilters('brand'); });
        if(selects.year) selects.year.addEventListener('change',  function() { refreshFilters('year'); });
        if(selects.price) selects.price.addEventListener('change', function() { refreshFilters('price'); });
    });

    // Hero Slider Rotation
    document.addEventListener("DOMContentLoaded", function() {
        const slides = document.querySelectorAll('#hero-slider .hero-media-item');
        if (slides.length > 1) {
            let current = 0;
            setInterval(function() {
                slides[current].classList.remove('active');
                current = (current + 1) % slides.length;
                slides[current].classList.add('active');
            }, 6000); // 6 seconds per slide
        }
    });

    // Intersection Observer for Scroll Reveal
    document.addEventListener("DOMContentLoaded", function() {
        var reveals = document.querySelectorAll(".reveal");
        var windowHeight = window.innerHeight;
        
        var revealOnScroll = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                    observer.unobserve(entry.target);
                }
            });
        }, {
            root: null,
            rootMargin: "0px",
            threshold: 0.15
        });

        reveals.forEach(function(el) {
            revealOnScroll.observe(el);
        });
    });
</script>