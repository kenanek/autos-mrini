# Autos Mrini - Launch Checklist & Production Deployment Guide

This document provides a single clear configuration checklist for deploying the Autos Mrini Laravel project to production.

## 1. Required Business Info to Insert
Before deploying to production, you must replace the [PLACEHOLDER] tags in the following files with your actual business information:

- **`resources/views/layouts/public.blade.php`**
  - `<!-- [INSERT_LOGO_SVG_OR_IMG_HERE] -->`
  - `[INSERTE SU DIRECCIÓN AQUÍ]` (Footer)
  - `[INSERTE SU TELÉFONO AQUÍ]` (Footer)
  - `[INSERTE SU EMAIL AQUÍ]` (Footer)

- **`resources/views/pages/contact.blade.php`**
  - `[INSERTE DIRECCIÓN LÍNEA 1]` & `[INSERTE CÓDIGO POSTAL Y CIUDAD]`
  - `[TELÉFONO FIJO]` & `[TELÉFONO MÓVIL/WHATSAPP]`
  - `[EMAIL PRINCIPAL]` & `[EMAIL SECUNDARIO]`
  - `[HORARIO LUNES-VIERNES]` & `[HORARIO FIN DE SEMANA]`

- **`resources/views/pages/location.blade.php`**
  - `<!-- [INSERTE_LINK_EMBED_GOOGLE_MAPS_AQUI] -->` (The src URL inside the iframe)
  - `[INSERTE SU DIRECCIÓN EXACTA AQUÍ]`
  - `[INSERTE_URL_GOOGLE_MAPS_AQUI]`

- **`resources/views/pages/financing.blade.php`**
  - `([INDIQUE SUS BANCOS ASOCIADOS AQUÍ])` (e.g. Santander, BBVA)

- **`resources/views/vehicles/show.blade.php`**
  - `[INSERTE_SU_NUMERO_WHATSAPP]` (In the wa.me/ URL)

- **`resources/views/home.blade.php`**
  - `<!-- [INSERTE_IMAGEN_HERO_AQUI] -->` (Replace the Unsplash src)

- **`resources/views/pages/about.blade.php`**
  - `<!-- [INSERTE_IMAGEN_CONCESIONARIO_AQUI] -->` (Replace the Unsplash src)

---

## 2. Admin Login Info
Once the project is deployed, access the admin panel via:
- **URL:** `you_domain.com/admin/login`
- **Default Email:** `admin@autosmrini.com`
- **Default Password:** `password`
*(Remember to change the admin password immediately upon first login in production!)*

---

## 3. Production Deployment Steps
Execute these steps on your shared hosting or VPS environment:

1. **Upload Files:** Upload the entire Laravel project to your server (excluding `/vendor` and `/node_modules` if utilizing CI/CD, or upload them if FTP is the only option).
2. **Setup .env:** Copy `.env.example` to `.env` and update the following variables:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://your_domain.com` (Must include https://)
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
3. **Install Dependencies:** (If not uploaded)
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
4. **Generate App Key:** (If not already generated)
   ```bash
   php artisan key:generate
   ```
5. **Run Migrations & Seeders:** 
   ```bash
   php artisan migrate --seed --force
   ```

---

## 4. Storage Link Reminder
Images uploaded via the Admin panel are stored in `storage/app/public`. You MUST create a symbolic link so they are accessible from the web:
```bash
php artisan storage:link
```
*Note on Shared Hosting:* If `php artisan storage:link` fails due to lack of symlink permissions on your host, you must run it locally and upload the generated `public/storage` folder, or use a custom PHP script to generate it on the server.

---

## 5. Cache Optimization Commands
Once everything is configured and working in production, run these commands to optimize loading speeds:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```
*Whenever you make changes to `.env` or configurations, remember to run:* `php artisan optimize:clear`

---

## 6. Final QA Checklist
- [ ] Are all placeholder texts and links updated?
- [ ] Is the Google Maps iframe pointing to the correct dealership?
- [ ] Does the WhatsApp button on vehicle detail pages open the correct number?
- [ ] Can you log into the Admin panel (`/admin`)?
- [ ] Try creating a test vehicle and uploading an image. Does it display correctly on the public site?
- [ ] Has the `APP_DEBUG` been set to `false` in the `.env` file?
- [ ] Test the public contact forms. Do they successfully save to the database?
