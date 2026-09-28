# Diseño de la página "Cultura Aymara"

Documenta el rediseño editorial de `resources/views/cultura.blade.php` (28-09-2026), hecho tras una primera versión funcional pero visualmente plana. Referencia: `app/Http/Controllers/CulturaController.php` (retorna la vista sin datos del CMS — la página es hardcodeada, no viene de `Tema`).

## Objetivo

Reemplazar el layout genérico "galería + texto" repetido 3 veces por una página editorial de turismo de alta gama (referencias: Explora, National Geographic Expeditions, Visit Faroe Islands/Iceland.is), con foco en escritorio, sin salirse de los 4 colores de marca (`winay-terracota`, `winay-tierra`, `winay-andino`, `winay-arena`) ni de la sobriedad visual que pide `CLAUDE.md`.

## Estructura de la página (en orden)

1. **Hero full-bleed** (`h-[70vh] min-h-96 max-h-160`) — imagen `cosmovision2.jpg` con overlay `bg-linear-to-t from-black/80 via-black/20 to-transparent`, kicker "Putre, territorio aymara" + H1 "Cultura Aymara" + subtítulo, texto blanco alineado abajo-izquierda.
2. **Nav interno sticky** (`hidden lg:block`, solo desktop) — 3 anclas (Cosmovisión / Textiles / Calendario) bajo el header (`top-16`). Estado activo con Alpine + `IntersectionObserver` vanilla (no hay plugin `@alpinejs/intersect` instalado; se implementó a mano en el `x-init` del `<nav>`).
3. **Cosmovisión** (`#cosmovision`) — intro + imagen `cosmovision1.jpg` a dos columnas, luego 3 tarjetas (Suma Qamaña / Ayni / Chachawarmi) en `sm:grid-cols-3`.
4. **Chakana + Wiphala** (banda `bg-winay-arena/40`) — diagrama de 3 niveles escalonados (Alaxpacha/Akapacha/Manghapacha, indentación vía `margin-left: {{ $i * 1.5 }}rem`) y legend de los 7 colores de la Wiphala como `<dl>`, con crédito discreto al libro fuente al final de la banda.
5. **Textiles y artesanía** (`#textiles`) — `<x-galeria-lightbox>` con las 3 fotos de artesanía en `lg:col-span-3` de una grid de 5, texto en `lg:col-span-2` con un `<blockquote>` destacado sobre "sawuña".
6. **Calendario de fiestas** (`#calendario`, banda `bg-winay-arena/40`) — 4 tarjetas con las fotos de fiestas + acordeón "Resto del año" (Alpine `x-data="{ open: false }"` por mes, sin cambios respecto a la versión anterior).
7. **CTA de cierre** — banda blanca con 2 botones (`cabanas.index`, `reserva`).

## Decisiones de contenido

- **Colores de la Wiphala**: los 7 significados (Blanco/Amarillo/Naranjo/Rojo/Morado/Azul/Verde) están tomados del libro "Cosmovisión Aymara: un acercamiento para profesionales de salud" (Javiera Quispe Villalobos, U. de Antofagasta, 2021), ya citado en la página. **Los valores hex son aproximaciones visuales estándar de la Wiphala, no verificados contra una fuente oficial** — revisar si el cliente tiene una referencia exacta.
- **Fotos del calendario**: `fiestas1.jpg` (Anata de los pueblos, 30-31 enero) y `fiestas3.jpg` (Ascenso a los cerros sagrados, 9 mayo) tienen fecha confirmada con alta confianza (banner visible en la foto / contexto visual). `fiestas2.jpg` (autoridades y reina de fiesta) y `fiestas4.jpg` (mesa ritual) **no se pudieron identificar con certeza** — se decidió con el cliente mostrarlas con etiqueta genérica ("Fiesta patronal" / "Ceremonia ritual" en `text-stone-400`) en vez de inventar una fecha. Si en el futuro se confirma cuál fiesta es cada una, mover esa tarjeta junto a su fecha real en el `$meses` array y usar `text-winay-terracota` como las otras dos.
- **Hero con imagen generada por IA**: `cosmovision2.jpg` es un gráfico ilustrativo (no una foto documental) — decisión consciente del cliente por su impacto visual, no un error de curatoría.

## Restricciones técnicas encontradas

- **`cosmovision1.jpg` es de baja resolución (733×418px, sin original de mayor tamaño)**. No se puede usar full-bleed sin verse borrosa — se dejó como imagen contenida a media columna (`aspect-video`, ~500-600px renderizados). Cualquier otro uso a pantalla completa de esta imagen debería reemplazarse por una foto de mayor resolución primero.
- **No hay plugin `@alpinejs/intersect`** en el proyecto — el scrollspy del nav interno usa `IntersectionObserver` nativo dentro de `x-init`, sin dependencias nuevas.
- **`<x-galeria-lightbox>` no se modificó** — no soporta layouts tipo bento nativamente (fuerza `aspect-video` + tira de miniaturas), así que en Textiles se le dio más ancho de columna en vez de duplicar su lógica Alpine para forzar un grid asimétrico.
- **`public/build` puede quedar desactualizado en desarrollo local** si hay un proceso `npm run dev` corriendo sin haber generado el archivo `public/build/hot` (Laravel entonces sirve el build viejo de `public/build/assets/`, no el dev server). Si un cambio de Blade/Tailwind "no se ve" en local aunque el HTML sí lo tenga, correr `npm run build` para descartar esto antes de asumir otro bug. Mismo síntoma raíz que `incidente-build-assets-desactualizados-2026-09-15.md`, pero en desarrollo local en vez de producción.

## Archivos tocados

- `resources/views/cultura.blade.php` — reescritura completa.
- `resources/views/layouts/winay.blade.php` — se agregó `class="scroll-smooth"` al `<html>` (cambio sitewide de una clase, para que los anchors del nav interno hagan scroll suave).
- `public/images/culturaaymara/web/*.jpg` — set de imágenes optimizadas (máx. ~1600px, calidad ~82%) usado por esta página, generado a partir de los originales en `public/images/culturaaymara/` (HEIC convertidos a JPG, resto redimensionado). Los originales no se tocaron.

## Patrones reutilizados de otras vistas

- Hero/overlay y bandas full-width alternadas: `resources/views/inicio.blade.php`.
- Tarjetas CTA con botones: mismo patrón de botones (`bg-winay-terracota` / outline `border-winay-tierra`) usado en todo el sitio.
- Acordeón por mes: implementación propia ya existente en esta página desde la primera versión, sin cambios.
