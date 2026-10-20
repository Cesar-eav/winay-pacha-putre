<?php

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\WithFileUploads;
use App\Services\ProcesadorImagen;
use Maestroerror\HeicToJpg;

trait ManagesGaleria
{
    use WithFileUploads;

    public array $nuevasFotos = [];

    public array $fotosPendientes = [];

    public array $previsualizacionesHeic = [];

    public Collection $imagenesExistentes;

    /**
     * Orden visible de la galería mezclando fotos ya guardadas ("e-{id}") y
     * fotos recién subidas aún sin guardar ("n-{aleatorio}").
     */
    public array $ordenGaleria = [];

    public array $altsGaleria = [];

    public array $imagenesAEliminar = [];

    public ?string $clavePrincipal = null;

    public array $erroresFotos = [];

    public function maximoFotosGaleria(): int
    {
        return 12;
    }

    public function usaImagenPrincipal(): bool
    {
        return false;
    }

    public function cargarGaleria(?Model $modelo, ?string $pathPrincipal = null): void
    {
        $this->imagenesExistentes = $modelo ? $modelo->imagenes()->get() : collect();
        $this->ordenGaleria = $this->imagenesExistentes->map(fn ($imagen) => 'e-'.$imagen->id)->all();
        $this->altsGaleria = $this->imagenesExistentes->mapWithKeys(fn ($imagen) => ['e-'.$imagen->id => (string) $imagen->alt])->all();
        $this->nuevasFotos = [];
        $this->fotosPendientes = [];
        $this->previsualizacionesHeic = [];
        $this->imagenesAEliminar = [];
        $this->erroresFotos = [];

        $principal = $pathPrincipal ? $this->imagenesExistentes->firstWhere('path', $pathPrincipal) : null;
        $this->clavePrincipal = $principal ? 'e-'.$principal->id : null;
        $this->asegurarPrincipal();
    }

    /**
     * El navegador sube las fotos de a una, así que cada llamada trae
     * normalmente un solo archivo; se valida aquí mismo para poder decir
     * cuál falló, en vez de esperar al guardado del formulario.
     */
    public function updatedNuevasFotos(): void
    {
        foreach ($this->nuevasFotos as $foto) {
            $nombre = $foto->getClientOriginalName();
            $error = $this->validarFotoNueva($foto);
            $previsualizacion = null;

            if (! $error && $this->esFormatoHeic($foto)) {
                $previsualizacion = $this->generarPrevisualizacionHeic($foto);
                $error = $previsualizacion ? null : 'No se pudo leer la foto HEIC. Expórtala como JPG e inténtalo de nuevo.';
            }

            if ($error) {
                $this->erroresFotos[] = ['nombre' => $nombre, 'motivo' => $error];

                continue;
            }

            $clave = 'n-'.Str::random(10);
            $this->fotosPendientes[$clave] = $foto;
            $this->previsualizacionesHeic[$clave] = $previsualizacion;
            $this->altsGaleria[$clave] = '';
            $this->ordenGaleria[] = $clave;
        }

        $this->nuevasFotos = [];
        $this->asegurarPrincipal();
    }

    protected function validarFotoNueva($foto): ?string
    {
        if (count($this->ordenGaleria) >= $this->maximoFotosGaleria()) {
            return 'Se alcanzó el máximo de '.$this->maximoFotosGaleria().' imágenes.';
        }

        $validador = Validator::make(
            ['foto' => $foto],
            ['foto' => ['image', 'mimes:jpg,jpeg,png,webp,gif,bmp,heic,heif', 'max:25600']],
            [
                'foto.image' => 'Formato no permitido. Usa JPG, PNG, WebP o HEIC.',
                'foto.mimes' => 'Formato no permitido. Usa JPG, PNG, WebP o HEIC.',
                'foto.max' => 'Pesa más de 25 MB.',
            ],
        );

        if ($validador->fails()) {
            return $validador->errors()->first('foto');
        }

        if (! $this->esFormatoHeic($foto) && @getimagesize($foto->getRealPath()) === false) {
            return 'El archivo está dañado o no es una imagen válida.';
        }

        return null;
    }

    public function itemsGaleria(): array
    {
        $items = [];

        foreach ($this->ordenGaleria as $clave) {
            if (str_starts_with($clave, 'e-')) {
                $imagen = $this->imagenesExistentes->firstWhere('id', (int) substr($clave, 2));

                if ($imagen) {
                    $items[] = ['clave' => $clave, 'url' => $imagen->thumb_url, 'nombre' => $imagen->alt, 'nueva' => false];
                }
            } elseif ($foto = $this->fotosPendientes[$clave] ?? null) {
                $items[] = [
                    'clave' => $clave,
                    'url' => $this->fotoEsPrevisualizable($foto) ? $foto->temporaryUrl() : ($this->previsualizacionesHeic[$clave] ?? null),
                    'nombre' => $foto->getClientOriginalName(),
                    'nueva' => true,
                ];
            }
        }

        return $items;
    }

    public function ordenarGaleria(string $clave, int $posicion): void
    {
        $indice = array_search($clave, $this->ordenGaleria, true);

        if ($indice === false) {
            return;
        }

        array_splice($this->ordenGaleria, $indice, 1);
        array_splice($this->ordenGaleria, max(0, min($posicion, count($this->ordenGaleria))), 0, [$clave]);
    }

    public function marcarPrincipal(string $clave): void
    {
        if ($this->usaImagenPrincipal() && in_array($clave, $this->ordenGaleria, true)) {
            $this->clavePrincipal = $clave;
        }
    }

    public function eliminarFoto(string $clave): void
    {
        if (! in_array($clave, $this->ordenGaleria, true)) {
            return;
        }

        $this->ordenGaleria = array_values(array_diff($this->ordenGaleria, [$clave]));
        unset($this->altsGaleria[$clave], $this->fotosPendientes[$clave], $this->previsualizacionesHeic[$clave]);

        if (str_starts_with($clave, 'e-')) {
            $this->imagenesAEliminar[] = (int) substr($clave, 2);
        }

        if ($this->clavePrincipal === $clave) {
            $this->clavePrincipal = null;
        }

        $this->asegurarPrincipal();
    }

    protected function asegurarPrincipal(): void
    {
        if (! $this->usaImagenPrincipal()) {
            return;
        }

        if (! in_array($this->clavePrincipal, $this->ordenGaleria, true)) {
            $this->clavePrincipal = $this->ordenGaleria[0] ?? null;
        }
    }

    public function fotoEsPrevisualizable($foto): bool
    {
        $extension = strtolower($foto->getClientOriginalExtension());

        return in_array($extension, config('livewire.temporary_file_upload.preview_mimes'), true);
    }

    /**
     * Devuelve la ruta guardada de cada foto de la galería, indexada por su
     * clave, para que el componente pueda resolver la imagen principal.
     */
    protected function guardarGaleria(Model $modelo, string $carpeta): array
    {
        $rutasNuevas = [];

        foreach ($this->fotosPendientes as $clave => $foto) {
            try {
                $rutasNuevas[$clave] = ProcesadorImagen::guardar(
                    $this->esFormatoHeic($foto) ? $this->heicABinarioJpg($foto) : $foto,
                    $carpeta,
                );
            } catch (\Throwable $e) {
                foreach ($rutasNuevas as $ruta) {
                    ProcesadorImagen::eliminar($ruta);
                }

                throw ValidationException::withMessages([
                    'galeria' => 'No se pudo procesar «'.$foto->getClientOriginalName().'». Expórtala como JPG o PNG, súbela de nuevo e inténtalo otra vez.',
                ]);
            }
        }

        foreach ($modelo->imagenes()->whereKey(array_unique($this->imagenesAEliminar))->get() as $imagen) {
            ProcesadorImagen::eliminar($imagen->path);

            $imagen->delete();
        }

        $rutas = [];
        $altPorDefecto = (string) ($modelo->nombre ?? $modelo->titulo ?? '');

        foreach (array_values($this->ordenGaleria) as $orden => $clave) {
            $alt = trim((string) ($this->altsGaleria[$clave] ?? ''));

            if (str_starts_with($clave, 'e-')) {
                $imagen = $this->imagenesExistentes->firstWhere('id', (int) substr($clave, 2));

                if (! $imagen) {
                    continue;
                }

                $modelo->imagenes()->whereKey($imagen->id)->update(['orden' => $orden, 'alt' => $alt]);
                $rutas[$clave] = $imagen->path;
            } elseif (isset($rutasNuevas[$clave])) {
                $modelo->imagenes()->create([
                    'path' => $rutasNuevas[$clave],
                    'alt' => $alt !== '' ? $alt : $altPorDefecto,
                    'orden' => $orden,
                ]);
                $rutas[$clave] = $rutasNuevas[$clave];
            }
        }

        $this->nuevasFotos = [];
        $this->fotosPendientes = [];
        $this->previsualizacionesHeic = [];
        $this->imagenesAEliminar = [];

        return $rutas;
    }

    protected function esFormatoHeic($foto): bool
    {
        return in_array(strtolower($foto->getClientOriginalExtension()), ['heic', 'heif'], true);
    }

    protected function heicABinarioJpg($foto, string $campoError = 'nuevasFotos'): string
    {
        try {
            return HeicToJpg::convert($foto->getRealPath())->get();
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                $campoError => 'No se pudo convertir una de las fotos HEIC. Expórtala como JPG o PNG e inténtalo de nuevo.',
            ]);
        }
    }

    /**
     * Miniatura JPG codificada en base64 para previsualizar una foto HEIC
     * antes de guardar el formulario (mismo pipeline de conversión que
     * convertirHeicAWebp, pero reducida de tamaño porque viaja embebida en
     * el HTML de cada respuesta de Livewire mientras el formulario esté abierto).
     */
    protected function generarPrevisualizacionHeic($foto): ?string
    {
        $imagen = $this->heicAImagenGd($foto);

        if (! $imagen) {
            return null;
        }

        $ladoMaximo = 400;
        $ancho = imagesx($imagen);
        $alto = imagesy($imagen);

        if (max($ancho, $alto) > $ladoMaximo) {
            $factor = $ladoMaximo / max($ancho, $alto);
            $miniatura = imagescale($imagen, (int) round($ancho * $factor), (int) round($alto * $factor));
            $imagen = $this->reemplazarImagen($imagen, $miniatura);
        }

        ob_start();
        imagejpeg($imagen, null, 75);
        $bytes = ob_get_clean();
        imagedestroy($imagen);

        return 'data:image/jpeg;base64,'.base64_encode($bytes);
    }

    /**
     * Decodifica un HEIC a un recurso GD ya con la orientación EXIF corregida.
     * Devuelve false si el archivo no es un HEIC válido.
     */
    protected function heicAImagenGd($foto)
    {
        try {
            $jpg = HeicToJpg::convert($foto->getRealPath())->get();
        } catch (\Throwable $e) {
            return false;
        }

        $temporalJpg = tempnam(sys_get_temp_dir(), 'heic').'.jpg';
        file_put_contents($temporalJpg, $jpg);
        $imagen = @imagecreatefromjpeg($temporalJpg);

        if (! $imagen) {
            unlink($temporalJpg);

            return false;
        }

        $imagen = $this->corregirOrientacionExif($imagen, $temporalJpg);
        unlink($temporalJpg);

        return $imagen;
    }

    /**
     * El JPEG que extrae HeicToJpg conserva el tag EXIF Orientation del HEIC
     * original, pero GD lo ignora al decodificar y WebP no lo conserva al
     * guardar. Sin esta corrección, las fotos tomadas en vertical (la
     * mayoría desde iPhone) quedan giradas en el WebP final.
     */
    protected function corregirOrientacionExif($imagen, string $rutaJpg)
    {
        $orientacion = @exif_read_data($rutaJpg)['Orientation'] ?? 1;

        switch ($orientacion) {
            case 3:
                $imagen = $this->reemplazarImagen($imagen, imagerotate($imagen, 180, 0));
                break;
            case 5:
                $imagen = $this->reemplazarImagen($imagen, imagerotate($imagen, -90, 0));
                imageflip($imagen, IMG_FLIP_HORIZONTAL);
                break;
            case 6:
                $imagen = $this->reemplazarImagen($imagen, imagerotate($imagen, -90, 0));
                break;
            case 7:
                $imagen = $this->reemplazarImagen($imagen, imagerotate($imagen, 90, 0));
                imageflip($imagen, IMG_FLIP_HORIZONTAL);
                break;
            case 8:
                $imagen = $this->reemplazarImagen($imagen, imagerotate($imagen, 90, 0));
                break;
            case 2:
                imageflip($imagen, IMG_FLIP_HORIZONTAL);
                break;
            case 4:
                imageflip($imagen, IMG_FLIP_VERTICAL);
                break;
        }

        return $imagen;
    }

    protected function reemplazarImagen($imagenAnterior, $imagenNueva)
    {
        imagedestroy($imagenAnterior);

        return $imagenNueva;
    }
}
