# Arquitectura — Wiñaypacha Putre

Mapa del código para no tener que explorarlo desde cero en cada sesión. Convenciones de estilo y alcance del proyecto están en `CLAUDE.md`; este documento es el "dónde está cada cosa".

## Rutas públicas (`routes/web.php`)

| Ruta | Controller | Name |
|---|---|---|
| `/` | `InicioController` (invokable) | `inicio` |
| `/cultura` | `CulturaController` (invokable) | `cultura` |
| `/putre` | `PutreController` (invokable) | `putre` |
| `/cabanas` | `CabanaController@index` | `cabanas.index` |
| `/cabanas/{cabana:slug}` | `CabanaController@show` | `cabanas.show` |
| `/habitaciones` | `CabanaController@habitaciones` | `habitaciones.index` |
| `/habitaciones/{habitacion:slug}` | `CabanaController@habitacion` | `habitaciones.show` (404 si el slug es de una cabaña, y viceversa) |
| `/entorno` | `EntornoController` (invokable) | `entorno` |
| `/nosotros` | `Route::view` (vista estática, sin controller/CMS) | `nosotros` |
| `/contacto` | `ContactoController` (invokable) | `contacto` |
| `/reserva` | `ReservaController` (invokable) | `reserva` |

`/dashboard` redirige a `/admin`. Bloque `auth`: `/profile` (Breeze `ProfileController`). `auth.php` = rutas Breeze estándar (login, registro *pendiente deshabilitar*, password reset, verify email).

## Panel admin (`routes/admin.php`)

Prefijo `/admin`, name `admin.*`, middleware `['auth', 'admin']` (alias `admin` → `App\Http\Middleware\EnsureUserIsAdmin`, registrado en `bootstrap/app.php`). Todas las páginas son componentes Livewire full-page (no hay `AdminController`):

| Ruta | Componente |
|---|---|
| `/admin` | `App\Livewire\Admin\Dashboard` |
| `/admin/contacto` | `App\Livewire\Admin\Contacto` (mensajes de `LeadContacto`: filtro pendientes/atendidos/todos, marcar atendido, eliminar) |
| `/admin/temas` | `App\Livewire\Admin\Temas` |
| `/admin/cabanas` | `App\Livewire\Admin\Cabanas` |
| `/admin/habitaciones` | `App\Livewire\Admin\Habitaciones` (extiende `Cabanas`, solo cambia `tipo()` y `etiquetas()`) |
| `/admin/lugares` | `App\Livewire\Admin\Lugares` |
| `/admin/especies` | `App\Livewire\Admin\Especies` |

Vistas en `resources/views/admin/*.blade.php`, layout `admin/layouts/admin.blade.php`.

Nota: no hay CRUD admin para `Fiesta`, `ServicioLocal` todavía (existen modelo + migración pero no componente Livewire admin) — verificar antes de asumir que están editables desde el panel. `Nosotros` (`/nosotros`) no tiene CMS: es vista estática, sin modelo ni admin.

## Modelos (`app/Models`)

| Modelo | Tabla | Traducible (`spatie/translatable`) | Relaciones |
|---|---|---|---|
| `Cabana` | `cabanas` | `descripcion` | columna `tipo` (`Cabana::TIPO_CABANA` / `TIPO_HABITACION`, scope `tipo()`): cabañas y habitaciones comparten tabla, modelo y vistas; `numero` es único por tipo; accessor `url` elige la ruta pública según el tipo. Portada en columna `imagen_principal` (path de una de las fotos de su galería, elegida con un tap en el admin), `imagenes` (morphMany), `equipamientos` (belongsToMany vía `cabana_equipamiento`), `solicitudesReserva` (hasMany) |
| `Tema` | `temas` | `titulo`, `cuerpo` | `imagenes` (morphMany) |
| `Fiesta` | `fiestas` | `titulo`, `descripcion` | `imagenes` (morphMany) |
| `LugarEntorno` | `lugares_entorno` | `descripcion` | `imagenes` (morphMany) |
| `Especie` | `especies` | `descripcion`, `donde_observar` | — (imagen simple vía columna `imagen`, no morphMany) |
| `Equipamiento` | `equipamientos` | — | `cabanas` (belongsToMany) |
| `ServicioLocal` | `servicios_locales` | — | — |
| `Configuracion` | `configuraciones` | — | key/value global (`clave` PK string), `Configuracion::get()/set()` — mismo patrón que en el proyecto hermano `pindoor` |
| `Imagen` | `imagenes` | — | `imageable` (morphTo) — polimórfica, usada por Cabana/Tema/Fiesta/LugarEntorno |
| `LeadContacto` | `leads_contacto` | — | formulario de contacto (`atendido` boolean) |
| `SolicitudReserva` | `solicitudes_reserva` | — | `cabana` (belongsTo) — el lead de reserva, NO reserva real (ver alcance en CLAUDE.md) |
| `User` | `users` | — | columna `is_admin` (único rol admin, sin roles múltiples) |

Modelos publicables usan scopes `publicado()` / `ordenado()` consistentemente (campo `publicado` boolean + `orden` integer).

## Livewire (`app/Livewire`)

**Público:**
- `FormularioContacto` — crea `LeadContacto`
- `FormularioReserva` — crea `SolicitudReserva`

**Admin CRUD:** `Admin\Dashboard`, `Admin\Contacto` (bandeja de mensajes, sin formulario), `Admin\Temas`, `Admin\Cabanas` (+ `Admin\Habitaciones`), `Admin\Lugares`, `Admin\Especies` — todos componentes full-page, usan los traits de `Concerns/`.

**Traits reutilizables (`app/Livewire/Concerns`):**
- `TranslatesWithDeepl` — `traducir()`: botón "Traducir con DeepL" para previsualizar en el formulario. `completarTraducciones($props, $guardado)`: se llama en cada `guardar()` del admin y traduce EN/FR si están vacíos, o si el ES cambió respecto a lo guardado y ese idioma no se editó a mano (si DeepL falla, guarda igual y deja `session('aviso')`, que muestra `banner-exito`). `DeepLTranslator` (`app/Services`) manda `source_lang=ES` y `tag_handling=html` cuando el texto trae HTML (Quill).
- `ManagesGaleria` — CRUD de galería de imágenes (`WithFileUploads`) para modelos con `imagenes()` morphMany: subir (de a un archivo, validando cada uno para reportar cuál falló), reordenar con arrastre (`wire:sort`), eliminar, máximo de fotos (`maximoFotosGaleria()`, 12 por defecto) e imagen principal opcional (`usaImagenPrincipal()`, activa solo en Cabañas). Incluye conversión HEIC→WebP server-side (vía `maestroerror/php-heic-to-jpg` + GD) con corrección de orientación EXIF, porque los navegadores (salvo Safari) no renderizan HEIC — las fotos de iPhone se convierten al vuelo. Placeholders (`path` con prefijo `placeholder/`) viven en `public/images/` y no se tocan con `Storage`.

## Imágenes

Toda subida del admin pasa por `App\Services\ProcesadorImagen` (intervention/image ^4, driver GD): guarda 3 variantes WebP en disco `public` — `carpeta/x.webp` (full, 2000px), `carpeta/medium/x.webp` (1200px), `carpeta/thumbs/x.webp` (480px). En BD solo se guarda la ruta full; `Imagen::thumb_url` / `medium_url` (y `Especie::imagen_thumb_url`) derivan la variante y caen al original si no existe. `galeria-lightbox` muestra medium/thumbs y carga full solo al abrir el lightbox. Imágenes anteriores a este sistema: `php artisan imagenes:optimizar [--dry-run]`.

## Idiomas

Rutas públicas duplicadas con prefijo `/en` y `/fr` (names `en.*`, `fr.*`), `SetLocale` fija el idioma, `lroute()` (`app/helpers.php`) genera la URL en el idioma actual. Textos fijos: `__()` + `lang/{en,fr}.json` (incluye nombres de `Equipamiento` y `ServicioLocal`, que son catálogos sin CRUD; ojo con claves dinámicas como el calendario de `cultura.blade.php`). Contenido del CMS: columnas JSON de `spatie/translatable`, auto-traducidas con DeepL al guardar (ver `TranslatesWithDeepl`). Rellenar vacíos existentes: `php artisan traducciones:completar [--dry-run]`.

Fichas reales de cabañas y habitaciones (enviadas por el cliente): `php artisan alojamientos:actualizar [--dry-run]` (`app/Console/Commands/ActualizarAlojamientos.php`). Busca por slug, fija número, capacidad, orden, publicado y descripción ES (retraduce EN/FR con DeepL), crea el equipamiento que falte y sincroniza el de cada alojamiento conservando Wifi/Estacionamiento/Agua caliente/Ropa de cama. Es idempotente. En fichas de tipo habitación, todo el equipamiento se muestra bajo un solo bloque.

## Vistas (`resources/views`)

Todo lo del admin vive en `admin/`; el resto de `views/` es sitio público.

**Admin (`admin/`):**
- `admin/{dashboard,contacto,temas,cabanas,lugares,especies}.blade.php` — vistas de los componentes Livewire `App\Livewire\Admin\*`.
- `admin/partials/` — `campo-traducible` (input ES/EN/FR + botón DeepL), `campo-richtext`, `galeria-editor` (UI de `ManagesGaleria`), `banner-exito` (`session('success')` y `session('aviso')`).
- `admin/layouts/admin.blade.php` — layout del panel. `admin/layouts/{app,guest,navigation}` — stack Breeze (`<x-app-layout>` / `<x-guest-layout>` para profile y auth).
- `admin/auth/` — login, password reset, verify email (Breeze).
- `admin/profile/` — perfil del admin (Breeze).
- `admin/components/` — componentes Breeze (botones, inputs, dropdown, modal…), registrados con prefijo en `AppServiceProvider`: se usan como `<x-admin::primary-button>`, `<x-admin::dropdown>`, etc.

**Público:**
- `layouts/winay.blade.php` + `layouts/footer.blade.php` — layout del sitio público.
- Páginas en la raíz de `views/`: `inicio`, `cultura`, `putre`, `entorno/{index,show}`, `nosotros`, `contacto`, `reserva`, `cabanas/{index,show}` (también sirven `/habitaciones`, textos según `$tipo`).
- `livewire/formulario-{contacto,reserva}.blade.php` — formularios de leads.
- `components/` — `galeria-lightbox` (lightbox Alpine), `alojamientos/grilla` y `alojamientos/editorial` (cabañas y habitaciones en el inicio), `imagen-placeholder`, `language-switcher`, `form-field`.

## Paleta / CSS (`resources/css/app.css`)

Tokens Tailwind v4 (sin config file):
```
--color-winay-terracota: #b5502f;
--color-winay-tierra:    #6b4423;
--color-winay-andino:    #2f4a5c;
--color-winay-arena:     #f4ede2;
```

## Stack de paquetes (versiones reales instaladas)

- `laravel/framework` ^13.17, PHP ^8.3
- `livewire/livewire` ^4.4
- `spatie/laravel-translatable` ^6.14
- `laravel/breeze` ^2.4 (dev, blade stack)
- `maestroerror/php-heic-to-jpg` ^1.0
- npm: `tailwindcss` ^4, `@tailwindcss/vite` ^4, `alpinejs` ^3.4, `vite` ^8

## Git

Remote `origin` → `https://github.com/Cesar-eav/winay-pacha-putre.git`, rama `main` trackea `origin/main`. `git pull` / `git push` ya operativos.

## Plan de implementación

Plan completo (histórico, puede estar desactualizado respecto al código ya construido) en `/home/cesar/.claude/plans/sigye-greedy-sunrise.md`.
