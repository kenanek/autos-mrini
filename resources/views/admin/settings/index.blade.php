@extends('admin.layouts.app')
@section('title', 'Ajustes del Sitio')
@section('breadcrumb', 'Configuración / Ajustes')

@section('content')
<style>
    .form-wrapper { padding: 32px; display: flex; flex-direction: column; gap: 40px; }
    .form-section { border-bottom: 1px solid #e2e8f0; padding-bottom: 32px; }
    .form-section:last-child { border-bottom: none; padding-bottom: 0; }
    .form-section-header { margin-bottom: 24px; }
    .form-section-title { font-size: 18px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
    .input-wrapper { position: relative; }
    .form-label { display:block;margin-bottom:8px;font-weight:600;font-size:14px;color:#1e293b; }
    .form-control { width:100%;padding:12px 16px;border-radius:12px;border:1px solid #cbd5e1;font-size:15px;background:white;transition:0.2s; color:#1e293b; }
    .form-control:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
    textarea.form-control { resize: vertical; min-height: 100px; }
</style>

<div class="page-header">
    <h1 class="page-title">Ajustes del Sitio</h1>
    <p class="page-subtitle">Modifica la información de contacto, textos y recursos de la web pública.</p>
</div>



<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="card" style="overflow: hidden; background: white;">
        <div class="form-wrapper">
            <!-- General Contact info -->
            <div class="form-section">
                <div class="form-section-header"><h3 class="form-section-title">Información General</h3></div>
                <div class="form-grid">
                    <div>
                        <label class="form-label">Tema por Defecto de la Web</label>
                        <select name="default_theme" class="form-control">
                            <option value="dark" {{ \App\Models\Setting::getVal('default_theme', 'dark') === 'dark' ? 'selected' : '' }}>Modo Oscuro (Dark)</option>
                            <option value="light" {{ \App\Models\Setting::getVal('default_theme', 'dark') === 'light' ? 'selected' : '' }}>Modo Claro (Light)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Nombre del Sitio</label>
                        <div class="input-wrapper"><input type="text" name="site_name" value="{{ \App\Models\Setting::getVal('site_name', 'Autos Mrini') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">Moneda Principal</label>
                        <select name="currency" class="form-control">
                            <option value="€" {{ \App\Models\Setting::getVal('currency', '€') === '€' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="MAD" {{ \App\Models\Setting::getVal('currency', '€') === 'MAD' ? 'selected' : '' }}>Dirham (MAD)</option>
                            <option value="$" {{ \App\Models\Setting::getVal('currency', '€') === '$' ? 'selected' : '' }}>Dólar ($)</option>
                            <option value="£" {{ \App\Models\Setting::getVal('currency', '€') === '£' ? 'selected' : '' }}>Libra (£)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Email de Contacto</label>
                        <div class="input-wrapper"><input type="email" name="email" value="{{ \App\Models\Setting::getVal('email') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">Teléfono Principal</label>
                        <div class="input-wrapper"><input type="text" name="phone" value="{{ \App\Models\Setting::getVal('phone') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">Número WhatsApp (Internacional)</label>
                        <div class="input-wrapper"><input type="text" name="whatsapp" value="{{ \App\Models\Setting::getVal('whatsapp') }}" class="form-control" placeholder="Ej: 34600000000"></div>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label class="form-label">Horario de Apertura</label>
                        <div class="input-wrapper"><textarea name="opening_hours" class="form-control" rows="3">{{ \App\Models\Setting::getVal('opening_hours') }}</textarea></div>
                    </div>
                </div>
            </div>

            <!-- SEO & Location info -->
            <div class="form-section">
                <div class="form-section-header"><h3 class="form-section-title">Ubicación y Medios</h3></div>
                <div class="form-grid">
                    <div style="grid-column: 1 / -1;">
                        <label class="form-label">Dirección Completa</label>
                        <div class="input-wrapper"><input type="text" name="address" value="{{ \App\Models\Setting::getVal('address') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">Enlace de Google Maps</label>
                        <div class="input-wrapper"><input type="url" name="google_maps_link" value="{{ \App\Models\Setting::getVal('google_maps_link') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">Código Embed de Google Maps (Iframe SRC solo)</label>
                        <div class="input-wrapper"><input type="text" name="google_maps_embed" value="{{ \App\Models\Setting::getVal('google_maps_embed') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">Logo (Sube para reemplazar)</label>
                        <input type="file" name="logo" accept="image/*" style="width:100%; font-size: 14px; margin-top:8px;">
                        @if(\App\Models\Setting::getVal('logo'))
                            <div style="margin-top:12px; padding:12px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0; display:inline-block;"><img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(\App\Models\Setting::getVal('logo')) }}" style="height:40px;"></div>
                        @endif
                    </div>
                    <div>
                        <label class="form-label">Favicon</label>
                        <input type="file" name="favicon" accept="image/*" style="width:100%; font-size: 14px; margin-top:8px;">
                        @if(\App\Models\Setting::getVal('favicon'))
                            <div style="margin-top:12px; padding:8px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0; display:inline-block;"><img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(\App\Models\Setting::getVal('favicon')) }}" style="height:32px;"></div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content SEO Social -->
            <div class="form-section">
                <div class="form-section-header"><h3 class="form-section-title">Contenidos y Redes Sociales</h3></div>
                <div class="form-grid">
                    <div>
                        <label class="form-label">Meta Título por Defecto</label>
                        <div class="input-wrapper"><input type="text" name="default_meta_title" value="{{ \App\Models\Setting::getVal('default_meta_title') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">Meta Descripción por Defecto</label>
                        <div class="input-wrapper"><textarea name="default_meta_description" class="form-control" rows="2">{{ \App\Models\Setting::getVal('default_meta_description') }}</textarea></div>
                    </div>
                    <div>
                        <label class="form-label">Pie de página (Descripción corta)</label>
                        <div class="input-wrapper"><textarea name="footer_description" class="form-control" rows="2">{{ \App\Models\Setting::getVal('footer_description') }}</textarea></div>
                    </div>
                    <div>
                        <label class="form-label">Texto Página Nosotros</label>
                        <div class="input-wrapper"><textarea name="about_page_text" class="form-control" rows="5">{{ \App\Models\Setting::getVal('about_page_text') }}</textarea></div>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label class="form-label">Bancos Asociados (Página Financiación)</label>
                        <div class="input-wrapper"><input type="text" name="financing_page_text" value="{{ \App\Models\Setting::getVal('financing_page_text') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">Facebook URL</label>
                        <div class="input-wrapper"><input type="url" name="facebook_url" value="{{ \App\Models\Setting::getVal('facebook_url') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">Instagram URL</label>
                        <div class="input-wrapper"><input type="url" name="instagram_url" value="{{ \App\Models\Setting::getVal('instagram_url') }}" class="form-control"></div>
                    </div>
                    <div>
                        <label class="form-label">TikTok URL</label>
                        <div class="input-wrapper"><input type="url" name="tiktok_url" value="{{ \App\Models\Setting::getVal('tiktok_url') }}" class="form-control"></div>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: flex-end; gap: 16px;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 32px;"><i class="icon-save" style="margin-right:8px;"></i> Guardar Ajustes</button>
        </div>
    </div>
</form>
@endsection