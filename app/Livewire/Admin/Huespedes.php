<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesGaleria;
use App\Models\Galeria;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.admin', ['titulo' => 'Fotos de huéspedes'])]
class Huespedes extends Component
{
    use ManagesGaleria {
        validarFotoNueva as validarFotoGaleria;
    }

    public const ANCHO_MINIMO = 600;

    public function maximoFotosGaleria(): int
    {
        return 24;
    }

    public function mount(): void
    {
        $this->cargarGaleria(Galeria::de(Galeria::HUESPEDES));
    }

    public function guardar(): void
    {
        $galeria = Galeria::de(Galeria::HUESPEDES);

        $this->guardarGaleria($galeria, 'huespedes');
        $this->cargarGaleria($galeria);

        session()->flash('success', 'Fotos de huéspedes guardadas correctamente.');
    }

    protected function validarFotoNueva($foto): ?string
    {
        if ($error = $this->validarFotoGaleria($foto)) {
            return $error;
        }

        // getimagesize() no lee HEIC: esas fotos (iPhone) no se pueden medir antes de convertirlas.
        if (! $this->esFormatoHeic($foto) && getimagesize($foto->getRealPath())[0] < self::ANCHO_MINIMO) {
            return 'Mide menos de '.self::ANCHO_MINIMO.' px de ancho y se vería borrosa. Usa una de al menos 1200 px.';
        }

        return null;
    }

    public function render()
    {
        return view('admin.huespedes');
    }
}
