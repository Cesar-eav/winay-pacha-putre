<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesEstadoYOrden;
use App\Livewire\Concerns\ManagesGaleria;
use App\Livewire\Concerns\TranslatesWithDeepl;
use App\Services\ProcesadorImagen;
use App\Models\Cabana;
use App\Models\Equipamiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.admin', ['titulo' => 'Cabañas'])]
class Cabanas extends Component
{
    use ManagesGaleria, TranslatesWithDeepl, ManagesEstadoYOrden;

    public bool $mostrarFormulario = false;

    public ?int $editandoId = null;

    public $numero = null;

    public string $slug = '';

    public string $nombre = '';

    public $capacidad = null;

    public int $cantidadBanos = 1;

    public bool $banoPrivado = true;

    public array $descripcion = ['es' => '', 'en' => '', 'fr' => ''];

    public string $precioDesde = '';

    public int $orden = 0;

    public bool $publicado = true;

    public array $equipamientosSeleccionados = [];

    protected function rules(): array
    {
        return [
            'numero' => ['nullable', 'integer', 'min:1', Rule::unique('cabanas', 'numero')->ignore($this->editandoId)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('cabanas', 'slug')->ignore($this->editandoId)],
            'nombre' => ['required', 'string', 'max:255'],
            'capacidad' => ['nullable', 'integer', 'min:1'],
            'cantidadBanos' => ['required', 'integer', 'min:0'],
            'banoPrivado' => ['boolean'],
            'descripcion.es' => ['required', 'string'],
            'precioDesde' => ['nullable', 'string', 'max:255'],
            'orden' => ['required', 'integer', 'min:0'],
        ];
    }

    public function usaImagenPrincipal(): bool
    {
        return true;
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
        $this->numero = null;
        $this->slug = '';
        $this->nombre = '';
        $this->capacidad = null;
        $this->cantidadBanos = 1;
        $this->banoPrivado = true;
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
        $this->numero = $cabana->numero;
        $this->slug = $cabana->slug;
        $this->nombre = $cabana->nombre;
        $this->capacidad = $cabana->capacidad;
        $this->cantidadBanos = $cabana->cantidad_banos;
        $this->banoPrivado = $cabana->bano_privado;
        $this->descripcion = $cabana->getTranslations('descripcion') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->precioDesde = (string) $cabana->precio_desde;
        $this->orden = $cabana->orden;
        $this->publicado = $cabana->publicado;
        $this->equipamientosSeleccionados = $cabana->equipamientos->pluck('id')->all();
        $this->cargarGaleria($cabana, $cabana->imagen_principal);
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
            'numero' => $this->numero !== null && $this->numero !== '' ? (int) $this->numero : null,
            'slug' => $this->slug,
            'nombre' => $this->nombre,
            'capacidad' => $this->capacidad !== null && $this->capacidad !== '' ? (int) $this->capacidad : null,
            'cantidad_banos' => $this->cantidadBanos,
            'bano_privado' => $this->banoPrivado,
            'descripcion' => $this->descripcion,
            'precio_desde' => $this->precioDesde ?: null,
            'orden' => $this->orden,
            'publicado' => $this->publicado,
        ];

        $cabana = $this->editandoId
            ? tap(Cabana::findOrFail($this->editandoId))->update($datos)
            : Cabana::create($datos);

        $cabana->equipamientos()->sync($this->equipamientosSeleccionados);

        $rutas = $this->guardarGaleria($cabana, 'cabanas');

        $cabana->update(['imagen_principal' => $rutas[$this->clavePrincipal] ?? null]);

        $this->mostrarFormulario = false;
        session()->flash('success', 'Cabaña guardada correctamente.');
    }

    public function eliminar(int $id): void
    {
        $cabana = Cabana::findOrFail($id);

        foreach ($cabana->imagenes as $imagen) {
            ProcesadorImagen::eliminar($imagen->path);
            $imagen->delete();
        }

        $cabana->delete();

        session()->flash('success', 'Cabaña eliminada.');
    }

    protected function consultaListado(): Builder
    {
        return Cabana::query();
    }

    public function render()
    {
        return view('admin.cabanas', [
            'cabanas' => $this->consultaListado()->orderBy('orden')->get(),
            'equipamientosPorAmbito' => Equipamiento::orderBy('orden')->get()->groupBy('ambito'),
        ]);
    }
}
