<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::getVal('default_meta_title', 'Autos Mrini - Concesionario Premium en Sevilla'))</title>
    @if(\App\Models\Setting::getVal('favicon'))<link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(\App\Models\Setting::getVal('favicon')) }}">@endif
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::getVal('default_meta_description', 'Encuentra vehículos premium, seminuevos y de ocasión con las mejores financiaciones.'))">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.min.css">
    <style>
        :root, [data-theme="dark"] {
            --primary: #0a0a1a;
            --primary-light: #13132b;
            --surface: #12121f;
            --surface-light: #1a1a2e;
            --accent: #8b5cf6;
            --accent-hover: #7c3aed;
            --accent-glow: rgba(139, 92, 246, 0.3);
            --bg-color: #0a0a1a;
            --text-main: #e2e8f0;
            --text-muted: #7c7c9a;
            --text-bright: #ffffff;
            --border: rgba(255, 255, 255, 0.08);
            --border-light: rgba(255, 255, 255, 0.12);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.2);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.3), 0 4px 6px -4px rgb(0 0 0 / 0.2);
            --shadow-float: 0 20px 40px -8px rgb(0 0 0 / 0.5);
            --radius-md: 10px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --header-bg: rgba(10, 10, 26, 0.9);
            --header-border: rgba(255, 255, 255, 0.08);
        }

        [data-theme="light"] {
            --primary: #ffffff;
            --primary-light: #f8fafc;
            --surface: #ffffff;
            --surface-light: #f1f5f9;
            --accent: #8b5cf6;
            --accent-hover: #7c3aed;
            --accent-glow: rgba(139, 92, 246, 0.2);
            --bg-color: #f1f5f9;
            --text-main: #334155;
            --text-muted: #64748b;
            --text-bright: #0f172a;
            --border: rgba(0, 0, 0, 0.08);
            --border-light: rgba(0, 0, 0, 0.12);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.05);
            --shadow-float: 0 20px 40px -8px rgb(0 0 0 / 0.1);
            --header-bg: rgba(255, 255, 255, 0.9);
            --header-border: rgba(0, 0, 0, 0.08);
        }

        /* Essential Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Inter', -apple-system, sans-serif; background-color: var(--bg-color); color: var(--text-main); line-height: 1.6; -webkit-font-smoothing: antialiased; transition: background-color 0.3s ease, color 0.3s ease; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; font-weight: 700; line-height: 1.25; color: var(--text-bright); letter-spacing: -0.02em; transition: color 0.3s ease; }
        a { text-decoration: none; color: inherit; transition: all 0.25s ease; }
        ul { list-style: none; }
        img { max-width: 100%; height: auto; display: block; }
        
        .container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        
        /* Header */
        header { background: var(--header-bg); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid var(--header-border); transition: background-color 0.3s ease, border-color 0.3s ease; }
        .nav-wrapper { display: flex; align-items: center; justify-content: space-between; height: 72px; }
        .logo { font-size: 22px; font-weight: 800; color: var(--text-bright); letter-spacing: -0.02em; font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 10px; }
        .logo span { color: var(--accent); }
        .nav-links { display: flex; gap: 8px; align-items: center; }
        .nav-link { font-weight: 500; font-size: 14px; color: var(--text-muted); transition: all 0.2s ease; padding: 8px 14px; border-radius: 8px; }
        .nav-link:hover { color: var(--accent); background: rgba(139,92,246,0.1); }
        .nav-link.active { color: var(--accent); background: rgba(139,92,246,0.08); font-weight: 600; }

        /* Mobile Menu Toggle & Theme Toggle */
        .mobile-actions { display: none; align-items: center; gap: 8px; }
        .icon-btn { background: transparent; border: 1px solid var(--border); color: var(--text-main); font-size: 20px; cursor: pointer; padding: 8px; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .icon-btn:hover { background: rgba(139,92,246,0.1); color: var(--accent); border-color: rgba(139,92,246,0.3); }
        .theme-toggle { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; border: 1px solid var(--border); background: transparent; color: var(--text-main); cursor: pointer; transition: all 0.2s; }
        .theme-toggle:hover { color: var(--accent); background: rgba(139,92,246,0.1); border-color: rgba(139,92,246,0.3); }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 12px 28px; font-weight: 600; font-size: 14px; border-radius: var(--radius-md); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; border: 1px solid transparent; font-family: 'Inter', sans-serif; text-align: center; gap: 8px; }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-hover); transform: translateY(-2px); box-shadow: 0 8px 24px var(--accent-glow); }
        .btn-outline { background: transparent; color: var(--text-bright); border: 1px solid var(--border-light); }
        .btn-outline:hover { background: var(--surface); border-color: var(--accent); transform: translateY(-1px); }
        .btn-ghost { background: transparent; color: var(--accent); padding: 8px 0; }
        .btn-ghost:hover { color: var(--accent-hover); }
        
        /* Pagination */
        nav[role="navigation"] { display: flex; flex-direction: column; align-items: center; gap: 12px; }
        nav[role="navigation"] .flex.justify-between { display: none; }
        nav[role="navigation"] > div:last-child .flex.flex-wrap { display: none; }
        .pagination, nav[role="navigation"] span[aria-label], nav[role="navigation"] div > span { display: flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: wrap; }
        nav[role="navigation"] a, nav[role="navigation"] span[aria-current] {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 40px; height: 40px; padding: 0 12px;
            border-radius: 10px; font-size: 14px; font-weight: 600;
            border: 1px solid var(--border); color: var(--text-muted);
            background: var(--surface); transition: all 0.2s;
        }
        nav[role="navigation"] a:hover { background: var(--accent); color: white; border-color: var(--accent); transform: translateY(-1px); }
        nav[role="navigation"] span[aria-current] { background: var(--accent); color: white; border-color: var(--accent); }
        nav[role="navigation"] span.cursor-default { opacity: 0.4; pointer-events: none; }
        nav[role="navigation"] p.text-sm { color: var(--text-muted); font-size: 13px; }
        
        /* Footer */
        footer { background: var(--surface); position: relative; overflow: hidden; color: var(--text-main); padding: 80px 0 0; margin-top: 0; border-top: 1px solid var(--border); transition: background-color 0.3s ease; }
        .footer-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 50px; margin-bottom: 50px; position: relative; z-index: 1; }
        .footer-title { font-size: 16px; font-weight: 600; margin-bottom: 24px; color: var(--text-bright); letter-spacing: -0.01em; }
        .footer-links li { margin-bottom: 14px; }
        .footer-links a { color: var(--text-muted); font-size: 14px; display: inline-flex; align-items: center; gap: 8px; }
        .footer-links a:hover { color: var(--accent); }
        .footer-bottom { border-top: 1px solid var(--border); padding: 24px 0; display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 13px; position: relative; z-index: 1; }
        
        /* Utils */
        .section { padding: 100px 0; }
        .section-alt { background: var(--surface); transition: background-color 0.3s ease; }
        .badge { display: inline-flex; align-items: center; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; }
        .badge-accent { background: var(--accent-glow); color: var(--accent); border: 1px solid var(--border); }
        .badge-success { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.25); }

        /* Vehicle Card Image Fix */
        .vehicle-img-wrap { position: relative; aspect-ratio: 16/10; overflow: hidden; background: var(--primary-light); }
        .vehicle-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .featured-badge { position: absolute; top: 0; left: 0; z-index: 2; background: var(--accent); color: white; padding: 4px 12px; border-bottom-right-radius: 8px; font-weight: 700; font-size: 10px; letter-spacing: 0.08em; text-transform: uppercase; box-shadow: 2px 2px 10px rgba(139,92,246,0.3); }

        /* App-like Mobile Feel Base (Hidden on Desktop) */
        .mobile-actions { display: none; align-items: center; gap: 8px; }
        .mobile-nav-overlay { display: none; }
        .mobile-nav-drawer { display: none; }

        @media (max-width: 900px) {
            .nav-links { display: none; }
            .theme-toggle-desktop { display: none; }
            .mobile-actions { display: flex; }
            
            /* Premium Mobile Top Header */
            .nav-wrapper { padding: 0 16px; height: 60px; }
            .container { padding: 0 16px; }
            .logo img { max-height: 80px !important; }
            .logo { font-size: 18px; gap: 8px; }
            
            /* Action Buttons Spacing */
            .icon-btn, .theme-toggle { width: 36px; height: 36px; padding: 0; }
            .mobile-actions { gap: 12px; }
            
            /* App-style Bottom Navigation for Mobile */
            .mobile-nav-overlay {
                display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 99; opacity: 0; transition: opacity 0.3s ease;
            }
            .mobile-nav-overlay.open { display: block; opacity: 1; }
            
            .mobile-nav-drawer {
                display: block; position: fixed; bottom: 0; left: 0; right: 0; background: var(--surface); border-top-left-radius: 24px; border-top-right-radius: 24px;
                padding: 24px 24px 40px; z-index: 100; transform: translateY(100%); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                border-top: 1px solid var(--border); box-shadow: 0 -10px 40px rgba(0,0,0,0.2);
            }
            .mobile-nav-drawer.open { transform: translateY(0); }
            
            .drawer-handle { width: 40px; height: 4px; background: var(--border); border-radius: 2px; margin: 0 auto 20px; }
            
            .mobile-nav-links { display: flex; flex-direction: column; gap: 8px; }
            .mobile-nav-link { display: flex; align-items: center; gap: 12px; padding: 16px; border-radius: 12px; font-size: 16px; font-weight: 600; color: var(--text-bright); text-decoration: none; background: var(--primary); border: 1px solid var(--border); }
            .mobile-nav-link.active { background: var(--accent-glow); color: var(--accent); border-color: rgba(139,92,246,0.3); }
            .mobile-nav-link i { font-size: 20px; }
            
            .section { padding: 60px 0; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 30px; }
            .footer-bottom { flex-direction: column; gap: 16px; text-align: center; padding-bottom: 40px; }
        }
        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; }
        }

        /* Light mode overrides for icon colors */
        [data-theme="light"] .icon-btn { border-color: #e2e8f0; color: #475569; }
        [data-theme="light"] .icon-btn:hover { background: #f1f5f9; color: #0f172a; }
        [data-theme="light"] .theme-toggle { border-color: #e2e8f0; color: #475569; }
        [data-theme="light"] .theme-toggle:hover { background: #f1f5f9; color: #0f172a; }
    </style>
    
    <script>
        // Apply theme early to prevent Flash of Unstyled Content (FOUC)
        (function() {
            const serverDefault = '{{ \App\Models\Setting::getVal("default_theme", "dark") }}';
            const storedTheme = localStorage.getItem('theme');
            const lastServerDefault = localStorage.getItem('theme_default_reference');
            
            let themeToApply = serverDefault;
            
            // If the server default changed in the dashboard, force it to apply to everyone
            if (lastServerDefault !== serverDefault) {
                themeToApply = serverDefault;
                localStorage.setItem('theme', serverDefault);
                localStorage.setItem('theme_default_reference', serverDefault);
            } else if (storedTheme) {
                themeToApply = storedTheme;
            }
            
            document.documentElement.setAttribute('data-theme', themeToApply);
        })();
    </script>
    @stack('styles')
</head>
<body>

    <header>
        <div class="container nav-wrapper">
            <a href="{{ route('home') }}" class="logo">
                @if(\App\Models\Setting::getVal('logo'))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(\App\Models\Setting::getVal('logo')) }}" alt="{{ \App\Models\Setting::getVal('site_name', 'Autos Mrini') }}" style="max-height: 100px;">
                @else
                    <div style="width:32px; height:32px; background: var(--accent); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                        <i class="icon-car" style="color:white; font-size:16px;"></i>
                    </div>
                    <span style="color:var(--text-bright);">AUTOS</span> <span style="color:var(--accent); font-style: italic;">MRINI</span>
                @endif
            </a>
            
            <nav class="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>
                <a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Catálogo</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Nosotros</a>
                <a href="{{ route('financing') }}" class="nav-link {{ request()->routeIs('financing') ? 'active' : '' }}">Financiación</a>
                <a href="{{ route('location') }}" class="nav-link {{ request()->routeIs('location') ? 'active' : '' }}">Ubicación</a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contacto</a>
                <button class="theme-toggle theme-toggle-desktop" onclick="toggleTheme()" aria-label="Cambiar tema" style="margin-left: 8px;">
                    <i class="icon-moon theme-icon"></i>
                </button>
            </nav>

            <div class="mobile-actions">
                <button class="theme-toggle" onclick="toggleTheme()" aria-label="Cambiar tema">
                    <i class="icon-moon theme-icon-mobile"></i>
                </button>
                <button class="icon-btn" onclick="toggleMobileNav()" aria-label="Menú">
                    <i class="icon-menu"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-nav-overlay" id="mobileOverlay" onclick="toggleMobileNav()"></div>
    <div class="mobile-nav-drawer" id="mobileDrawer">
        <div class="drawer-handle"></div>
        <div class="mobile-nav-links">
            <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="icon-home"></i> Inicio
            </a>
            <a href="{{ route('vehicles.index') }}" class="mobile-nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                <i class="icon-car"></i> Catálogo
            </a>
            <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                <i class="icon-info"></i> Nosotros
            </a>
            <a href="{{ route('financing') }}" class="mobile-nav-link {{ request()->routeIs('financing') ? 'active' : '' }}">
                <i class="icon-wallet"></i> Financiación
            </a>
            <a href="{{ route('location') }}" class="mobile-nav-link {{ request()->routeIs('location') ? 'active' : '' }}">
                <i class="icon-map-pin"></i> Ubicación
            </a>
            <a href="{{ route('contact') }}" class="mobile-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                <i class="icon-mail"></i> Contacto
            </a>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div style="padding-right: 30px;">
                    <a href="{{ route('home') }}" class="logo" style="color:white; margin-bottom:20px; font-size: 22px;">
                        <div style="width:32px; height:32px; background: var(--accent); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                            <i class="icon-car" style="color:white; font-size:14px;"></i>
                        </div>
                        <span>AUTOS</span> <span style="color:var(--accent); font-style: italic;">MRINI</span>
                    </a>
                    <p style="color:var(--text-muted); font-size:14px; margin-bottom:24px; line-height: 1.8;">
                        {{ \App\Models\Setting::getVal('footer_description', 'Líderes en Sevilla en la venta de vehículos de alta gama y seminuevos con total garantía y revisión certificada.') }}
                    </p>
                    <div style="display: flex; gap: 12px;">
                        @if(\App\Models\Setting::getVal('facebook_url'))
                        <a href="{{ \App\Models\Setting::getVal('facebook_url') }}" style="width:36px; height:36px; border-radius:8px; border: 1px solid var(--border); display:flex; align-items:center; justify-content:center; color: var(--text-muted);" target="_blank"><i class="icon-facebook" style="font-size:16px;"></i></a>
                        @endif
                        @if(\App\Models\Setting::getVal('instagram_url'))
                        <a href="{{ \App\Models\Setting::getVal('instagram_url') }}" style="width:36px; height:36px; border-radius:8px; border: 1px solid var(--border); display:flex; align-items:center; justify-content:center; color: var(--text-muted);" target="_blank"><i class="icon-instagram" style="font-size:16px;"></i></a>
                        @endif
                        @if(\App\Models\Setting::getVal('tiktok_url'))
                        <a href="{{ \App\Models\Setting::getVal('tiktok_url') }}" style="width:36px; height:36px; border-radius:8px; border: 1px solid var(--border); display:flex; align-items:center; justify-content:center; color: var(--text-muted);" target="_blank"><i class="icon-music" style="font-size:16px;"></i></a>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="footer-title">Explorar</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('vehicles.index') }}">Catálogo Completo</a></li>
                        <li><a href="{{ route('vehicles.index') }}">Vehículos de Ocasión</a></li>
                        <li><a href="{{ route('vehicles.index') }}">Novedades</a></li>
                        <li><a href="{{ route('about') }}">Servicio Postventa</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Soporte</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('financing') }}">Financiación</a></li>
                        <li><a href="{{ route('about') }}">Garantía</a></li>
                        <li><a href="{{ route('contact') }}">Política de Privacidad</a></li>
                        <li><a href="{{ route('contact') }}">Términos y Condiciones</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Contacto</h4>
                    <ul class="footer-links">
                        <li style="display:flex; align-items:flex-start; gap:8px;"><i class="icon-map-pin" style="color: var(--accent); font-size:14px; margin-top:4px;"></i> <span style="color:var(--text-muted); font-size:14px;">{{ \App\Models\Setting::getVal('address', 'Sevilla, España') }}</span></li>
                        <li style="display:flex; align-items:center; gap:8px;"><i class="icon-phone" style="color: var(--accent); font-size:14px;"></i> <span style="color:var(--text-muted); font-size:14px;">{{ \App\Models\Setting::getVal('phone', '+34 954 000 000') }}</span></li>
                        <li style="display:flex; align-items:center; gap:8px;"><i class="icon-mail" style="color: var(--accent); font-size:14px;"></i> <span style="color:var(--text-muted); font-size:14px;">{{ \App\Models\Setting::getVal('email', 'info@autosmrini.com') }}</span></li>
                        @if(\App\Models\Setting::getVal('whatsapp'))
                        <li style="display:flex; align-items:center; gap:8px;"><i class="icon-message-circle" style="color: #10b981; font-size:14px;"></i> <a href="https://wa.me/{{ \App\Models\Setting::getVal('whatsapp') }}" target="_blank" style="color: #10b981; font-size:14px;">WhatsApp</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} Autos Mrini. Todos los derechos reservados.</span>
                <div style="display:flex; gap: 24px;">
                    <a href="{{ route('contact') }}" style="color:var(--text-muted); font-size: 13px;">Aviso Legal</a>
                    <a href="{{ route('contact') }}" style="color:var(--text-muted); font-size: 13px;">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Theme Toggle Logic
        function updateThemeIcon() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            document.querySelectorAll('.theme-icon, .theme-icon-mobile').forEach(icon => {
                icon.className = isDark ? 'icon-sun' : 'icon-moon';
            });
        }

        function toggleTheme() {
            const serverDefault = '{{ \App\Models\Setting::getVal("default_theme", "dark") }}';
            const current = document.documentElement.getAttribute('data-theme');
            const target = current === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', target);
            localStorage.setItem('theme', target);
            localStorage.setItem('theme_default_reference', serverDefault); // Keep sync
            updateThemeIcon();
        }

        // Mobile Nav Logic
        function toggleMobileNav() {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileOverlay');
            drawer.classList.toggle('open');
            overlay.classList.toggle('open');
            
            if (drawer.classList.contains('open')) {
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            } else {
                document.body.style.overflow = '';
            }
        }

        // Run once DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcon();
        });
    </script>
    @stack('scripts')
</body>
</html>