# Deploy a producción — flujo de `git pull`

Servidor: cPanel, cuenta `cwi118900` en `srv15`. Proyecto Laravel en `~/winay-pacha-putre`,
vinculado a `origin` (`https://github.com/Cesar-eav/winay-pacha-putre.git`), rama `main`.

> **Importante**: el document root del dominio es `~/public_html`, una carpeta física
> **separada** de `~/winay-pacha-putre/public`. Ver
> [`incidente-deploy-imagenes-2026-09-10.md`](incidente-deploy-imagenes-2026-09-10.md) para el
> detalle de por qué. Esto significa que un `git pull` actualiza el código pero **no**
> actualiza por sí solo lo que el navegador ve, a menos que `public_html/images`,
> `public_html/build` y `public_html/storage` sigan siendo symlinks hacia
> `winay-pacha-putre/public/...` (se dejaron así resuelto el incidente). Si en algún momento
> dejan de serlo (por ejemplo, alguien vuelve a subir esas carpetas por FTP), hay que
> recrearlos con los comandos de la sección "Pendiente" de ese documento antes de dar el pull
> por terminado.

## Flujo normal (código sin cambios de dependencias)

```bash
cd ~/winay-pacha-putre
git pull origin main
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan route:clear
```

## Cuando `composer.json` / `composer.lock` cambiaron

Composer no está en el `PATH` global de este hosting — se usa el binario local
`~/composer.phar` (instalado una vez, ver abajo si no existe).

```bash
cd ~/winay-pacha-putre
git pull origin main
php ~/composer.phar install --no-dev --optimize-autoloader
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan route:clear
```

Si `~/composer.phar` no existe (primera vez en un servidor nuevo):

```bash
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

## Cuando cambia Blade/CSS/JS (assets compilados)

Confirmado: este hosting **no tiene Node/npm** (`which node npm` → no encontrado). `public/build`
está en `.gitignore`, así que `git pull` nunca lo actualiza — todo cambio visual requiere este
paso manual (ver [`incidente-build-assets-desactualizados-2026-09-15.md`](incidente-build-assets-desactualizados-2026-09-15.md)):

1. En local: `npm run build`.
2. Subir por FTP el contenido de `public/build/` a `~/winay-pacha-putre/public/build/` en el
   servidor, reemplazando lo anterior (borrar el `build/` viejo del servidor antes de subir).
3. Verificar que `public_html/build` sigue siendo symlink hacia
   `winay-pacha-putre/public/build` (no una carpeta real copiada por FTP):
   ```bash
   ls -la ~/public_html | grep build
   ```
   Si no lo es:
   ```bash
   rm -rf ~/public_html/build
   ln -s ~/winay-pacha-putre/public/build ~/public_html/build
   ```
4. `php artisan view:clear`.

## Cuando hay migraciones nuevas

```bash
php artisan migrate --force
```

(`--force` es necesario porque `APP_ENV=production` bloquea migraciones interactivas.)

## Verificación post-pull

1. `git status` → debe quedar limpio (salvo `public/storage_backup_20260910/` u otros
   untracked conocidos).
2. Confirmar que `public_html/images`, `public_html/build` y `public_html/storage` siguen
   siendo symlinks:
   ```bash
   ls -la ~/public_html | grep -E 'images|build|storage'
   ```
   Deben mostrar `->` apuntando a `~/winay-pacha-putre/public/...`.
3. Cargar el sitio en el navegador y revisar las páginas afectadas por el pull.

## Primera vinculación (referencia histórica)

Estos pasos ya se hicieron una vez (15-09-2026) para pasar de un deploy FTP sin git a uno
vinculado al repo. No hace falta repetirlos en pulls normales, quedan documentados por si se
necesita vincular un servidor nuevo desde cero:

```bash
# Backup antes de tocar nada
cd ~
tar -czf backup-winay-pacha-putre-$(date +%Y%m%d).tar.gz winay-pacha-putre \
  --exclude=winay-pacha-putre/vendor --exclude=winay-pacha-putre/node_modules

cd ~/winay-pacha-putre
git init
git remote add origin https://github.com/Cesar-eav/winay-pacha-putre.git
git fetch origin

# Revisar diffs antes de sobrescribir (por si hay ajustes manuales en producción)
git --no-pager diff origin/main -- <archivos-clave>

git reset --mixed origin/main
git checkout origin/main -- .
git branch -m master main   # si el init creó "master" en vez de "main"
git branch --set-upstream-to=origin/main main
```
