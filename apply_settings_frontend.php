<?php

// A script to replace placeholders with dynamic settings

$layoutPath = __DIR__ . '/resources/views/layouts/public.blade.php';
$layoutConfig = file_get_contents($layoutPath);
$layoutConfig = preg_replace('/<title>@yield\(\'title\', \'Autos Mrini\'\) - Concesionario Premium en Sevilla<\/title>/', '<title>@yield(\'title\', \App\Models\Setting::getVal(\'default_meta_title\', \'Autos Mrini - Concesionario Premium en Sevilla\'))</title>', $layoutConfig);
$layoutConfig = preg_replace('/<meta name="description" content="@yield\(\'meta_description\', \'Autos Mrini es tu concesionario de confianza en Sevilla, España\. Encuentra vehículos premium, seminuevos y de ocasión con las mejores financiaciones\.\'\)">/', '<meta name="description" content="@yield(\'meta_description\', \App\Models\Setting::getVal(\'default_meta_description\', \'Encuentra vehículos premium, seminuevos y de ocasión con las mejores financiaciones.\'))">', $layoutConfig);
$layoutConfig = str_replace('<!-- [INSERT_LOGO_SVG_OR_IMG_HERE] -->', '', $layoutConfig);
$layoutConfig = preg_replace('/<a href="\{\{ route\(\'home\'\) \}\}" class="logo">.*?Autos <span>Mrini<\/span>\s*<\/a>/s', '<a href="{{ route(\'home\') }}" class="logo">
                @if(\App\Models\Setting::getVal(\'logo\'))
                    <img src="{{ asset(\'storage/\' . \App\Models\Setting::getVal(\'logo\')) }}" alt="{{ \App\Models\Setting::getVal(\'site_name\', \'Autos Mrini\') }}" style="max-height: 40px;">
                @else
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent)"><circle cx="12" cy="12" r="10"></circle><path d="M16 12l-4-4-4 4M12 8v8"></path></svg>
                    {{ \App\Models\Setting::getVal(\'site_name\', \'Autos Mrini\') }}
                @endif
            </a>', $layoutConfig);

// Footer logo
$layoutConfig = preg_replace('/<a href="\{\{ route\(\'home\'\) \}\}" class="logo" style="color:white; margin-bottom:16px;">Autos <span style="color:var\(--accent\)">Mrini<\/span><\/a>/', '<a href="{{ route(\'home\') }}" class="logo" style="color:white; margin-bottom:16px;">{{ \App\Models\Setting::getVal(\'site_name\', \'Autos Mrini\') }}</a>', $layoutConfig);
$layoutConfig = str_replace('<p style="color:#94a3b8; font-size:14px; margin-bottom:20px;">Tu concesionario premium de vehículos de ocasión y seminuevos en Sevilla, España. Calidad y confianza garantizada.</p>', '<p style="color:#94a3b8; font-size:14px; margin-bottom:20px;">{{ \App\Models\Setting::getVal(\'footer_description\', \'Tu concesionario premium de vehículos de ocasión y seminuevos en Sevilla, España. Calidad y confianza garantizada.\') }}</p>', $layoutConfig);

$layoutConfig = str_replace('📍 [INSERTE SU DIRECCIÓN AQUÍ]', '📍 {{ \App\Models\Setting::getVal(\'address\', \'Av. de la Innovación, 45, Sevilla\') }}', $layoutConfig);
$layoutConfig = str_replace('📞 [INSERTE SU TELÉFONO AQUÍ]', '📞 {{ \App\Models\Setting::getVal(\'phone\', \'+34 954 00 00 00\') }}', $layoutConfig);
$layoutConfig = str_replace('✉️ [INSERTE SU EMAIL AQUÍ]', '✉️ {{ \App\Models\Setting::getVal(\'email\', \'info@autosmrini.es\') }}', $layoutConfig);

// Inject favicon if exists
$layoutConfig = str_replace('</title>', "</title>\n    @if(\App\Models\Setting::getVal('favicon'))<link rel=\"icon\" href=\"{{ asset('storage/' . \App\Models\Setting::getVal('favicon')) }}\">@endif", $layoutConfig);

file_put_contents($layoutPath, $layoutConfig);

$contactPath = __DIR__ . '/resources/views/pages/contact.blade.php';
$contactConfig = file_get_contents($contactPath);
$contactConfig = str_replace('[INSERTE DIRECCIÓN LÍNEA 1]<br>[INSERTE CÓDIGO POSTAL Y CIUDAD]', '{!! nl2br(e(\App\Models\Setting::getVal(\'address\', "Av. de la Innovación, 45\n41020 Sevilla, España"))) !!}', $contactConfig);
$contactConfig = str_replace('[TELÉFONO FIJO]<br>[TELÉFONO MÓVIL/WHATSAPP]', '{{ \App\Models\Setting::getVal(\'phone\', \'+34 954 00 00 00\') }}<br>{{ \App\Models\Setting::getVal(\'whatsapp\') ? \'+34 \' . \App\Models\Setting::getVal(\'whatsapp\') . \' (WhatsApp)\' : \'+34 600 00 00 00 (WhatsApp)\' }}', $contactConfig);
$contactConfig = str_replace('[EMAIL PRINCIPAL]<br>[EMAIL SECUNDARIO]', '{{ \App\Models\Setting::getVal(\'email\', \'info@autosmrini.es\') }}', $contactConfig);
$contactConfig = str_replace('[HORARIO LUNES-VIERNES]<br>[HORARIO FIN DE SEMANA]', '{!! nl2br(e(\App\Models\Setting::getVal(\'opening_hours\', "Lunes - Viernes: 10:00h - 14:00h y 16:30h - 20:30h\nSábados: 10:00h - 14:00h"))) !!}', $contactConfig);
file_put_contents($contactPath, $contactConfig);

$locPath = __DIR__ . '/resources/views/pages/location.blade.php';
$locConfig = file_get_contents($locPath);
$locConfig = str_replace('<!-- [INSERTE_LINK_EMBED_GOOGLE_MAPS_AQUI] -->' . "\n" . '            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d101408.21721869805!2d-6.064509172605822!3d37.37535004149959!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd126c1114be6291%3A0x34f018621cfe5648!2sSeville!5e0!3m2!1sen!2ses!4v1700000000000!5m2!1sen!2ses"', '<iframe src="{{ \App\Models\Setting::getVal(\'google_maps_embed\', \'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d101408.21721869805!2d-6.064509172605822!3d37.37535004149959!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd126c1114be6291%3A0x34f018621cfe5648!2sSeville!5e0!3m2!1sen!2ses!4v1700000000000!5m2!1sen!2ses\') }}"', $locConfig);
$locConfig = str_replace('Autos Mrini', '{{ \App\Models\Setting::getVal(\'site_name\', \'Autos Mrini\') }}', $locConfig);
$locConfig = str_replace('[INSERTE SU DIRECCIÓN EXACTA AQUÍ]', '{{ \App\Models\Setting::getVal(\'address\', \'Av. de la Innovación, 45, Sevilla\') }}', $locConfig);
$locConfig = str_replace('[INSERTE_URL_GOOGLE_MAPS_AQUI]', '{{ \App\Models\Setting::getVal(\'google_maps_link\', \'https://maps.google.com/?q=Sevilla\') }}', $locConfig);
file_put_contents($locPath, $locConfig);

$finPath = __DIR__ . '/resources/views/pages/financing.blade.php';
$finConfig = file_get_contents($finPath);
$finConfig = str_replace('([INDIQUE SUS BANCOS ASOCIADOS AQUÍ])', '({{ \App\Models\Setting::getVal(\'financing_page_text\', \'Santander, BBVA, Cetelem\') }})', $finConfig);
file_put_contents($finPath, $finConfig);

$showPath = __DIR__ . '/resources/views/vehicles/show.blade.php';
$showConfig = file_get_contents($showPath);
$showConfig = preg_replace('/<a href="https:\/\/wa\.me\/\[INSERTE_SU_NUMERO_WHATSAPP\]\?text=.*?WhatsApp Directo<\/a>/', '<a href="https://wa.me/{{ \App\Models\Setting::getVal(\'whatsapp\', \'34954000000\') }}?text=Hola,%20me%20interesa%20el%20vehículo%20{{ urlencode($vehicle->title) }}" target="_blank" class="btn" style="width:100%; margin-top:12px; background:#25D366; color:white;">WhatsApp Directo</a>', $showConfig);
file_put_contents($showPath, $showConfig);

$homePath = __DIR__ . '/resources/views/home.blade.php';
$homeConfig = file_get_contents($homePath);
// Don't enforce dynamic un-unsplash images unless they add it to Settings as a hero_image upload, but we'll leave it as is for now since user just meant text and basic settings, and the images are decorative.

$aboutPath = __DIR__ . '/resources/views/pages/about.blade.php';
$aboutConfig = file_get_contents($aboutPath);
// Replace about page text
$aboutPattern = '/<p style="color:var\(--text-muted\); font-size:16px; line-height:1\.8; margin-bottom:20px;">\s*En Autos Mrini sabemos.*?<\/p>.*?<p style="color:var\(--text-muted\); font-size:16px; line-height:1\.8; margin-bottom:20px;">\s*Nuestro equipo.*?<\/p>/s';
$aboutReplacement = '<div style="color:var(--text-muted); font-size:16px; line-height:1.8; margin-bottom:20px;">{!! nl2br(e(\App\Models\Setting::getVal(\'about_page_text\', "En Autos Mrini sabemos que la compra de un coche es una decisión importante. Por eso, hemos revolucionado la forma de entender el vehículo de ocasión, ofreciendo unidades exclusivas que superan estrictos controles de calidad.\n\nNuestro equipo cuenta con más de dos décadas de experiencia en el sector automotriz. Seleccionamos únicamente vehículos con mantenimientos certificados y un historial transparente para ofrecerte coches que se sienten y conducen como el primer día."))) !!}</div>';
$aboutConfig = preg_replace($aboutPattern, $aboutReplacement, $aboutConfig);
$aboutConfig = str_replace('Sobre Autos Mrini', 'Sobre {{ \App\Models\Setting::getVal(\'site_name\', \'Autos Mrini\') }}', $aboutConfig);
file_put_contents($aboutPath, $aboutConfig);

// Sidebar fix on Admin Layout
$adminLayoutPath = __DIR__ . '/resources/views/admin/layouts/app.blade.php';
$adminLayoutConfig = file_get_contents($adminLayoutPath);
$adminLayoutConfig = preg_replace('/<a href="\{\{ route\(\'settings\.index\'\) \}\}" class="nav-item \{\{ request\(\)->routeIs\(\'settings\.\*\'\) \? \'active\' : \'\' \}\}">\s*<span class="icon">.*?<\/span>\s*Paramètres\s*<\/a>/s', '<a href="{{ route(\'admin.settings.index\') }}" class="nav-item {{ request()->routeIs(\'admin.settings.*\') ? \'active\' : \'\' }}">
            <span class="icon"><svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></span>
            Configuración
        </a>', $adminLayoutConfig);

// Fix route in web.php if any other changes were missed
$routes = file_get_contents(__DIR__ . '/routes/web.php');
$routes = str_replace("Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');\n    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');", "Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');\n    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');", $routes);
file_put_contents(__DIR__ . '/routes/web.php', $routes);

echo "Frontend dynamic integration completed.\n";
