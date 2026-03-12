<?php
$file = 'resources/views/admin/layouts/app.blade.php';
$content = file_get_contents($file);

// 1. Sidebar translations
$content = str_replace('Tableau de bord', 'Panel de Control', $content);
$content = str_replace('Gestion', 'Gestión', $content);
$content = str_replace('Véhicules', 'Vehículos', $content);
$content = str_replace('Marques', 'Marcas', $content);
$content = str_replace('Modèles', 'Modelos', $content);
$content = str_replace('Caractéristiques', 'Características', $content);
$content = str_replace('Communication', 'Comunicación', $content);
$content = str_replace('Demandes', 'Consultas', $content);
$content = str_replace('Configuration', 'Configuración', $content);
$content = str_replace('Utilisateurs', 'Usuarios', $content);
$content = str_replace('Paramètres', 'Ajustes del Sitio', $content);

// 2. Fix broken @if blocks in sidebar
$sidebarStart = strpos($content, '<nav class="sidebar-nav">');
$sidebarEnd = strpos($content, '</nav>', $sidebarStart) + 6;

$newSidebar = <<<'BLADE'
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
                @endif
            </div>
        </nav>
BLADE;

$content = substr_replace($content, $newSidebar, $sidebarStart, $sidebarEnd - $sidebarStart);

file_put_contents($file, $content);
echo "Admin layout translated and fixed.\n";
