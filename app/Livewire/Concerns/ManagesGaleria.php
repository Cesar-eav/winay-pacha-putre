<?php

namespace App\Livewire\Concerns;

use App\Models\Imagen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

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

        foreach (array_values($this->nuevasFotos) as $i => $foto) {
            $path = $foto->store($carpeta, 'public');

            $modelo->imagenes()->create([
                'path' => $path,
                'alt' => (string) ($modelo->nombre ?? $modelo->titulo ?? ''),
                'orden' => $ordenBase + $i,
            ]);
        }

        $this->nuevasFotos = [];
        $this->imagenesAEliminar = [];
    }
}
