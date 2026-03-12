<style>
    /* Responsive grid specifically for the user form */
    .form-wrapper { padding: 32px; display: flex; flex-direction: column; gap: 40px; }
    
    .form-section { border-bottom: 1px solid #e2e8f0; padding-bottom: 32px; }
    .form-section:last-child { border-bottom: none; padding-bottom: 0; }
    
    .form-section-header { margin-bottom: 24px; }
    .form-section-title { font-size: 18px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
    .form-section-desc { font-size: 14px; color: #64748b; margin-top: 0; }
    
    .form-grid { display: grid; grid-template-columns: repeat(1, 1fr); gap: 24px; }
    @media (min-width: 768px) {
        .form-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    .form-group-full { grid-column: 1 / -1; }
    
    .role-grid { display: grid; grid-template-columns: 1fr; gap: 16px; margin-top: 12px; }
    @media (min-width: 768px) {
        .role-grid { grid-template-columns: repeat(3, 1fr); }
    }
    
    .role-card {
        border: 2px solid #e2e8f0; border-radius: 12px; padding: 16px; position: relative; cursor: pointer; transition: all 0.2s; background: white;
    }
    .role-card:hover { border-color: #cbd5e1; background: #f8fafc; }
    .role-card input[type="radio"] { position: absolute; opacity: 0; }
    
    .role-card input[type="radio"]:checked + .role-content {
        color: #3b82f6; 
    }
    .role-card:has(input[type="radio"]:checked) {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
    }
    
    .role-icon { font-size: 24px; margin-bottom: 12px; display: inline-block; }
    .role-name { font-weight: 700; font-size: 15px; color: #1e293b; margin-bottom: 4px; }
    .role-desc { font-size: 12px; color: #64748b; line-height: 1.4; }
    
    .input-wrapper { position: relative; }
    .input-error { border-color: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important; }
    .error-msg { color: #ef4444; font-size: 12px; font-weight: 500; margin-top: 6px; display: block; }
</style>

<div class="form-wrapper">
    <!-- SECTION 1: Detalles Personales -->
    <div class="form-section">
        <div class="form-section-header">
            <h3 class="form-section-title">Detalles Personales</h3>
            <p class="form-section-desc">Información básica de contacto e identificación del usuario en la plataforma.</p>
        </div>
        
        <div class="form-grid">
            <div class="form-group-full">
                <label class="form-label" for="name">Nombre Completo *</label>
                <div class="input-wrapper">
                    <input type="text" id="name" name="name" class="form-control @error('name') input-error @enderror" value="{{ old('name', $user->name ?? '') }}" placeholder="Ej: Hassan Mrini" required>
                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div>
                <label class="form-label" for="email">Correo Electrónico *</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" class="form-control @error('email') input-error @enderror" value="{{ old('email', $user->email ?? '') }}" placeholder="ejemplo@autosmrini.ma" required autocomplete="off">
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div>
                <label class="form-label" for="phone">Teléfono Móvil (Opcional)</label>
                <div class="input-wrapper">
                    <input type="text" id="phone" name="phone" class="form-control @error('phone') input-error @enderror" value="{{ old('phone', $user->phone ?? '') }}" placeholder="+212 600 000 000">
                    @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- SECTION 2: Seguridad de la Cuenta -->
    <div class="form-section">
        <div class="form-section-header">
            <h3 class="form-section-title">Seguridad de la Cuenta</h3>
            <p class="form-section-desc">
                @if(isset($user))
                    Rellena estos campos solo si deseas cambiar la contraseña de {{ $user->name }}. Déjalos en blanco para mantener la actual.
                @else
                    Configura la contraseña inicial. El usuario debe introducir una contraseña segura para acceder al panel.
                @endif
            </p>
        </div>

        <div class="form-grid">
            <div>
                <label class="form-label" for="password">Contraseña @if(!isset($user)) * @endif</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-control @error('password') input-error @enderror" placeholder="{{ isset($user) ? 'Solo si quieres cambiarla' : 'Mínimo 8 caracteres' }}" @if(!isset($user)) required @endif autocomplete="new-password">
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div>
                <label class="form-label" for="password_confirmation">Confirmar Contraseña @if(!isset($user)) * @endif</label>
                <div class="input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repite la contraseña" @if(!isset($user)) required @endif autocomplete="new-password">
                </div>
            </div>
        </div>
    </div>
    
    <!-- SECTION 3: Permisos y Roles -->
    <div class="form-section">
        <div class="form-section-header">
            <h3 class="form-section-title">Permisos de Acceso</h3>
            <p class="form-section-desc">Selecciona el nivel de acceso al panel que necesita este usuario.</p>
        </div>
        
        @php
            $currentRole = old('role', $user->role ?? 'editor');
        @endphp
        
        <div class="role-grid">
            <!-- Super Admin -->
            <label class="role-card">
                <input type="radio" name="role" value="super_admin" @if($currentRole === 'super_admin') checked @endif>
                <div class="role-content">
                    <div class="role-icon">⚡</div>
                    <div class="role-name">Super Administrador</div>
                    <div class="role-desc">Acceso total al sistema sin restricciones. Puede crear, editar y eliminar a otros usuarios y cambiar configuraciones avanzadas.</div>
                </div>
            </label>
            
            <!-- Admin -->
            <label class="role-card">
                <input type="radio" name="role" value="admin" @if($currentRole === 'admin') checked @endif>
                <div class="role-content">
                    <div class="role-icon">💼</div>
                    <div class="role-name">Administrador</div>
                    <div class="role-desc">Gestión integral del inventario (Vehículos, Marcas, Modelos), mensajes, y acceso a los Ajustes del Sitio. No puede gestionar usuarios.</div>
                </div>
            </label>
            
            <!-- Editor -->
            <label class="role-card">
                <input type="radio" name="role" value="editor" @if($currentRole === 'editor') checked @endif>
                <div class="role-content">
                    <div class="role-icon">✏️</div>
                    <div class="role-name">Editor (Operador)</div>
                    <div class="role-desc">Acceso limitado y seguro. Ideal para personal de ventas. Solo puede gestionar el inventario de Vehículos y leer consultas de clientes.</div>
                </div>
            </label>
        </div>
        @error('role') <div class="error-msg" style="margin-top:12px;">{{ $message }}</div> @enderror
    </div>
</div>
