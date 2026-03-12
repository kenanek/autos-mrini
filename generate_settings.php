<?php

$files = [];

// 1. Model
$files['app/Models/Setting.php'] = <<<'PHP'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getVal($key, $default = null)
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return self::pluck('value', 'key')->toArray();
        });
        return $settings[$key] ?? $default;
    }

    public static function setVal($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('site_settings');
    }
}
PHP;

// 2. Controller
$files['app/Http/Controllers/Admin/SettingController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $keys = [
            'site_name', 'phone', 'whatsapp', 'email', 'address', 'opening_hours',
            'google_maps_embed', 'google_maps_link', 'footer_description',
            'about_page_text', 'financing_page_text', 'facebook_url',
            'instagram_url', 'tiktok_url', 'default_meta_title', 'default_meta_description'
        ];

        foreach($keys as $key) {
            Setting::setVal($key, $request->input($key, ''));
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::setVal('logo', $path);
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::setVal('favicon', $path);
        }

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
PHP;

// 3. Admin View
$files['resources/views/admin/settings/index.blade.php'] = <<<'HTML'
@extends('layouts.admin')
@section('title', 'Paramètres du site')

@section('content')
<div class="header-actions" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="font-size:24px; font-weight:600; color:white;">Configuración del Sitio</h1>
</div>

@if(session('success'))
<div style="background:rgba(16, 185, 129, 0.1); color:#10b981; border:1px solid rgba(16, 185, 129, 0.2); padding:16px; border-radius:8px; margin-bottom:24px;">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" style="background:rgba(255,255,255,0.05); padding:32px; border-radius:12px; border:1px solid rgba(255,255,255,0.1);">
    @csrf
    @method('PUT')

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:32px;">
        <!-- General Information -->
        <div>
            <h3 style="color:white; margin-bottom:16px; font-size:18px; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:8px;">Información General</h3>
            
            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Nombre del Sitio</label>
                <input type="text" name="site_name" value="{{ \App\Models\Setting::getVal('site_name', 'Autos Mrini') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Email de Contacto</label>
                <input type="email" name="email" value="{{ \App\Models\Setting::getVal('email') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Teléfono Principal</label>
                <input type="text" name="phone" value="{{ \App\Models\Setting::getVal('phone') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Número WhatsApp (Solo números formato internacional. Ej: 34600000000)</label>
                <input type="text" name="whatsapp" value="{{ \App\Models\Setting::getVal('whatsapp') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Horario de Apertura</label>
                <textarea name="opening_hours" rows="3" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">{{ \App\Models\Setting::getVal('opening_hours') }}</textarea>
            </div>
            
            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Pie de página (Descripción corta)</label>
                <textarea name="footer_description" rows="3" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">{{ \App\Models\Setting::getVal('footer_description', 'Tu concesionario premium de vehículos de ocasión y seminuevos en Sevilla, España. Calidad y confianza garantizada.') }}</textarea>
            </div>
        </div>

        <!-- SEO & Media & Location -->
        <div>
            <h3 style="color:white; margin-bottom:16px; font-size:18px; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:8px;">Ubicación y Medios</h3>

            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Dirección Completa</label>
                <input type="text" name="address" value="{{ \App\Models\Setting::getVal('address') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Enlace de Google Maps</label>
                <input type="url" name="google_maps_link" value="{{ \App\Models\Setting::getVal('google_maps_link') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Código Embed de Google Maps (Iframe SRC solo)</label>
                <input type="text" name="google_maps_embed" value="{{ \App\Models\Setting::getVal('google_maps_embed') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;" placeholder="https://www.google.com/maps/embed?pb=...">
            </div>

            <div  style="display:flex; gap:16px; margin-bottom:16px;">
                <div style="flex:1;">
                    <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Logo (Sube para reemplazar)</label>
                    <input type="file" name="logo" accept="image/*" style="width:100%; color:#94a3b8;">
                    @if(\App\Models\Setting::getVal('logo'))
                    <img src="{{ asset('storage/' . \App\Models\Setting::getVal('logo')) }}" style="height:40px; margin-top:8px;">
                    @endif
                </div>
                <div style="flex:1;">
                    <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Favicon</label>
                    <input type="file" name="favicon" accept="image/*" style="width:100%; color:#94a3b8;">
                    @if(\App\Models\Setting::getVal('favicon'))
                    <img src="{{ asset('storage/' . \App\Models\Setting::getVal('favicon')) }}" style="height:32px; margin-top:8px;">
                    @endif
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Meta Título por Defecto</label>
                <input type="text" name="default_meta_title" value="{{ \App\Models\Setting::getVal('default_meta_title', 'Autos Mrini - Concesionario Premium en Sevilla') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Meta Descripción por Defecto</label>
                <textarea name="default_meta_description" rows="2" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">{{ \App\Models\Setting::getVal('default_meta_description', 'Encuentra vehículos premium, seminuevos y de ocasión con las mejores financiaciones en Autos Mrini.') }}</textarea>
            </div>
        </div>
    </div>
    
    <div style="margin-top:32px; padding-top:24px; border-top:1px solid rgba(255,255,255,0.1);">
        <h3 style="color:white; margin-bottom:16px; font-size:18px;">Contenidos de Páginas</h3>
        
        <div style="margin-bottom:16px;">
            <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Texto Página Nosotros (Párrafos largos)</label>
            <textarea name="about_page_text" rows="5" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">{{ \App\Models\Setting::getVal('about_page_text') }}</textarea>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Bancos Asociados (Para página Financiación)</label>
            <input type="text" name="financing_page_text" value="{{ \App\Models\Setting::getVal('financing_page_text', 'Santander, BBVA, Cetelem') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;" placeholder="Ej: Santander, BBVA">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px;">
            <div>
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Facebook URL</label>
                <input type="url" name="facebook_url" value="{{ \App\Models\Setting::getVal('facebook_url') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>
            <div>
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">Instagram URL</label>
                <input type="url" name="instagram_url" value="{{ \App\Models\Setting::getVal('instagram_url') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>
            <div>
                <label style="display:block; color:#94a3b8; font-size:14px; margin-bottom:8px;">TikTok URL</label>
                <input type="url" name="tiktok_url" value="{{ \App\Models\Setting::getVal('tiktok_url') }}" style="width:100%; padding:10px 14px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:6px; color:white;">
            </div>
        </div>
    </div>

    <div style="margin-top:32px;">
        <button type="submit" style="background:#3b82f6; color:white; padding:12px 24px; border:none; border-radius:6px; font-weight:600; cursor:pointer; font-size:15px;">Guardar Configuración</button>
    </div>
</form>

@endsection
HTML;

foreach ($files as $path => $content) {
    $fullPath = __DIR__ . '/' . $path;
    $dir = dirname($fullPath);
    if (!is_dir($dir))
        mkdir($dir, 0755, true);
    file_put_contents($fullPath, $content);
}

// Write the migration explicitly
$migrationContent = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
PHP;

$files = glob(__DIR__ . '/database/migrations/*_create_settings_table.php');
if (empty($files)) {
    $migrationFileName = date('Y_m_d_His') . '_create_settings_table.php';
    file_put_contents(__DIR__ . '/database/migrations/' . $migrationFileName, $migrationContent);
}

// Update routes/web.php
$routes = file_get_contents(__DIR__ . '/routes/web.php');
if (strpos($routes, "Route::get('/settings',") !== false && strpos($routes, "App\Http\Controllers\Admin\SettingController") === false) {
    // Replace old placeholder settings route
    $pattern = "/Route::get\('\/settings',\s*function\s*\(\)\s*\{[\s\S]*?\}\)->name\('settings\.index'\);/";
    $replacement = <<<'PHP'
Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
PHP;
    $routes = preg_replace($pattern, $replacement, $routes);
}
else if (strpos($routes, "App\Http\Controllers\Admin\SettingController") === false) {
    // Add it dynamically inside admin group
    $routes = str_replace(
        "Route::get('/dashboard',",
        "Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');\n    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');\n    Route::get('/dashboard',",
        $routes
    );
}
// Remove old `settings.index` if we just appended
if (strpos($routes, "Route::get('/settings',") !== false) {
    $routes = preg_replace("/Route::get\('\/settings',\s*function\s*\(\)\s*\{[\s\S]*?\}\)->name\('settings\.index'\);/", "", $routes);
}

file_put_contents(__DIR__ . '/routes/web.php', $routes);

echo "Settings module generated.\n";
