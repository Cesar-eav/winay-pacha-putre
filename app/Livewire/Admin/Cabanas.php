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

    public $nuevaImagenPrincipal = null;

    public ?string $previsualizacionPrincipalHeic = null;

    public ?string $imagenPrincipalActual = null;

    public bool $quitarImagenPrincipal = false;

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
            'nuevaImagenPrincipal' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,bmp,heic,heif', 'max:5120'],
            'nuevasFotos.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif,bmp,heic,heif', 'max:5120'],
        ];
    }

    public function updated(string $property, mixed $value): void
    {
        if ($property === 'nombre' && ! $this->editandoId) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedNuevaImagenPrincipal(): void
    {
        $this->previsualizacionPrincipalHeic = $this->nuevaImagenPrincipal && $this->esFormatoHeic($this->nuevaImagenPrincipal)
            ? $this->generarPrevisualizacionHeic($this->nuevaImagenPrincipal)
            : null;
    }

    public function quitarImagenPrincipalActual(): void
    {
        $this->imagenPrincipalActual = null;
        $this->quitarImagenPrincipal = true;
    }

    public function descartarImagenPrincipalNueva(): void
    {
        $this->nuevaImagenPrincipal = null;
        $this->previsualizacionPrincipalHeic = null;
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
        $this->reiniciarImagenPrincipal(null);
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
        $this->reiniciarImagenPrincipal($cabana->imagen_principal);
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

        if ($this->nuevaImagenPrincipal) {
            $datos['imagen_principal'] = ProcesadorImagen::guardar(
                $this->esFormatoHeic($this->nuevaImagenPrincipal) ? $this->heicABinarioJpg($this->nuevaImagenPrincipal, 'nuevaImagenPrincipal') : $this->nuevaImagenPrincipal,
                'cabanas',
                'nuevaImagenPrincipal',
            );
        } elseif ($this->quitarImagenPrincipal) {
            $datos['imagen_principal'] = null;
        }

        $principalAnterior = $this->editandoId ? Cabana::find($this->editandoId)?->imagen_principal : null;

        $cabana = $this->editandoId
            ? tap(Cabana::findOrFail($this->editandoId))->update($datos)
            : Cabana::create($datos);

        if (($this->nuevaImagenPrincipal || $this->quitarImagenPrincipal) && $principalAnterior) {
            ProcesadorImagen::eliminar($principalAnterior);
        }

        $cabana->equipamientos()->sync($this->equipamientosSeleccionados);

        $this->guardarGaleria($cabana, 'cabanas');

        $this->reiniciarImagenPrincipal($cabana->imagen_principal);
        $this->mostrarFormulario = false;
        session()->flash('success', 'Cabaña guardada correctamente.');
    }

    public function eliminar(int $id): void
    {
        $cabana = Cabana::findOrFail($id);

        ProcesadorImagen::eliminar($cabana->imagen_principal);

        foreach ($cabana->imagenes as $imagen) {
            ProcesadorImagen::eliminar($imagen->path);
            $imagen->delete();
        }

        $cabana->delete();

        session()->flash('success', 'Cabaña eliminada.');
    }

    protected function reiniciarImagenPrincipal(?string $actual): void
    {
        $this->nuevaImagenPrincipal = null;
        $this->previsualizacionPrincipalHeic = null;
        $this->imagenPrincipalActual = $actual;
        $this->quitarImagenPrincipal = false;
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
