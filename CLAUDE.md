# CLAUDE.md — Wiñaypacha Putre

Sitio informativo con CMS para "Wiñaypacha Putre", cabañas en Putre (Región de Arica y Parinacota) enfocadas en difundir la cultura, cosmovisión y territorio del pueblo aymara. **No es una plataforma de reservas en tiempo real**: la sección "Reservas" es un formulario de solicitud (lead) que el anfitrión confirma manualmente.

Mapa de rutas, modelos, Livewire y vistas ya construidos: ver `ARQUITECTURA.md` (leerlo antes de explorar el código a mano). Plan de implementación completo en `/home/cesar/.claude/plans/sigye-greedy-sunrise.md`.

## Stack

- **Laravel 13** / **PHP 8.3** / MySQL (`winay_pacha_putre`)
- **Blade** + **Alpine.js** + **Livewire 4** + **Tailwind CSS v4** (sin `tailwind.config.js`, plugin `@tailwindcss/vite`)
- **Vite** para assets (`npm run dev` / `npm run build`)
- Auth: Laravel Breeze (blade stack) — un solo rol `admin`, sin registro público (pendiente deshabilitar rutas de registro de Breeze)
- `spatie/laravel-translatable` para contenido multi-idioma (ES/EN/FR) editado a mano en el admin
- DeepL API (`DEEPL_API_KEY` en `.env`) como asistente de traducción en el admin — nunca traducción automática en el sitio público sin revisión humana

## Proyecto hermano de referencia

`/var/www/html/pindoor` (mismo autor) — guía turística de Valparaíso con patrones reutilizados aquí: `Configuracion` (key/value global), galería de imágenes, formularios de leads, layout con Alpine para lightbox/toggles. Es multi-tenant (varios negocios/roles); este proyecto es de un solo negocio, así que se simplifica a un solo rol admin.

## Convenciones de estilo

- Tailwind v4 — sin archivo de config, clases canónicas.
- Sobriedad visual: acentos de identidad aymara (wiphala, chakana, textiles) con moderación, no como fondo saturado. Paleta base en `resources/css/app.css` (`--color-winay-terracota`, `--color-winay-tierra`, `--color-winay-andino`, `--color-winay-arena`).
- Alpine.js para interactividad ligera (lightbox, toggles). Livewire para todo lo que requiere estado de servidor (formularios, calendario de fiestas, admin CRUD).
- No usar comentarios en código salvo que el WHY sea no obvio.

## Producción / Deploy

- Hosting compartido cPanel (usuario `cwi118900`, servidor `srv15`), acceso por terminal cPanel (sin SSH externo configurado).
- La app Laravel completa vive en `~/winay-pacha-putre` (con `.git`), **separada** del docroot.
- El docroot del dominio es `~/public_html`: copia de `public/` (`index.php`, `build/`, `images/`, `.htaccess`, etc.) + symlink `storage` → `~/winay-pacha-putre/public/storage`. No es un symlink completo a `public/`, así que assets compilados hay que sincronizarlos ahí aparte de git.
- Git ya vinculado a GitHub: `origin` → `https://github.com/Cesar-eav/winay-pacha-putre.git`, rama `main` trackeando `origin/main`. Repo público → `git pull` no requiere credenciales.
- Composer no está en el `PATH` del servidor: usar `php composer.phar install` (el `.phar` ya está en `~/`).
- No hay Node/npm en el servidor: `public/build` (Tailwind/Vite, gitignorado) se compila en local con `npm run build` y se sube aparte (FTP/scp) — git no lo resuelve.
- Flujo para actualizar producción tras un push a GitHub:
  1. `cd ~/winay-pacha-putre && git pull`
  2. Si cambió `composer.lock`: `php composer.phar install --no-dev --optimize-autoloader`
  3. Si cambiaron assets: `npm run build` en local y subir `public/build` a `~/public_html/build`
  4. `php artisan config:cache route:cache view:cache` si aplica

## Alcance explícitamente fuera de este proyecto

- Sistema de reservas en tiempo real, disponibilidad automática o cobro online.
- Traducción automática pública sin revisión (Google Translate widget, etc.) — la traducción vía DeepL es solo una ayuda en el admin.
- Dominio y hosting de producción: gestión administrativa del cliente, no tarea de desarrollo.
