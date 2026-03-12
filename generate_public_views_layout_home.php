<?php
$files = [];

$files['resources/views/layouts/public.blade.php'] = <<<'HTML'
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autos Mrini') - Concesionario Premium en Sevilla</title>
    <meta name="description" content="@yield('meta_description', 'Autos Mrini es tu concesionario de confianza en Sevilla, España. Encuentra vehículos premium, seminuevos y de ocasión con las mejores financiaciones.')">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e293b;
            --primary-light: #334155;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --bg-color: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --radius-md: 0.5rem;
            --radius-lg: 1rem;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-color); color: var(--text-main); line-height: 1.5; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; font-weight: 600; line-height: 1.2; color: var(--primary); }
        a { text-decoration: none; color: inherit; transition: color 0.2s ease; }
        ul { list-style: none; }
        img { max-width: 100%; height: auto; display: block; }
        
        .container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        
        /* Header */
        header { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid var(--border); }
        .nav-wrapper { display: flex; align-items: center; justify-content: space-between; height: 80px; }
        .logo { font-size: 24px; font-weight: 700; color: var(--primary); letter-spacing: -0.5px; font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 8px;}
        .logo span { color: var(--accent); }
        .nav-links { display: flex; gap: 32px; align-items: center; }
        .nav-link { font-weight: 500; font-size: 15px; color: var(--primary-light); transition: color 0.2s; }
        .nav-link:hover, .nav-link.active { color: var(--accent); }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 20px; font-weight: 500; font-size: 15px; border-radius: var(--radius-md); transition: all 0.2s; cursor: pointer; border: none; font-family: 'Inter', sans-serif; }
        .btn-primary { background: var(--accent); color: white; box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39); }
        .btn-primary:hover { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4); }
        .btn-outline { background: transparent; color: var(--primary); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--bg-color); border-color: var(--primary-light); }
        
        /* Footer */
        footer { background: var(--primary); color: white; padding: 60px 0 30px; margin-top: 80px; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px; }
        .footer-title { font-size: 18px; font-weight: 600; margin-bottom: 20px; color: white; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { color: #cbd5e1; font-size: 14px; }
        .footer-links a:hover { color: white; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; text-align: center; color: #94a3b8; font-size: 14px; }
        
        /* Utils */
        .section { padding: 80px 0; }
        .section-light { background: white; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-accent { background: rgba(59, 130, 246, 0.1); color: var(--accent); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: #10b981; }

        @media (max-width: 768px) {
            .nav-links { display: none; } /* Simplified for now, would need mobile menu JS */
        }
    </style>
    @stack('styles')
</head>
<body>

    <header>
        <div class="container nav-wrapper">
            <a href="{{ route('home') }}" class="logo">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent)"><circle cx="12" cy="12" r="10"></circle><path d="M16 12l-4-4-4 4M12 8v8"></path></svg>
                Autos <span>Mrini</span>
            </a>
            <nav class="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>
                <a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehículos</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Nosotros</a>
                <a href="{{ route('financing') }}" class="nav-link {{ request()->routeIs('financing') ? 'active' : '' }}">Financiación</a>
                <a href="{{ route('location') }}" class="nav-link {{ request()->routeIs('location') ? 'active' : '' }}">Ubicación</a>
                <a href="{{ route('contact') }}" class="btn btn-primary">Contacto</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="{{ route('home') }}" class="logo" style="color:white; margin-bottom:16px;">Autos <span style="color:var(--accent)">Mrini</span></a>
                    <p style="color:#94a3b8; font-size:14px; margin-bottom:20px;">Tu concesionario premium de vehículos de ocasión y seminuevos en Sevilla, España. Calidad y confianza garantizada.</p>
                </div>
                <div>
                    <h4 class="footer-title">Enlaces Rápidos</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Inicio</a></li>
                        <li><a href="{{ route('vehicles.index') }}">Inventario de Vehículos</a></li>
                        <li><a href="{{ route('financing') }}">Financiación a Medida</a></li>
                        <li><a href="{{ route('about') }}">Sobre Nosotros</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Contacto</h4>
                    <ul class="footer-links">
                        <li style="color:#cbd5e1; font-size:14px; margin-bottom:10px;">📍 Av. de la Innovación, 45, Sevilla, España</li>
                        <li style="color:#cbd5e1; font-size:14px; margin-bottom:10px;">📞 +34 954 00 00 00</li>
                        <li style="color:#cbd5e1; font-size:14px; margin-bottom:10px;">✉️ contacto@autosmrini.es</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} Autos Mrini. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
HTML;

$files['resources/views/home.blade.php'] = <<<'HTML'
@extends('layouts.public')

@push('styles')
<style>
    .hero { position: relative; padding: 120px 0; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); overflow: hidden; }
    .hero::after { content: ''; position: absolute; right: -10%; top: -10%; width: 50%; height: 120%; background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, transparent 70%); z-index: 0; }
    .hero-container { position: relative; z-index: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
    .hero-content h1 { font-size: 56px; font-weight: 700; color: var(--primary); margin-bottom: 24px; letter-spacing: -1px; }
    .hero-content p { font-size: 18px; color: var(--text-muted); margin-bottom: 32px; max-width: 500px; }
    .hero-buttons { display: flex; gap: 16px; }
    .hero-image { position: relative; }
    .hero-image img { width: 100%; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); transform: perspective(1000px) rotateY(-5deg); transition: transform 0.3s; }
    .hero-image img:hover { transform: perspective(1000px) rotateY(0deg); }
    
    .search-block { background: white; border-radius: var(--radius-lg); padding: 32px; box-shadow: var(--shadow-md); margin-top: -60px; position: relative; z-index: 10; max-width: 1000px; margin-left: auto; margin-right: auto; }
    .search-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--primary-light); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-control { width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 15px; color: var(--text-main); background: var(--bg-color); transition: border-color 0.2s; }
    .form-control:focus { outline: none; border-color: var(--accent); }
    
    .section-title { text-align: center; margin-bottom: 60px; }
    .section-title h2 { font-size: 36px; margin-bottom: 16px; }
    .section-title p { color: var(--text-muted); font-size: 16px; max-width: 600px; margin: 0 auto; }
    
    .vehicle-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px; }
    .vehicle-card { background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border); transition: all 0.3s ease; display: flex; flex-direction: column; }
    .vehicle-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: transparent;}
    .vehicle-img-wrap { position: relative; aspect-ratio: 16/10; overflow: hidden; background: #e2e8f0; }
    .vehicle-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .vehicle-card:hover .vehicle-img-wrap img { transform: scale(1.05); }
    .vehicle-badge { position: absolute; top: 16px; left: 16px; z-index: 2; background: white; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 12px; box-shadow: var(--shadow-sm); color: var(--primary); }
    .featured-badge { position: absolute; top: 16px; right: 16px; z-index: 2; background: #f59e0b; color: white; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 12px; box-shadow: var(--shadow-sm); }
    .vehicle-info { padding: 24px; flex-grow: 1; display: flex; flex-direction: column; }
    .vehicle-title { font-size: 18px; font-weight: 600; margin-bottom: 8px; color: var(--primary); }
    .vehicle-price { font-size: 22px; font-weight: 700; color: var(--accent); margin-bottom: 16px; font-family: 'Outfit', sans-serif; }
    .vehicle-specs { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px; }
    .spec-item { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); }
    .vehicle-action { margin-top: auto; border-top: 1px solid var(--border); padding-top: 16px; display: flex; justify-content: space-between; align-items: center; }
    .view-details { font-weight: 600; color: var(--primary); font-size: 14px; }
    .vehicle-card:hover .view-details { color: var(--accent); }
    
    .trust-section { background: var(--primary); color: white; padding: 100px 0; }
    .trust-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; text-align: center; }
    .trust-icon { width: 64px; height: 64px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px; color: var(--accent); }
    .trust-title { font-size: 20px; font-weight: 600; margin-bottom: 12px; color: white; }
    .trust-text { color: #cbd5e1; font-size: 15px; }
    
    @media (max-width: 900px) {
        .hero-container { grid-template-columns: 1fr; text-align: center; }
        .hero-content h1 { font-size: 40px; }
        .hero-content p { margin: 0 auto 32px; }
        .hero-buttons { justify-content: center; }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container hero-container">
        <div class="hero-content">
            <h1>Encuentra el coche de tus sueños hoy.</h1>
            <p>Descubre una selección exclusiva de vehículos premium seminuevos y de ocasión en Sevilla. Calidad verificada y garantía total.</p>
            <div class="hero-buttons">
                <a href="{{ route('vehicles.index') }}" class="btn btn-primary" style="padding: 14px 28px; font-size: 16px;">Ver Catálogo</a>
                <a href="{{ route('contact') }}" class="btn btn-outline" style="padding: 14px 28px; font-size: 16px; background:white;">Contactar</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Vehículo Premium">
        </div>
    </div>
</section>

<!-- Search Block -->
<div class="container">
    <div class="search-block">
        <form action="{{ route('vehicles.index') }}" method="GET" class="search-form">
            <div class="form-group">
                <label>Marca</label>
                <select name="brand_id" class="form-control">
                    <option value="">Todas las marcas</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Año Mínimo</label>
                <select name="year_min" class="form-control">
                    <option value="">Cualquiera</option>
                    @for($y = date('Y'); $y >= 2010; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>Precio Máximo</label>
                <select name="price_max" class="form-control">
                    <option value="">Cualquiera</option>
                    <option value="10000">10.000 €</option>
                    <option value="20000">20.000 €</option>
                    <option value="30000">30.000 €</option>
                    <option value="50000">50.000 €</option>
                    <option value="80000">80.000 €</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%; height: 46px;">Buscar Vehículos</button>
            </div>
        </form>
    </div>
</div>

@if($featuredVehicles->count() > 0)
<!-- Featured Vehicles -->
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Vehículos Destacados</h2>
            <p>Una selección especial de nuestras mejores oportunidades y vehículos premium.</p>
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
            <div>
                <div class="trust-icon">🛡️</div>
                <h3 class="trust-title">Garantía Total</h3>
                <p class="trust-text">Todos nuestros vehículos incluyen mínimo 12 meses de garantía premium para su tranquilidad.</p>
            </div>
            <div>
                <div class="trust-icon">✅</div>
                <h3 class="trust-title">Revisión Exhaustiva</h3>
                <p class="trust-text">Sometemos cada coche a un estricto control de más de 100 puntos antes de su entrega.</p>
            </div>
            <div>
                <div class="trust-icon">💰</div>
                <h3 class="trust-title">Financiación a Medida</h3>
                <p class="trust-text">Condiciones excelentes de financiación. Estudiamos su caso para ofrecerle la cuota perfecta.</p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Vehicles -->
<section class="section section-light">
    <div class="container">
        <div class="section-title">
            <h2>Últimas Novedades</h2>
            <p>Descubre las incorporaciones más recientes a nuestro catálogo.</p>
        </div>
        <div class="vehicle-grid">
            @foreach($recentVehicles as $vehicle)
                @include('components.vehicle-card', ['vehicle' => $vehicle])
            @endforeach
        </div>
        <div style="text-align: center; margin-top: 50px;">
            <a href="{{ route('vehicles.index') }}" class="btn btn-outline" style="padding: 12px 32px;">Ver todo el inventario</a>
        </div>
    </div>
</section>
@endsection
HTML;

$files['resources/views/components/vehicle-card.blade.php'] = <<<'HTML'
<a href="{{ route('vehicles.show', $vehicle->slug) }}" class="vehicle-card">
    <div class="vehicle-img-wrap">
        <span class="vehicle-badge">{{ $vehicle->condition == 'new' ? 'Nuevo' : 'Ocasión' }}</span>
        @if($vehicle->is_featured)
            <span class="featured-badge">Destacado</span>
        @endif
        @if($vehicle->primary_image)
            <img src="{{ asset('storage/' . $vehicle->primary_image) }}" alt="{{ $vehicle->title }}">
        @else
            <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800&auto=format&fit=crop&q=60" alt="Placeholder">
        @endif
    </div>
    <div class="vehicle-info">
        <h3 class="vehicle-title">{{ Str::limit($vehicle->title, 45) }}</h3>
        <div class="vehicle-price">{{ number_format($vehicle->price, 0, ',', '.') }} €</div>
        <div class="vehicle-specs">
            <div class="spec-item"><span style="font-size:16px;">📅</span> {{ $vehicle->year }}</div>
            <div class="spec-item"><span style="font-size:16px;">🛣️</span> {{ number_format($vehicle->mileage, 0, ',', '.') }} km</div>
            <div class="spec-item"><span style="font-size:16px;">⛽</span> 
                @if($vehicle->fuel_type == 'gasoline') Gasolina
                @elseif($vehicle->fuel_type == 'diesel') Diésel
                @elseif($vehicle->fuel_type == 'hybrid') Híbrido
                @elseif($vehicle->fuel_type == 'electric') Eléctrico
                @else {{ ucfirst($vehicle->fuel_type) }} @endif
            </div>
            <div class="spec-item"><span style="font-size:16px;">⚙️</span> {{ $vehicle->transmission == 'automatic' ? 'Automático' : 'Manual' }}</div>
        </div>
        <div class="vehicle-action">
            <span class="view-details">Ver Detalles &rarr;</span>
            <span style="color:var(--text-muted); font-size:13px;">{{ $vehicle->brand->name }}</span>
        </div>
    </div>
</a>
HTML;

foreach ($files as $path => $content) {
    $fullPath = __DIR__ . '/' . $path;
    $dir = dirname($fullPath);
    if (!is_dir($dir))
        mkdir($dir, 0755, true);
    file_put_contents($fullPath, $content);
}
echo "Layout and Home Views generated.\n";
