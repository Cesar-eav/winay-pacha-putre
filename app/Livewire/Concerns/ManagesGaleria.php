<?php

namespace App\Livewire\Concerns;

use App\Models\Imagen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\WithFileUploads;
use Maestroerror\HeicToJpg;

trait ManagesGaleria
{
    use WithFileUploads;

    public array $nuevasFotos = [];

    public array $previsualizacionesHeic = [];

    public Collection $imagenesExistentes;

    public array $imagenesAEliminar = [];

    public function cargarGaleria(?Model $modelo): void
    {
        $this->imagenesExistentes = $modelo ? $modelo->imagenes()->get() : collect();
        $this->nuevasFotos = [];
        $this->previsualizacionesHeic = [];
        $this->imagenesAEliminar = [];
    }

    /**
     * El navegador no puede mostrar un <img> de un archivo HEIC directamente
     * (salvo Safari), así que generamos una miniatura JPG en el servidor
     * apenas se suben las fotos, en vez de esperar al guardado del formulario.
     */
    public function updatedNuevasFotos(): void
    {
        $this->previsualizacionesHeic = [];

        foreach ($this->nuevasFotos as $i => $foto) {
            $this->previsualizacionesHeic[$i] = $this->esFormatoHeic($foto)
                ? $this->generarPrevisualizacionHeic($foto)
                : null;
        }
    }

    public function eliminarFotoExistente(int $id): void
    {
        $this->imagenesExistentes = $this->imagenesExistentes
            ->reject(fn ($imagen) => $imagen->id === $id)
            ->values();

        $this->imagenesAEliminar[] = $id;
    }

    public function eliminarFotoNueva(int $index): void
    {
        unset($this->nuevasFotos[$index]);
        $this->nuevasFotos = array_values($this->nuevasFotos);

        unset($this->previsualizacionesHeic[$index]);
        $this->previsualizacionesHeic = array_values($this->previsualizacionesHeic);
    }

    public function fotoEsPrevisualizable($foto): bool
    {
        $extension = strtolower($foto->getClientOriginalExtension());

        return in_array($extension, config('livewire.temporary_file_upload.preview_mimes'), true);
    }

    public function moverFotoExistente(int $id, string $direccion): void
    {
        $items = $this->imagenesExistentes->values();
        $index = $items->search(fn ($imagen) => $imagen->id === $id);

        if ($index === false) {
            return;
        }

        $nuevoIndex = $direccion === 'arriba' ? $index - 1 : $index + 1;

        if ($nuevoIndex < 0 || $nuevoIndex >= $items->count()) {
            return;
        }

        $temp = $items[$index];
        $items[$index] = $items[$nuevoIndex];
        $items[$nuevoIndex] = $temp;

        $this->imagenesExistentes = $items->values();
    }

    protected function guardarGaleria(Model $modelo, string $carpeta): void
    {
        $rutasNuevas = [];

        foreach (array_values($this->nuevasFotos) as $foto) {
            $rutasNuevas[] = $this->esFormatoHeic($foto)
                ? $this->convertirHeicAWebp($foto, $carpeta)
                : $foto->store($carpeta, 'public');
        }

        foreach ($this->imagenesAEliminar as $id) {
            $imagen = Imagen::find($id);

            if (! $imagen) {
                continue;
            }

            if (! str_starts_with($imagen->path, 'placeholder/')) {
                Storage::disk('public')->delete($imagen->path);
            }

            $imagen->delete();
        }

        foreach ($this->imagenesExistentes->values() as $orden => $imagen) {
            Imagen::where('id', $imagen->id)->update(['orden' => $orden]);
        }

        $ordenBase = $this->imagenesExistentes->count();

        foreach ($rutasNuevas as $i => $path) {
            $modelo->imagenes()->create([
                'path' => $path,
                'alt' => (string) ($modelo->nombre ?? $modelo->titulo ?? ''),
                'orden' => $ordenBase + $i,
            ]);
        }

        $this->nuevasFotos = [];
        $this->imagenesAEliminar = [];
    }

    protected function esFormatoHeic($foto): bool
    {
        return in_array(strtolower($foto->getClientOriginalExtension()), ['heic', 'heif'], true);
    }

    protected function convertirHeicAWebp($foto, string $carpeta): string
    {
        $imagen = $this->heicAImagenGd($foto);

        if (! $imagen) {
            throw ValidationException::withMessages([
                'nuevasFotos' => 'No se pudo convertir una de las fotos HEIC. Expórtala como JPG o PNG e inténtalo de nuevo.',
            ]);
        }

        $path = $carpeta.'/'.Str::random(40).'.webp';
        $temporal = tempnam(sys_get_temp_dir(), 'webp');
        imagewebp($imagen, $temporal);
        imagedestroy($imagen);

        Storage::disk('public')->put($path, file_get_contents($temporal));
        unlink($temporal);

        return $path;
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
