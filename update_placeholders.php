<?php

// Update layouts.public
$layoutPath = __DIR__ . '/resources/views/layouts/public.blade.php';
$layoutConfig = file_get_contents($layoutPath);
// Footer Info replacements
$layoutConfig = str_replace('📍 Av. de la Innovación, 45, Sevilla, España', '📍 [INSERTE SU DIRECCIÓN AQUÍ]', $layoutConfig);
$layoutConfig = str_replace('📞 +34 954 00 00 00', '📞 [INSERTE SU TELÉFONO AQUÍ]', $layoutConfig);
$layoutConfig = str_replace('✉️ contacto@autosmrini.es', '✉️ [INSERTE SU EMAIL AQUÍ]', $layoutConfig);
$layoutConfig = preg_replace('/<svg (.*?)<\/svg>/s', '<!-- [INSERT_LOGO_SVG_OR_IMG_HERE] -->$0', $layoutConfig);
file_put_contents($layoutPath, $layoutConfig);

// Update pages.contact
$contactPath = __DIR__ . '/resources/views/pages/contact.blade.php';
$contactConfig = file_get_contents($contactPath);
$contactConfig = str_replace('Av. de la Innovación, 45<br>41020 Sevilla, España', '[INSERTE DIRECCIÓN LÍNEA 1]<br>[INSERTE CÓDIGO POSTAL Y CIUDAD]', $contactConfig);
$contactConfig = str_replace('+34 954 00 00 00<br>+34 600 00 00 00 (WhatsApp)', '[TELÉFONO FIJO]<br>[TELÉFONO MÓVIL/WHATSAPP]', $contactConfig);
$contactConfig = str_replace('info@autosmrini.es<br>ventas@autosmrini.es', '[EMAIL PRINCIPAL]<br>[EMAIL SECUNDARIO]', $contactConfig);
$contactConfig = str_replace('Lunes - Viernes: 10:00h - 14:00h y 16:30h - 20:30h<br>Sábados: 10:00h - 14:00h', '[HORARIO LUNES-VIERNES]<br>[HORARIO FIN DE SEMANA]', $contactConfig);
file_put_contents($contactPath, $contactConfig);

// Update pages.location
$locPath = __DIR__ . '/resources/views/pages/location.blade.php';
$locConfig = file_get_contents($locPath);
$locConfig = str_replace('<iframe', '<!-- [INSERTE_LINK_EMBED_GOOGLE_MAPS_AQUI] -->' . "\n" . '            <iframe', $locConfig);
$locConfig = str_replace('Av. de la Innovación, 45, Sevilla', '[INSERTE SU DIRECCIÓN EXACTA AQUÍ]', $locConfig);
$locConfig = str_replace('https://maps.google.com/?q=Sevilla', '[INSERTE_URL_GOOGLE_MAPS_AQUI]', $locConfig);
file_put_contents($locPath, $locConfig);

// Update pages.financing
$finPath = __DIR__ . '/resources/views/pages/financing.blade.php';
$finConfig = file_get_contents($finPath);
$finConfig = str_replace('(Santander, BBVA, Cetelem)', '([INDIQUE SUS BANCOS ASOCIADOS AQUÍ])', $finConfig);
file_put_contents($finPath, $finConfig);

// Update vehicles.show
$showPath = __DIR__ . '/resources/views/vehicles/show.blade.php';
$showConfig = file_get_contents($showPath);
$showConfig = str_replace('34954000000', '[INSERTE_SU_NUMERO_WHATSAPP]', $showConfig);
file_put_contents($showPath, $showConfig);

// Update home.blade.php
$homePath = __DIR__ . '/resources/views/home.blade.php';
$homeConfig = file_get_contents($homePath);
$homeConfig = str_replace('<img src="https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6', '<!-- [INSERTE_IMAGEN_HERO_AQUI] -->' . "\n" . '            <img src="https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6', $homeConfig);
file_put_contents($homePath, $homeConfig);

// Update about.blade.php
$aboutPath = __DIR__ . '/resources/views/pages/about.blade.php';
$aboutConfig = file_get_contents($aboutPath);
$aboutConfig = str_replace('<img src="https://images.unsplash.com/photo-1542282088-fe8426682b8f', '<!-- [INSERTE_IMAGEN_CONCESIONARIO_AQUI] -->' . "\n" . '            <img src="https://images.unsplash.com/photo-1542282088-fe8426682b8f', $aboutConfig);
file_put_contents($aboutPath, $aboutConfig);

echo "Template placeholders updated successfully.\n";
