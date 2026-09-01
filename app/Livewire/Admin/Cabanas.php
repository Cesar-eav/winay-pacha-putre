<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesGaleria;
use App\Livewire\Concerns\TranslatesWithDeepl;
use App\Models\Cabana;
use App\Models\Equipamiento;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin', ['titulo' => 'Cabañas'])]
class Cabanas extends Component
{
    use ManagesGaleria, TranslatesWithDeepl;

    public bool $mostrarFormulario = false;

    public ?int $editandoId = null;

    public string $slug = '';

    public string $nombre = '';

    public int $capacidad = 2;

    public array $descripcion = ['es' => '', 'en' => '', 'fr' => ''];

    public string $precioDesde = '';

    public int $orden = 0;

    public bool $publicado = true;

    public array $equipamientosSeleccionados = [];

    protected function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', Rule::unique('cabanas', 'slug')->ignore($this->editandoId)],
            'nombre' => ['required', 'string', 'max:255'],
            'capacidad' => ['required', 'integer', 'min:1'],
            'descripcion.es' => ['required', 'string'],
            'precioDesde' => ['nullable', 'string', 'max:255'],
            'orden' => ['required', 'integer', 'min:0'],
            'nuevasFotos.*' => ['image', 'max:5120'],
        ];
    }

    public function updated(string $property, mixed $value): void
    {
        if ($property === 'nombre' && ! $this->editandoId) {
            $this->slug = Str::slug($value);
        }
    }

    public function nuevo(): void
    {
        $this->editandoId = null;
        $this->slug = '';
        $this->nombre = '';
        $this->capacidad = 2;
        $this->descripcion = ['es' => '', 'en' => '', 'fr' => ''];
        $this->precioDesde = '';
        $this->orden = 0;
        $this->publicado = true;
        $this->equipamientosSeleccionados = [];
        $this->cargarGaleria(null);
        $this->resetValidation();
        $this->mostrarFormulario = true;
    }

    public function editar(int $id): void
    {
        $cabana = Cabana::with('equipamientos')->findOrFail($id);

        $this->editandoId = $cabana->id;
        $this->slug = $cabana->slug;
        $this->nombre = $cabana->nombre;
        $this->capacidad = $cabana->capacidad;
        $this->descripcion = $cabana->getTranslations('descripcion') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->precioDesde = (string) $cabana->precio_desde;
        $this->orden = $cabana->orden;
        $this->publicado = $cabana->publicado;
        $this->equipamientosSeleccionados = $cabana->equipamientos->pluck('id')->all();
        $this->cargarGaleria($cabana);
        $this->resetValidation();
        $this->mostrarFormulario = true;
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
            'slug' => $this->slug,
            'nombre' => $this->nombre,
            'capacidad' => $this->capacidad,
            'descripcion' => $this->descripcion,
            'precio_desde' => $this->precioDesde ?: null,
            'orden' => $this->orden,
            'publicado' => $this->publicado,
        ];

        $cabana = $this->editandoId
            ? tap(Cabana::findOrFail($this->editandoId))->update($datos)
            : Cabana::create($datos);

        $cabana->equipamientos()->sync($this->equipamientosSeleccionados);

        $this->guardarGaleria($cabana, 'cabanas');

        $this->mostrarFormulario = false;
        session()->flash('success', 'Cabaña guardada correctamente.');
    }

    public function eliminar(int $id): void
    {
        $cabana = Cabana::findOrFail($id);

        foreach ($cabana->imagenes as $imagen) {
            if (! str_starts_with($imagen->path, 'placeholder/')) {
                Storage::disk('public')->delete($imagen->path);
            }
            $imagen->delete();
        }

        $cabana->delete();

        session()->flash('success', 'Cabaña eliminada.');
    }

    public function render()
    {
        return view('livewire.admin.cabanas', [
            'cabanas' => Cabana::orderBy('orden')->get(),
            'equipamientosPorAmbito' => Equipamiento::orderBy('orden')->get()->groupBy('ambito'),
        ]);
    }
}
