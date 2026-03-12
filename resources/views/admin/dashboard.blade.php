@extends('admin.layouts.app')

@section('title', 'Panel de Control')
@section('breadcrumb', 'Panel de Control')

@section('content')
<div class="page-header">
    <h1 class="page-title">Panel de Control</h1>
    <p class="page-subtitle">Bienvenido, {{ auth()->user()->name }}. Aquí tienes un resumen de la actividad.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card animate-in" style="--stat-color: #3b82f6">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_vehicles'] }}</div>
                <div class="stat-label">Total Vehículos</div>
            </div>
            <div class="stat-icon" style="background: rgba(59,130,246,.1); color: #3b82f6;">
                🚗
            </div>
        </div>
    </div>

    <div class="stat-card animate-in" style="--stat-color: #10b981">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['available_vehicles'] }}</div>
                <div class="stat-label">Disponibles</div>
            </div>
            <div class="stat-icon" style="background: rgba(16,185,129,.1); color: #10b981;">
                ✅
            </div>
        </div>
    </div>

    <div class="stat-card animate-in" style="--stat-color: #f59e0b">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['sold_vehicles'] }}</div>
                <div class="stat-label">Vendidos</div>
            </div>
            <div class="stat-icon" style="background: rgba(245,158,11,.1); color: #f59e0b;">
                🏷️
            </div>
        </div>
    </div>

    <div class="stat-card animate-in" style="--stat-color: #ef4444">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['new_inquiries'] }}</div>
                <div class="stat-label">Nuevas Consultas</div>
            </div>
            <div class="stat-icon" style="background: rgba(239,68,68,.1); color: #ef4444;">
                📩
            </div>
        </div>
    </div>

    <div class="stat-card animate-in" style="--stat-color: #8b5cf6">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['featured_vehicles'] }}</div>
                <div class="stat-label">Destacados</div>
            </div>
            <div class="stat-icon" style="background: rgba(139,92,246,.1); color: #8b5cf6;">
                ⭐
            </div>
        </div>
    </div>

    <div class="stat-card animate-in" style="--stat-color: #06b6d4">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_brands'] }}</div>
                <div class="stat-label">Marcas</div>
            </div>
            <div class="stat-icon" style="background: rgba(6,182,212,.1); color: #06b6d4;">
                🏢
            </div>
        </div>
    </div>

    <div class="stat-card animate-in" style="--stat-color: #ec4899">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total_inquiries'] }}</div>
                <div class="stat-label">Total Consultas</div>
            </div>
            <div class="stat-icon" style="background: rgba(236,72,153,.1); color: #ec4899;">
                📧
            </div>
        </div>
    </div>

    <div class="stat-card animate-in" style="--stat-color: #14b8a6">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($stats['total_views']) }}</div>
                <div class="stat-label">Vistas Totales</div>
            </div>
            <div class="stat-icon" style="background: rgba(20,184,166,.1); color: #14b8a6;">
                👁️
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="content-grid">
    <!-- Recent Vehicles -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Vehículos Recientes</h3>
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-sm btn-outline">Ver todos</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Vehículo</th>
                        <th>Precio</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentVehicles as $vehicle)
                    <tr>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ Str::limit($vehicle->title, 35) }}</div>
                            <div style="color: var(--text-muted); font-size: 12px;">{{ $vehicle->brand->name }} · {{ $vehicle->year }}</div>
                        </td>
                        <td style="font-weight: 600; white-space: nowrap;">{{ $vehicle->formatted_price }}</td>
                        <td>
                            @switch($vehicle->status)
                                @case('available')
                                    <span class="badge badge-success">Disponible</span>
                                    @break
                                @case('sold')
                                    <span class="badge badge-danger">Vendido</span>
                                    @break
                                @case('reserved')
                                    <span class="badge badge-warning">Reservado</span>
                                    @break
                                @default
                                    <span class="badge badge-info">Borrador</span>
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Inquiries -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Últimas Consultas</h3>
            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm btn-outline">Ver todas</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentInquiries as $inquiry)
                    <tr>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ $inquiry->name }}</div>
                            <div style="color: var(--text-muted); font-size: 12px;">{{ Str::limit($inquiry->subject ?? $inquiry->message, 30) }}</div>
                        </td>
                        <td>
                            @switch($inquiry->type)
                                @case('vehicle')
                                    <span class="badge badge-info">Vehículo</span>
                                    @break
                                @case('test_drive')
                                    <span class="badge badge-warning">Prueba</span>
                                    @break
                                @case('financing')
                                    <span class="badge badge-success">Financiación</span>
                                    @break
                                @default
                                    <span class="badge badge-info">General</span>
                            @endswitch
                        </td>
                        <td>
                            @switch($inquiry->status)
                                @case('new')
                                    <span class="badge badge-danger">Nueva</span>
                                    @break
                                @case('read')
                                    <span class="badge badge-warning">Leída</span>
                                    @break
                                @case('replied')
                                    <span class="badge badge-success">Respondida</span>
                                    @break
                                @default
                                    <span class="badge badge-info">{{ ucfirst($inquiry->status) }}</span>
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Viewed Vehicles -->
    <div class="card content-grid-full">
        <div class="card-header">
            <h3 class="card-title">Vehículos Más Vistos</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Vehículo</th>
                        <th>Marca</th>
                        <th>Año</th>
                        <th>Precio</th>
                        <th>Vistas</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topViewed as $vehicle)
                    <tr>
                        <td style="font-weight: 600;">{{ Str::limit($vehicle->title, 40) }}</td>
                        <td>{{ $vehicle->brand->name }}</td>
                        <td>{{ $vehicle->year }}</td>
                        <td style="font-weight: 600; white-space: nowrap;">{{ $vehicle->formatted_price }}</td>
                        <td>
                            <span style="font-weight: 700; color: var(--primary);">{{ number_format($vehicle->views_count) }}</span>
                        </td>
                        <td>
                            <span class="badge badge-success">Disponible</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
