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
| `/entorno` | `EntornoController` (invokable) | `entorno` |
| `/nosotros` | `NosotrosController` (invokable) | `nosotros` |
| `/contacto` | `ContactoController` (invokable) | `contacto` |
| `/reserva` | `ReservaController` (invokable) | `reserva` |

`/dashboard` redirige a `/admin`. Bloque `auth`: `/profile` (Breeze `ProfileController`). `auth.php` = rutas Breeze estándar (login, registro *pendiente deshabilitar*, password reset, verify email).

## Panel admin (`routes/admin.php`)

Prefijo `/admin`, name `admin.*`, middleware `['auth', 'admin']` (alias `admin` → `App\Http\Middleware\EnsureUserIsAdmin`, registrado en `bootstrap/app.php`). Todas las páginas son componentes Livewire full-page (no hay `AdminController`):

| Ruta | Componente |
|---|---|
| `/admin` | `App\Livewire\Admin\Dashboard` |
| `/admin/temas` | `App\Livewire\Admin\Temas` |
| `/admin/cabanas` | `App\Livewire\Admin\Cabanas` |
| `/admin/lugares` | `App\Livewire\Admin\Lugares` |
| `/admin/especies` | `App\Livewire\Admin\Especies` |

Vistas en `resources/views/livewire/admin/*.blade.php`, layout `layouts/admin.blade.php`.

Nota: no hay CRUD admin para `Fiesta`, `ServicioLocal`, `PaginaNosotros` todavía (existen modelo + migración pero no componente Livewire admin) — verificar antes de asumir que están editables desde el panel.

## Modelos (`app/Models`)

| Modelo | Tabla | Traducible (`spatie/translatable`) | Relaciones |
|---|---|---|---|
| `Cabana` | `cabanas` | `descripcion` | `imagenes` (morphMany), `equipamientos` (belongsToMany vía `cabana_equipamiento`), `solicitudesReserva` (hasMany) |
| `Tema` | `temas` | `titulo`, `cuerpo` | `imagenes` (morphMany) |
| `Fiesta` | `fiestas` | `titulo`, `descripcion` | `imagenes` (morphMany) |
| `LugarEntorno` | `lugares_entorno` | `descripcion` | `imagenes` (morphMany) |
| `Especie` | `especies` | `descripcion`, `donde_observar` | — (imagen simple vía columna `imagen`, no morphMany) |
| `Equipamiento` | `equipamientos` | — | `cabanas` (belongsToMany) |
| `ServicioLocal` | `servicios_locales` | — | — |
| `PaginaNosotros` | `pagina_nosotros` | `historia`, `mensaje` | `imagenes` (morphMany), patrón singleton (`::singleton()`) |
| `Configuracion` | `configuraciones` | — | key/value global (`clave` PK string), `Configuracion::get()/set()` — mismo patrón que en el proyecto hermano `pindoor` |
| `Imagen` | `imagenes` | — | `imageable` (morphTo) — polimórfica, usada por Cabana/Tema/Fiesta/LugarEntorno/PaginaNosotros |
| `LeadContacto` | `leads_contacto` | — | formulario de contacto (`atendido` boolean) |
| `SolicitudReserva` | `solicitudes_reserva` | — | `cabana` (belongsTo) — el lead de reserva, NO reserva real (ver alcance en CLAUDE.md) |
| `User` | `users` | — | columna `is_admin` (único rol admin, sin roles múltiples) |

Modelos publicables usan scopes `publicado()` / `ordenado()` consistentemente (campo `publicado` boolean + `orden` integer).

## Livewire (`app/Livewire`)

**Público:**
- `FormularioContacto` — crea `LeadContacto`
- `FormularioReserva` — crea `SolicitudReserva`

**Admin CRUD:** `Admin\Dashboard`, `Admin\Temas`, `Admin\Cabanas`, `Admin\Lugares`, `Admin\Especies` — todos componentes full-page, usan los traits de `Concerns/`.

**Traits reutilizables (`app/Livewire/Concerns`):**
- `TranslatesWithDeepl` — botón "traducir" en el admin: toma el campo `es` de una propiedad traducible y llama a `DeepLTranslator` (servicio en `app/Services`) para rellenar `en`/`fr`. Si falla, deja mensaje de error y permite completar a mano. Nunca se usa en el sitio público.
- `ManagesGaleria` — CRUD de galería de imágenes (`WithFileUploads`) para modelos con `imagenes()` morphMany: subir, reordenar, eliminar. Incluye conversión HEIC→WebP server-side (vía `maestroerror/php-heic-to-jpg` + GD) con corrección de orientación EXIF, porque los navegadores (salvo Safari) no renderizan HEIC — las fotos de iPhone se convierten al vuelo. Placeholders (`path` con prefijo `placeholder/`) viven en `public/images/` y no se tocan con `Storage`.

## Vistas (`resources/views`)

- `layouts/winay.blade.php` — layout del sitio público (el que tenías abierto).
- `layouts/app.blade.php`, `layouts/guest.blade.php`, `layouts/navigation.blade.php` — stack Breeze (auth/profile).
- `layouts/admin.blade.php` — layout del panel admin.
- Páginas públicas en la raíz de `views/`: `inicio`, `cultura`, `putre`, `entorno`, `nosotros`, `contacto`, `reserva`, `cabanas/index`, `cabanas/show`.
- `components/galeria-lightbox.blade.php` — lightbox Alpine para galerías.
- `livewire/admin/partials/` — `campo-traducible` (input ES/EN/FR + botón DeepL), `galeria-editor` (UI de `ManagesGaleria`), `banner-exito`.

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
