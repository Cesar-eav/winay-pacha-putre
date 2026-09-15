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

    public Collection $imagenesExistentes;

    public array $imagenesAEliminar = [];

    public function cargarGaleria(?Model $modelo): void
    {
        $this->imagenesExistentes = $modelo ? $modelo->imagenes()->get() : collect();
        $this->nuevasFotos = [];
        $this->imagenesAEliminar = [];
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
        try {
            $jpg = HeicToJpg::convert($foto->getRealPath())->get();
            $imagen = imagecreatefromstring($jpg);
        } catch (\Throwable $e) {
            $imagen = false;
        }

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
}
