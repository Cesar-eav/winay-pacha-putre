<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\TranslatesWithDeepl;
use App\Models\Especie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin', ['titulo' => 'Qué Visitar — Flora y Fauna'])]
class Especies extends Component
{
    use TranslatesWithDeepl, WithFileUploads;

    public bool $mostrarFormulario = false;

    public ?int $editandoId = null;

    public string $nombreComun = '';

    public string $nombreCientifico = '';

    public string $tipo = 'ave';

    public array $descripcion = ['es' => '', 'en' => '', 'fr' => ''];

    public array $dondeObservar = ['es' => '', 'en' => '', 'fr' => ''];

    public int $orden = 0;

    public bool $publicado = true;

    public $nuevaFoto = null;

    public ?string $imagenActual = null;

    public bool $quitarFotoActual = false;

    protected function rules(): array
    {
        return [
            'nombreComun' => ['required', 'string', 'max:255'],
            'nombreCientifico' => ['nullable', 'string', 'max:255'],
            'tipo' => ['required', Rule::in(['mamifero', 'ave', 'otro'])],
            'descripcion.es' => ['required', 'string'],
            'dondeObservar.es' => ['required', 'string'],
            'orden' => ['required', 'integer', 'min:0'],
            'nuevaFoto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ];
    }

    public function nuevo(): void
    {
        $this->editandoId = null;
        $this->nombreComun = '';
        $this->nombreCientifico = '';
        $this->tipo = 'ave';
        $this->descripcion = ['es' => '', 'en' => '', 'fr' => ''];
        $this->dondeObservar = ['es' => '', 'en' => '', 'fr' => ''];
        $this->orden = 0;
        $this->publicado = true;
        $this->nuevaFoto = null;
        $this->imagenActual = null;
        $this->quitarFotoActual = false;
        $this->resetValidation();
        $this->mostrarFormulario = true;
    }

    public function editar(int $id): void
    {
        $especie = Especie::findOrFail($id);

        $this->editandoId = $especie->id;
        $this->nombreComun = $especie->nombre_comun;
        $this->nombreCientifico = (string) $especie->nombre_cientifico;
        $this->tipo = $especie->tipo;
        $this->descripcion = $especie->getTranslations('descripcion') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->dondeObservar = $especie->getTranslations('donde_observar') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->orden = $especie->orden;
        $this->publicado = $especie->publicado;
        $this->nuevaFoto = null;
        $this->imagenActual = $especie->imagen;
        $this->quitarFotoActual = false;
        $this->resetValidation();
        $this->mostrarFormulario = true;
    }

    public function quitarFoto(): void
    {
        $this->imagenActual = null;
        $this->quitarFotoActual = true;
    }

    public function cancelar(): void
    {
        $this->mostrarFormulario = false;
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate();

        $datos = [
            'nombre_comun' => $this->nombreComun,
            'nombre_cientifico' => $this->nombreCientifico ?: null,
            'tipo' => $this->tipo,
            'descripcion' => $this->descripcion,
            'donde_observar' => $this->dondeObservar,
            'orden' => $this->orden,
            'publicado' => $this->publicado,
        ];

        if ($this->nuevaFoto) {
            $datos['imagen'] = $this->nuevaFoto->store('especies', 'public');
        } elseif ($this->quitarFotoActual) {
            $datos['imagen'] = null;
        }

        $fotoAnterior = $this->editandoId ? Especie::find($this->editandoId)?->imagen : null;

        $especie = $this->editandoId
            ? tap(Especie::findOrFail($this->editandoId))->update($datos)
            : Especie::create($datos);

        if (($this->nuevaFoto || $this->quitarFotoActual) && $fotoAnterior && ! str_starts_with($fotoAnterior, 'placeholder/')) {
            Storage::disk('public')->delete($fotoAnterior);
        }

        $this->nuevaFoto = null;
        $this->quitarFotoActual = false;
        $this->mostrarFormulario = false;
        session()->flash('success', 'Especie guardada correctamente.');
    }

    public function eliminar(int $id): void
    {
        $especie = Especie::findOrFail($id);

        if ($especie->imagen && ! str_starts_with($especie->imagen, 'placeholder/')) {
            Storage::disk('public')->delete($especie->imagen);
        }

        $especie->delete();

        session()->flash('success', 'Especie eliminada.');
    }

    public function render()
    {
        return view('livewire.admin.especies', [
            'especies' => Especie::orderBy('orden')->get(),
        ]);
    }
}
