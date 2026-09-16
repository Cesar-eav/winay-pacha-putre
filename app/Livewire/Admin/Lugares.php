<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesGaleria;
use App\Livewire\Concerns\TranslatesWithDeepl;
use App\Models\LugarEntorno;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin', ['titulo' => 'Qué Visitar — Lugares'])]
class Lugares extends Component
{
    use ManagesGaleria, TranslatesWithDeepl;

    public bool $mostrarFormulario = false;

    public ?int $editandoId = null;

    public string $slug = '';

    public string $nombre = '';

    public string $icono = '';

    public array $descripcion = ['es' => '', 'en' => '', 'fr' => ''];

    public string $ubicacionTexto = '';

    public int $orden = 0;

    public bool $publicado = true;

    protected function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', Rule::unique('lugares_entorno', 'slug')->ignore($this->editandoId)],
            'nombre' => ['required', 'string', 'max:255'],
            'icono' => ['nullable', 'string', 'max:255'],
            'descripcion.es' => ['required', 'string'],
            'ubicacionTexto' => ['nullable', 'string', 'max:255'],
            'orden' => ['required', 'integer', 'min:0'],
            'nuevasFotos.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif,bmp,heic,heif', 'max:5120'],
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
        $this->icono = '';
        $this->descripcion = ['es' => '', 'en' => '', 'fr' => ''];
        $this->ubicacionTexto = '';
        $this->orden = 0;
        $this->publicado = true;
        $this->cargarGaleria(null);
        $this->resetValidation();
        $this->mostrarFormulario = true;
    }

    public function editar(int $id): void
    {
        $lugar = LugarEntorno::findOrFail($id);

        $this->editandoId = $lugar->id;
        $this->slug = $lugar->slug;
        $this->nombre = $lugar->nombre;
        $this->icono = (string) $lugar->icono;
        $this->descripcion = $lugar->getTranslations('descripcion') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->ubicacionTexto = (string) $lugar->ubicacion_texto;
        $this->orden = $lugar->orden;
        $this->publicado = $lugar->publicado;
        $this->cargarGaleria($lugar);
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
            'icono' => $this->icono ?: null,
            'descripcion' => $this->descripcion,
            'ubicacion_texto' => $this->ubicacionTexto ?: null,
            'orden' => $this->orden,
            'publicado' => $this->publicado,
        ];

        $lugar = $this->editandoId
            ? tap(LugarEntorno::findOrFail($this->editandoId))->update($datos)
            : LugarEntorno::create($datos);

        $this->guardarGaleria($lugar, 'lugares');

        $this->mostrarFormulario = false;
        session()->flash('success', 'Lugar guardado correctamente.');
    }

    public function eliminar(int $id): void
    {
        $lugar = LugarEntorno::findOrFail($id);

        foreach ($lugar->imagenes as $imagen) {
            if (! str_starts_with($imagen->path, 'placeholder/')) {
                Storage::disk('public')->delete($imagen->path);
            }
            $imagen->delete();
        }

        $lugar->delete();

        session()->flash('success', 'Lugar eliminado.');
    }

    public function render()
    {
        return view('livewire.admin.lugares', [
            'lugares' => LugarEntorno::orderBy('orden')->get(),
        ]);
    }
}
