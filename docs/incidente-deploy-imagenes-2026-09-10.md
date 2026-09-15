# Incidente: imágenes no cargaban en producción (10-09-2026)

## Síntoma

Se subió por FTP la imagen `cabana-ejemplo.jpg` al proyecto y también se subió una foto de
cabaña desde el panel admin. Ninguna de las dos se veía en `https://winaypachaputre.cl/`, sin
error visible en la consola del navegador.

## Causa raíz

El dominio no sirve directamente la carpeta `public/` del proyecto Laravel. En el servidor
(cPanel, cuenta `cwi118900`) la estructura es:

```
~/winay-pacha-putre/        # proyecto Laravel (código, git)
~/public_html/              # document root real del dominio — carpeta física aparte
```

`public_html/index.php` sí apunta correctamente al proyecto (`require
__DIR__.'/../winay-pacha-putre/vendor/autoload.php'`), pero el **contenido estático**
(`images/`, `build/`, `storage/`) vive duplicado en ambas carpetas y no se sincroniza solo.
Cualquier archivo nuevo subido a `winay-pacha-putre/public/...` no llega al sitio en vivo
hasta que se copia también a `public_html/...`.

Esto produjo dos fallas relacionadas pero distintas:

### 1. `cabana-ejemplo.jpg` (subida por FTP)

Se subió al `public/images/placeholder/` del proyecto, pero el sitio se sirve desde
`public_html/images/`. En este caso resultó ser una falsa pista: la página de cabañas no
llama a esa imagen en absoluto — usa `placeholder/cabana-1.svg`, definido en la tabla
`imagenes` de la base de datos (ver sección "Pendiente" más abajo).

### 2. Foto subida desde el admin (Livewire)

El componente [`ManagesGaleria`](../app/Livewire/Concerns/ManagesGaleria.php) guarda los
archivos con `Storage::disk('public')->store(...)`, es decir en
`winay-pacha-putre/storage/app/public/`. La URL pública depende del symlink que crea
`php artisan storage:link` (`public/storage → storage/app/public`).

En el servidor, ese symlink **nunca se había creado correctamente**: tanto
`winay-pacha-putre/public/storage` como `public_html/storage` eran carpetas reales (no
symlinks), con el mismo contenido parcial (`temas/`, sin `cabanas/`). Lo más probable es que
en algún deploy anterior por FTP se haya subido el contenido apuntado por el symlink en vez
del symlink mismo (comportamiento típico de clientes FTP con symlinks locales).

Resultado: el archivo subido sí quedaba físicamente en
`storage/app/public/cabanas/<hash>.jpg` (visible por FTP/terminal), pero no había nada en el
servidor web que lo expusiera en `/storage/cabanas/<hash>.jpg` → 404.

## Solución aplicada

```bash
# 1. Respaldo de las carpetas "storage" falsas antes de tocar nada
mv ~/winay-pacha-putre/public/storage ~/winay-pacha-putre/public/storage_backup_20260910
mv ~/public_html/storage ~/public_html/storage_backup_20260910

# 2. Symlink real del proyecto
cd ~/winay-pacha-putre
php artisan storage:link

# 3. public_html/storage apunta al storage real del proyecto
ln -s ~/winay-pacha-putre/public/storage ~/public_html/storage
```

Verificado con `curl -sI https://winaypachaputre.cl/storage/cabanas/<hash>.jpg` → `200 OK`.

## Pendiente

- **`images/` y `build/` siguen desacopladas** entre `winay-pacha-putre/public/` y
  `public_html/`. Cualquier imagen subida por FTP directo al proyecto, o cualquier
  `npm run build`, sigue requiriendo copia manual a `public_html/` hasta que se apliquen los
  mismos symlinks:
  ```bash
  cd ~/public_html
  rm -rf images && ln -s ~/winay-pacha-putre/public/images images
  rm -rf build   && ln -s ~/winay-pacha-putre/public/build  build
  ```
- **Las 3 cabañas semilla (Titicaca, Parinacota, Lauca) muestran `placeholder/cabana-1.svg`**
  en vez de una foto real. Es un dato desactualizado en la tabla `imagenes` (quedó de una
  versión anterior del seeder) y se corrige subiendo la foto real desde el admin — ya no
  debería haber problema de servidor para que se vea, gracias al symlink de `storage`.
- Confirmar que las fotos de `temas` (las únicas que había antes del incidente) siguen
  visibles en el sitio, y una vez confirmado, borrar los backups:
  ```bash
  rm -rf ~/winay-pacha-putre/public/storage_backup_20260910 ~/public_html/storage_backup_20260910
  ```

## Lección para futuros deploys

Nunca subir por FTP una carpeta que en local es un symlink (`public/storage`) — el archivo o
carpeta enlazada puede subir aplanada como copia real. En el servidor, `storage:link` debe
correrse siempre vía terminal (`php artisan storage:link`), no vía FTP.
