<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administración') — Autos Mrini</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.min.css">
    <style>
        :root {
            --primary: #1e40af;
            --primary-light: #3b82f6;
            --primary-dark: #1e3a8a;
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
            --accent: #f59e0b;
            --accent-light: #fbbf24;
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-hover: #1e293b;
            --sidebar-active: #1e40af;
            --body-bg: #f1f5f9;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,.08), 0 2px 4px -2px rgba(0,0,0,.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.08), 0 4px 6px -4px rgba(0,0,0,.06);
            --radius: 12px;
            --radius-sm: 8px;
            --radius-xs: 6px;
            --transition: all .2s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--body-bg);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Sidebar ────────────────────────────────────────────── */
        .admin-sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 270px;
            background: var(--sidebar-bg);
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .sidebar-brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: white;
            font-size: 18px;
        }

        .sidebar-brand-text {
            color: white;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: -.025em;
        }

        .sidebar-brand-sub {
            color: var(--sidebar-text);
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            padding: 0 12px;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: 600;
            color: var(--sidebar-text);
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            margin-bottom: 2px;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .nav-link.active {
            background: var(--sidebar-active);
            color: white;
        }

        .nav-link i {
            width: 20px;
            height: 20px;
            font-size: 18px;
            opacity: .7;
        }

        .nav-link.active i { opacity: 1; }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
        }

        /* ── Main Content ───────────────────────────────────────── */
        .admin-main {
            margin-left: 270px;
            min-height: 100vh;
        }

        .admin-topbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-breadcrumb {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .topbar-breadcrumb a {
            color: var(--text-muted);
            text-decoration: none;
        }

        .topbar-breadcrumb a:hover { color: var(--primary); }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
        }

        .topbar-user:hover { background: var(--primary-50); }

        .topbar-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .topbar-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .topbar-role {
            font-size: 12px;
            color: var(--text-muted);
        }

        .admin-content {
            padding: 32px;
        }

        /* ── Cards & Stats ──────────────────────────────────────── */
        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -.025em;
        }

        .page-subtitle {
            font-size: 15px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--stat-color, var(--primary));
        }

        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -.025em;
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
            margin-top: 4px;
        }

        /* ── Tables ─────────────────────────────────────────────── */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .card-body { padding: 0; }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8fafc;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .table td {
            padding: 14px 16px;
            font-size: 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .table tr:last-child td { border-bottom: none; }

        .table tr:hover td { background: #fafbfd; }

        /* ── Badges ─────────────────────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success { background: var(--success-light); color: #065f46; }
        .badge-danger { background: var(--danger-light); color: #991b1b; }
        .badge-warning { background: var(--warning-light); color: #92400e; }
        .badge-info { background: var(--info-light); color: #1e40af; }

        /* ── Buttons ────────────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, .25);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-50);
        }

        /* ── Content Grid ───────────────────────────────────────── */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .content-grid-full {
            grid-column: 1 / -1;
        }

        /* ── App-Like Mobile Responsive Layer ────────────────────── */
        .sidebar-toggle {
            display: none; background: none; border: none; font-size: 24px; cursor: pointer; color: #1e293b;
            padding: 8px; margin-left: -8px; border-radius: 8px;
        }
        .sidebar-overlay { 
            display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); 
            backdrop-filter: blur(4px); z-index: 90; opacity: 0; transition: opacity 0.3s;
        }

        @media (max-width: 1024px) {
            .admin-sidebar { transform: translateX(-100%); width: 280px; box-shadow: 4px 0 24px rgba(0,0,0,0.15); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .sidebar-overlay.open { display: block; opacity: 1; }
            .content-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            body { font-size: 15px; }
            .admin-content { padding: 16px; }
            .admin-topbar { padding: 16px; height: 60px; }
            .stats-grid { grid-template-columns: 1fr; gap: 16px; }
            .form-grid { grid-template-columns: 1fr; gap: 20px; }
            
            /* Native feeling touch targets & spacing */
            .card { border-radius: 16px; margin-bottom: 16px; }
            .card-header { padding: 18px 20px; font-size: 16px; }
            .card-body, .table-responsive { padding: 16px; }
            .form-control { padding: 14px 16px; font-size: 16px; } /* Prevents iOS zoom */
            .btn { padding: 14px 20px; width: 100%; justify-content: center; font-size: 15px; }
            
            .page-header { flex-direction: column; align-items: stretch !important; gap: 16px; margin-bottom: 24px !important; }
            .topbar-right .topbar-role { display: none; }
            .topbar-right .topbar-name { font-size: 13px; }

            /* App-Like Card Tables built automatically with data-label */
            .table-responsive { border: none; box-shadow: none; background: transparent; padding: 0; }
            .table-responsive .table { display: block; width: 100%; border: none; }
            .table thead, .table th { display: none; } /* Hide native headers */
            .table tbody { display: grid; gap: 16px; }
            .table tr { 
                display: flex; flex-direction: column; 
                background: white; border: 1px solid var(--border); 
                border-radius: 16px; padding: 16px; 
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); 
            }
            .table td { 
                display: flex; justify-content: space-between; align-items: center; 
                border: none; padding: 8px 0; text-align: right; font-size: 14px;
                border-bottom: 1px solid #f1f5f9;
            }
            .table td:last-child { border-bottom: none; }
            .table td::before { 
                content: attr(data-label); 
                font-weight: 600; color: #64748b; font-size: 12px; 
                text-transform: uppercase; margin-right: 16px; text-align: left;
            }
            
            /* Actions row at the bottom of the card */
            .table td[data-label="Acciones"], .table td:last-child { 
                margin-top: 8px; padding-top: 16px; 
                border-top: 1px solid var(--border); border-bottom: none;
                justify-content: flex-end; gap: 8px; flex-wrap: wrap; 
            }
            .table td[data-label="Acciones"]::before, .table td:last-child::before { display: none; }
            .table td[data-label="Acciones"] .btn, .table td:last-child .btn { width: auto; flex: 1; padding: 10px; font-size: 13px; text-align: center; justify-content: center; }
            .table td[data-label="Acciones"] form { display: contents; }
        }

        /* ── Sidebar Footer ─────────────────────────────────────── */
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .sidebar-footer form { width: 100%; }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 10px 12px;
            color: var(--sidebar-text);
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, .15);
            color: #fca5a5;
        }

        /* ── Animations ─────────────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp .4s ease forwards;
        }

        .animate-in:nth-child(1) { animation-delay: 0s; }
        .animate-in:nth-child(2) { animation-delay: .05s; }
        .animate-in:nth-child(3) { animation-delay: .1s; }
        .animate-in:nth-child(4) { animation-delay: .15s; }

        /* ── Pagination ─────────────────────────────────────── */
        nav[role="navigation"] { display: flex; flex-direction: column; align-items: center; gap: 12px; margin-top: 24px; padding: 16px 0; }
        nav[role="navigation"] .flex.justify-between { display: none; }
        nav[role="navigation"] > div:last-child .flex.flex-wrap { display: none; }
        nav[role="navigation"] a, nav[role="navigation"] span[aria-current] {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 38px; height: 38px; padding: 0 12px;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;
            border: 1px solid var(--border); color: var(--text-secondary);
            background: white; transition: var(--transition);
        }
        nav[role="navigation"] a:hover { background: var(--primary); color: white; border-color: var(--primary); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(30, 64, 175, .2); }
        nav[role="navigation"] span[aria-current] { background: var(--primary); color: white; border-color: var(--primary); }
        nav[role="navigation"] span.cursor-default { opacity: 0.4; pointer-events: none; }
        nav[role="navigation"] p.text-sm { color: var(--text-muted); font-size: 13px; }

                /* PREMIUM FORMS & UI OVERRIDES */
        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 14px;
            color: var(--text-primary);
            transition: var(--transition);
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        .form-label {
            display: block;
            font-weight: 600;
            font-size: 13.5px;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .card { 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); 
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
            padding: 24px;
            font-weight: 700;
            font-size: 18px;
        }
        .card-body { padding: 24px; }
        .table th { background: #f8fafc; font-weight: 700; color: #475569; font-size: 11px; letter-spacing: 0.05em; padding: 14px 24px; }
        .table td { padding: 16px 24px; color: #334155; font-size: 14px; }
        .table-responsive { overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0; }
        
        .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s; }
        .btn-primary { background: var(--primary); color: white; border: 1px solid var(--primary-dark); box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        /* Grid layouts for forms to look cleaner */
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
        .form-col-full { grid-column: 1 / -1; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
        .empty-state i { font-size: 48px; color: #cbd5e1; margin-bottom: 16px; display: block; }
/* ── Scrollbar ──────────────────────────────────────────── */
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">AM</div>
            <div>
                <div class="sidebar-brand-text">Autos Mrini</div>
                <div class="sidebar-brand-sub">Panel de Administración</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <i class="icon-layout-dashboard"></i>
                    Panel de Control
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Gestión</div>
                <a href="{{ route('admin.vehicles.index') }}" class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
                    <i class="icon-car"></i>
                    Vehículos
                </a>
                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <i class="icon-tags"></i>
                    Marcas
                </a>
                <a href="{{ route('admin.models.index') }}" class="nav-link {{ request()->routeIs('admin.models.*') ? 'active' : '' }}">
                    <i class="icon-list"></i>
                    Modelos
                </a>
                <a href="{{ route('admin.features.index') }}" class="nav-link {{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                    <i class="icon-settings-2"></i>
                    Características
                </a>
                @endif
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Comunicación</div>
                <a href="{{ route('admin.inquiries.index') }}" class="nav-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                    <i class="icon-mail"></i>
                    Consultas
                    @php $newInq = \App\Models\Inquiry::where('status','new')->count(); @endphp
                    @if($newInq > 0)
                        <span class="nav-badge">{{ $newInq }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Newsletter</div>
                <a href="{{ route('admin.newsletter.subscribers') }}" class="nav-link {{ request()->routeIs('admin.newsletter.subscribers*') ? 'active' : '' }}">
                    <i class="icon-users"></i>
                    Suscriptores
                    @php $activeSubs = \App\Models\Subscriber::active()->count(); @endphp
                    @if($activeSubs > 0)
                        <span class="nav-badge" style="background: #3b82f6;">{{ $activeSubs }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.newsletter.campaigns') }}" class="nav-link {{ request()->routeIs('admin.newsletter.campaign*') ? 'active' : '' }}">
                    <i class="icon-send"></i>
                    Campañas
                </a>
                <a href="{{ route('admin.newsletter.mail-status') }}" class="nav-link {{ request()->routeIs('admin.newsletter.mail-status') ? 'active' : '' }}">
                    <i class="icon-settings"></i>
                    Estado del Correo
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Configuración</div>
                @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="icon-users"></i>
                    Usuarios
                </a>
                @endif
                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="icon-settings"></i>
                    Ajustes del Sitio
                </a>
                <a href="{{ route('admin.hero-media.index') }}" class="nav-link {{ request()->routeIs('admin.hero-media.*') ? 'active' : '' }}">
                    <i class="icon-image"></i>
                    Ajustes de Portada
                </a>
                @endif
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('admin.profile.index') }}" class="logout-btn" style="background: transparent; color: var(--sidebar-text); text-align: left; margin-bottom: 8px; text-decoration: none; display: flex;">
                <i class="icon-user"></i>
                Mi Perfil
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="icon-log-out"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    ☰
                </button>
                <div class="topbar-breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Admin</a> / @yield('breadcrumb', 'Panel de Control')
                </div>
            </div>
            <div class="topbar-right">
                <a href="{{ route('admin.profile.index') }}" class="topbar-user" style="text-decoration: none; color: inherit;">
                    <div class="topbar-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>
                        <div class="topbar-name">{{ auth()->user()->name }}</div>
                        <div class="topbar-role">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                </a>
            </div>
        </header>

        <div class="admin-content">
            @if(session('success'))
                <div style="background: var(--success-light); color: #065f46; padding: 12px 20px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 14px; font-weight: 500; border: 1px solid #a7f3d0;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: var(--danger-light); color: #991b1b; padding: 12px 20px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 14px; font-weight: 500; border: 1px solid #fecaca;">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('open');
            if(document.getElementById('sidebar').classList.contains('open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Auto-generate data-labels for mobile responsive tables
            document.querySelectorAll('.table').forEach(table => {
                const headers = Array.from(table.querySelectorAll('th')).map(th => th.innerText.trim());
                table.querySelectorAll('tbody tr').forEach(row => {
                    row.querySelectorAll('td').forEach((td, index) => {
                        if(headers[index] && td.innerText.trim() !== '') {
                            td.setAttribute('data-label', headers[index]);
                        }
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
