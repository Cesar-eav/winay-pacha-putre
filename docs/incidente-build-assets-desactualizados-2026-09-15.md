# Incidente: cambios de estilo no se ven en producción (15-09-2026)

## Síntoma

Tras vincular `~/winay-pacha-putre` al repo y hacer `git pull` con los últimos commits
(rediseño del formulario de reserva, ajustes de copy, etc.), el código en el servidor
quedó actualizado pero el sitio en vivo seguía mostrando los estilos/markup viejos.

## Causa raíz

`/public/build` está en `.gitignore` (es el output compilado de Vite, no se versiona). Un
`git pull` nunca toca esa carpeta, así que producción se quedó con el build generado el
2-09-2026, de antes de los commits recientes.

A esto se sumó el mismo problema ya visto con `storage` en
[`incidente-deploy-imagenes-2026-09-10.md`](incidente-deploy-imagenes-2026-09-10.md): el
document root real del dominio es `~/public_html`, carpeta física separada de
`~/winay-pacha-putre/public`. `~/public_html/build` había quedado como **carpeta real**
(copia estática, probablemente subida por FTP en algún deploy anterior) en vez de symlink
hacia `~/winay-pacha-putre/public/build`. Aunque se hubiera actualizado el build del
proyecto, tampoco se habría reflejado en el sitio.

## Solución aplicada (parcial)

Se corrigió el symlink en el servidor:

```bash
rm -rf ~/public_html/build
ln -s ~/winay-pacha-putre/public/build ~/public_html/build
```

## Pendiente

- **Recompilar y subir el build actualizado.** El servidor no tiene Node/npm disponible
  (`which node npm` → no encontrado), así que el flujo es:
  1. En local: `npm run build` (genera `public/build/` con hashes nuevos).
  2. Subir por FTP el contenido de `public/build/` a `~/winay-pacha-putre/public/build/` en
     el servidor, reemplazando lo que haya ahí (borrar el `build/` viejo del servidor antes
     de subir, para no mezclar archivos con hash antiguo).
  3. `php artisan view:clear` en el servidor.
  4. Verificar visualmente el sitio.
- Confirmar si `~/public_html/images` también quedó como carpeta real en vez de symlink
  (mismo patrón que `build` y `storage`) — no se revisó en este incidente. Ver sección
  "Pendiente" de `incidente-deploy-imagenes-2026-09-10.md`.

## Lección para futuros deploys

`public/build` no viaja con `git pull` (está gitignored). Cualquier pull que incluya cambios
de Blade/CSS/JS necesita, además del pull, un `npm run build` local + subida manual de
`public/build` mientras el servidor no tenga Node disponible. Ver el flujo general en
[`deploy-produccion.md`](deploy-produccion.md).
