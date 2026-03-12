@extends('admin.layouts.app')

@section('title', 'Gestión de Usuarios')
@section('breadcrumb', 'Ajustes / Usuarios')

@section('content')
<div class="page-header">
    <h1 class="page-title">Administración de Usuarios</h1>
    <a href="{{ route('admin.users.create') }}" class="btn" style="background:#3b82f6; color:white; padding:10px 20px; font-weight:600;">+ Añadir Usuario</a>
</div>

@if(session('success'))
<div style="background:#065f46; color:#34d399; padding:16px; margin-bottom:24px; border-radius:12px; font-weight:500;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background:#7f1d1d; color:#fca5a5; padding:16px; margin-bottom:24px; border-radius:12px; font-weight:500;">
    {{ session('error') }}
</div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre de Usuario</th>
                    <th>Correo Electrónico</th>
                    <th>Rol en el Sistema</th>
                    <th>Fecha de Registro</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #1e293b;">{{ $user->name }}</div>
                    </td>
                    <td style="color: #64748b;">
                        {{ $user->email }}
                    </td>
                    <td>
                        @if($user->role === 'super_admin')
                            <span class="badge" style="background: rgba(139,92,246,.1); color: #8b5cf6;">Super Admin</span>
                        @elseif($user->role === 'admin')
                            <span class="badge" style="background: rgba(59,130,246,.1); color: #3b82f6;">Administrador</span>
                        @else
                            <span class="badge" style="background: rgba(16,185,129,.1); color: #10b981;">Editor</span>
                        @endif
                    </td>
                    <td style="color:#64748b; font-size:13px;">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex; justify-content:flex-end; gap:8px;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline" style="background:white; border: 1px solid #e2e8f0; color:#475569; padding:8px 12px;">Editar</a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar el acceso a este usuario irrevocablemente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background: rgba(239, 68, 68, .1); color: #ef4444; border: 1px solid rgba(239, 68, 68, .2); padding: 8px 12px; border-radius: 8px; font-weight: 600;">Eliminar</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i>👥</i>
                            <h3>No se encontraron usuarios</h3>
                            <p>Parece que el sistema no tiene otros administradores.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div style="padding: 24px; border-top: 1px solid #e2e8f0;">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection