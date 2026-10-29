# CLAUDE.md — Wiñaypacha Putre

Sitio informativo con CMS para "Wiñaypacha Putre", cabañas en Putre (Región de Arica y Parinacota) enfocadas en difundir la cultura, cosmovisión y territorio del pueblo aymara. **No es una plataforma de reservas en tiempo real**: la sección "Reservas" es un formulario de solicitud (lead) que el anfitrión confirma manualmente.

Mapa de rutas, modelos, Livewire y vistas ya construidos: ver `ARQUITECTURA.md` (leerlo antes de explorar el código a mano). Plan de implementación completo en `/home/cesar/.claude/plans/sigye-greedy-sunrise.md`.

## Stack

- **Laravel 13** / **PHP 8.3** / MySQL (local: `cwi118900_winay_pacha_putre`, mismo nombre que en producción; `winay_pacha_putre` es una base local antigua que la app no usa)
- **Blade** + **Alpine.js** + **Livewire 4** + **Tailwind CSS v4** (sin `tailwind.config.js`, plugin `@tailwindcss/vite`)
- Tests (`php artisan test`): SQLite en memoria (`phpunit.xml`), requiere `php8.3-sqlite3`. Las migraciones deben ser portables MySQL/SQLite: usar Schema (`->change()`, etc.), nunca SQL crudo de MySQL (`ALTER TABLE ... MODIFY`)
- **Vite** para assets (`npm run dev` / `npm run build`)
- Auth: Laravel Breeze (blade stack) — un solo rol `admin`, sin registro público (rutas de registro de Breeze eliminadas)
- `spatie/laravel-translatable` para contenido multi-idioma (ES/EN/FR) editado a mano en el admin
- DeepL API (`DEEPL_API_KEY` en `.env`): al guardar en el admin, traduce automáticamente a EN/FR los campos vacíos o desactualizados (ES cambió y ese idioma no se editó a mano). Las pestañas EN/FR del admin quedan para revisar/corregir. Textos fijos de las vistas van en `lang/{en,fr}.json` vía `__()`, traducidos a mano

## Proyecto hermano de referencia

`/var/www/html/pindoor` (mismo autor) — guía turística de Valparaíso con patrones reutilizados aquí: `Configuracion` (key/value global), galería de imágenes, formularios de leads, layout con Alpine para lightbox/toggles. Es multi-tenant (varios negocios/roles); este proyecto es de un solo negocio, así que se simplifica a un solo rol admin.

## Convenciones de estilo

- Tailwind v4 — sin archivo de config, clases canónicas.
- Sobriedad visual: acentos de identidad aymara (wiphala, chakana, textiles) con moderación, no como fondo saturado. Paleta base en `resources/css/app.css` (`--color-winay-terracota`, `--color-winay-tierra`, `--color-winay-andino`, `--color-winay-arena`).
- Alpine.js para interactividad ligera (lightbox, toggles). Livewire para todo lo que requiere estado de servidor (formularios, calendario de fiestas, admin CRUD).
- No usar comentarios en código salvo que el WHY sea no obvio.
- **Todo cambio debe reflejarse en `resources/views/como-funciona.blade.php`** (`/como-funciona`), la guía para el cliente de qué se edita desde el admin y qué requiere desarrollador.

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
  2b. Si hay migraciones nuevas: `php artisan migrate --force` (las del carrusel del inicio y de fotos de huéspedes además importan sus fotos, requieren GD con WebP; la del catálogo de equipamiento carga sus ~107 ítems y no requiere nada extra)
  3. Si cambiaron assets: `npm run build` en local (Vite exige Node 20+: el Node del sistema es 18, usar el de nvm, `~/.nvm/versions/node/v22.*`) y subir `public/build` a `~/public_html/build`
  4. Si hay imágenes subidas antes del procesador: `php artisan imagenes:optimizar` (requiere GD con WebP en el servidor)
  4b. Si hay contenido con EN/FR vacíos (p. ej. cargado antes de la auto-traducción): `php artisan traducciones:completar [--dry-run]` (solo rellena vacíos, no pisa nada)
  4c. Para cargar o actualizar las fichas reales de cabañas y habitaciones: `php artisan alojamientos:actualizar --dry-run` y luego sin `--dry-run` (idempotente; retraduce EN/FR de las descripciones que cambien)
  5. `php artisan config:cache route:cache view:cache` si aplica

## Alcance explícitamente fuera de este proyecto

- Sistema de reservas en tiempo real, disponibilidad automática o cobro online.
- Traducción en vivo del lado del cliente (Google Translate widget, etc.) — la traducción se hace con DeepL al guardar en el admin y queda persistida/editable.
- Dominio y hosting de producción: gestión administrativa del cliente, no tarea de desarrollo.
