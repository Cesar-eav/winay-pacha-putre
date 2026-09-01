# Progreso: Panel Admin — Infraestructura + CRUD Temas/Cabañas

Estado a: 2026-09-01 — **verificado en navegador (Puppeteer), patrón validado end-to-end.** Falta solo comitear y replicar a los 8 recursos restantes.

Plan completo en: `/home/cesar/.claude/plans/estabamos-trabajando-en-crear-staged-steele.md`

## Verificación en navegador (2026-09-01)

Se corrió `migrate:fresh --seed`, se levantó `php artisan serve` y se automatizó todo el flujo con Puppeteer (login → crear/editar/eliminar Tema → crear/editar/eliminar Cabaña, con DeepL, galería y equipamiento). Sin errores de consola. Se encontraron y corrigieron dos bugs reales del código sin probar:

1. **Slug invisible al escribir**: ningún `wire:model` tenía `.live`, así que `titulo.es` (Temas) y `nombre` (Cabañas) solo sincronizaban con el servidor en el próximo round-trip (ej. al hacer clic en "Traducir con DeepL" o "Guardar"), por lo que el slug autogenerado no se veía mientras el usuario escribía. Fix: `wire:model.live.debounce.500ms` solo en esos dos campos (`campo-traducible.blade.php` ahora acepta `generaSlug => true` para el tab ES cuando aplica; `cabanas.blade.php` lo aplica directo en `nombre`). El resto de campos sigue con `wire:model` deferred normal (sin cambios de comportamiento).
2. **Banner de éxito nunca aparecía**: estaba en `layouts/admin.blade.php`, pero el atributo `#[Layout(...)]` de Livewire solo envuelve el layout en la carga inicial (GET) — las respuestas AJAX de `wire:submit`/`wire:click` (guardar, eliminar) solo re-renderizan el `<div>` raíz del componente, nunca el layout. Fix: se creó `resources/views/livewire/admin/partials/banner-exito.blade.php` y se incluye al principio de `temas.blade.php` y `cabanas.blade.php`; se quitó el banner duplicado del layout.

Confirmado también: `equipamientos()->sync()` en Cabañas persiste correctamente, y los cambios de admin (crear Tema/Cabaña con foto) se reflejan en `/cultura` y `/cabanas/{slug}` del sitio público.

## Ya hecho (código escrito y VERIFICADO en navegador)

**Infraestructura compartida:**
- `resources/views/layouts/admin.blade.php` — layout admin (sidebar Temas/Cabañas, paleta winay, `@livewireScriptConfig`, `{{ $slot }}`, banner `session('success')`).
- `routes/admin.php` — grupo `auth`+`admin`, prefix `/admin`, name `admin.` → `admin.temas`, `admin.cabanas` (Livewire full-page components directos, sin controlador).
- `routes/web.php` — agregado `require __DIR__.'/admin.php';` y `/dashboard` ahora redirige a `/admin/temas`.
- `dashboard.blade.php` eliminado (ya no se usa).
- `app/Services/DeepLTranslator.php` — `translate($texto, $destino)`, usa `config('winay.deepl_api_key')`, devuelve `null` si falla/no hay key (nunca rompe el guardado).
- `app/Livewire/Concerns/TranslatesWithDeepl.php` — trait con `traducir($propiedad)` y `$errorTraduccion`.
- `app/Livewire/Concerns/ManagesGaleria.php` — trait `WithFileUploads`, maneja `imagenesExistentes`/`nuevasFotos`/`imagenesAEliminar`, `cargarGaleria()`, `eliminarFotoExistente()`, `eliminarFotoNueva()`, `moverFotoExistente()` (botones subir/bajar, sin drag-and-drop), `guardarGaleria()`.
- `resources/views/livewire/admin/partials/campo-traducible.blade.php` — tabs ES/EN/FR + botón "Traducir con DeepL", vía `@include`.
- `resources/views/livewire/admin/partials/galeria-editor.blade.php` — grid de fotos + input upload.

**CRUD Temas:** `app/Livewire/Admin/Temas.php` + `resources/views/livewire/admin/temas.blade.php` — listado con tabs por categoría, alta/edición inline, slug auto-generado desde título ES, galería, DeepL.

**CRUD Cabañas:** `app/Livewire/Admin/Cabanas.php` + `resources/views/livewire/admin/cabanas.blade.php` — listado, alta/edición inline, checkboxes de Equipamiento agrupados por ámbito (`sync()`), galería, DeepL.

**Corrección de arquitectura importante:** Al principio ambas vistas envolvían todo en `<x-admin-layout>...<x-slot:titulo>` (el patrón usado en el sitio público con controladores normales). Eso está MAL para un componente Livewire full-page: Livewire necesita que la vista del componente tenga UN SOLO `<div>` raíz, y el layout se aplica aparte vía el atributo `#[Layout('layouts.admin', ['titulo' => '...'])]` en la clase (`Temas.php` y `Cabanas.php`). Ambas vistas empiezan/terminan con `<div>...</div>` en vez de `<x-admin-layout>`. Verificado en navegador — carga sin errores.

## Pendiente / próximos pasos al retomar

1. **Commitear** — todo lo de esta pasada (infraestructura admin + CRUD Temas/Cabañas + los 2 fixes de esta verificación) sigue sin commitear. Revisar `git status`/diff completo antes.
2. **Una vez comiteado**, replicar el patrón (ya validado) a los 8 recursos restantes (tarea aparte): Equipamientos, Lugares del Entorno, Especies (ojo: imagen única, no galería), Servicios Locales, Nosotros (singleton), Solicitudes de Reserva (listado + cambio de estado), Leads de Contacto (listado + toggle atendido), Configuración (formulario de settings).
   - Para cada uno de estos, aplicar también los 2 fixes ya conocidos desde el principio (no como hallazgo tardío): `wire:model.live.debounce.500ms` en el campo que genera el slug/identificador, y el banner de éxito incluido dentro del `<div>` raíz del componente (usar `@include('livewire.admin.partials.banner-exito')`), nunca en el layout.

## Notas de contexto (por si se pierde memoria)

- CLAUDE.md pide admin CRUD en Livewire (no Filament, no controladores tradicionales) — así se construyó, aunque el proyecto hermano `pindoor` usa controladores+Blade tradicionales (no se siguió ese patrón, se siguió la instrucción explícita del proyecto).
- `config/winay.php` ya trae `deepl_api_key` y `locales` (es/en/fr).
- Decisión del usuario: reordenar fotos con botones subir/bajar, NO drag-and-drop.
- Decisión del usuario: empezar con Temas+Cabañas nomás para validar el patrón antes de replicar a los otros 8 recursos.
- El sitio público (8 páginas) y el login/logout con Breeze (sin registro público, gate `is_admin`) ya estaban terminados y verificados en navegador ANTES de esta tarea — no tocar de nuevo salvo que se detecte una regresión.
