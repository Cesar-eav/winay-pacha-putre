<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesGaleria;
use App\Livewire\Concerns\TranslatesWithDeepl;
use App\Models\Tema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin', ['titulo' => 'Temas'])]
class Temas extends Component
{
    use ManagesGaleria, TranslatesWithDeepl;

    public string $categoriaFiltro = 'cultura';

    public bool $mostrarFormulario = false;

    public ?int $editandoId = null;

    public string $slug = '';

    public string $categoria = 'cultura';

    public array $titulo = ['es' => '', 'en' => '', 'fr' => ''];

    public array $cuerpo = ['es' => '', 'en' => '', 'fr' => ''];

    public int $orden = 0;

    public bool $publicado = true;

    protected function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', Rule::unique('temas', 'slug')->ignore($this->editandoId)],
            'categoria' => ['required', Rule::in(['cultura', 'actividad', 'vive_local', 'publico_objetivo'])],
            'titulo.es' => ['required', 'string', 'max:255'],
            'cuerpo.es' => ['required', 'string'],
            'orden' => ['required', 'integer', 'min:0'],
            'nuevasFotos.*' => ['image', 'max:5120'],
        ];
    }

    public function updated(string $property, mixed $value): void
    {
        if ($property === 'titulo.es' && ! $this->editandoId) {
            $this->slug = Str::slug($value);
        }
    }

    public function nuevo(): void
    {
        $this->editandoId = null;
        $this->slug = '';
        $this->categoria = $this->categoriaFiltro;
        $this->titulo = ['es' => '', 'en' => '', 'fr' => ''];
        $this->cuerpo = ['es' => '', 'en' => '', 'fr' => ''];
        $this->orden = 0;
        $this->publicado = true;
        $this->cargarGaleria(null);
        $this->resetValidation();
        $this->mostrarFormulario = true;
    }

    public function editar(int $id): void
    {
        $tema = Tema::findOrFail($id);

        $this->editandoId = $tema->id;
        $this->slug = $tema->slug;
        $this->categoria = $tema->categoria;
        $this->titulo = $tema->getTranslations('titulo') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->cuerpo = $tema->getTranslations('cuerpo') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->orden = $tema->orden;
        $this->publicado = $tema->publicado;
        $this->cargarGaleria($tema);
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
            'categoria' => $this->categoria,
            'titulo' => $this->titulo,
            'cuerpo' => $this->cuerpo,
            'orden' => $this->orden,
            'publicado' => $this->publicado,
        ];

        $tema = $this->editandoId
            ? tap(Tema::findOrFail($this->editandoId))->update($datos)
            : Tema::create($datos);

        $this->guardarGaleria($tema, 'temas');

        $this->mostrarFormulario = false;
        session()->flash('success', 'Tema guardado correctamente.');
    }

    public function eliminar(int $id): void
    {
        $tema = Tema::findOrFail($id);

        foreach ($tema->imagenes as $imagen) {
            if (! str_starts_with($imagen->path, 'placeholder/')) {
                Storage::disk('public')->delete($imagen->path);
            }
            $imagen->delete();
        }

        $tema->delete();

        session()->flash('success', 'Tema eliminado.');
    }

    public function render()
    {
        return view('livewire.admin.temas', [
            'temas' => Tema::where('categoria', $this->categoriaFiltro)->orderBy('orden')->get(),
        ]);
    }
}
