<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesEstadoYOrden;
use App\Livewire\Concerns\TranslatesWithDeepl;
use App\Models\ImagenCarrusel;
use App\Services\ProcesadorImagen;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maestroerror\HeicToJpg;

#[Layout('admin.layouts.admin', ['titulo' => 'Carrusel del inicio'])]
class Carrusel extends Component
{
    use TranslatesWithDeepl, WithFileUploads, ManagesEstadoYOrden;

    public const ANCHO_MINIMO = 1200;

    public bool $mostrarFormulario = false;

    public ?int $editandoId = null;

    public array $frase = ['es' => '', 'en' => '', 'fr' => ''];

    public string $encuadre = 'centro';

    public int $orden = 0;

    public bool $publicado = true;

    public $nuevaFoto = null;

    public ?string $imagenActual = null;

    protected function rules(): array
    {
        $foto = [$this->editandoId ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp,heic,heif', 'max:25600'];

        // getimagesize() no lee HEIC: esas fotos (iPhone) no se pueden medir antes de convertirlas.
        if ($this->nuevaFoto && ! $this->esHeic()) {
            $foto[] = 'dimensions:min_width='.self::ANCHO_MINIMO;
        }

        return [
            'nuevaFoto' => $foto,
            'frase.es' => ['nullable', 'string', 'max:120'],
            'frase.en' => ['nullable', 'string', 'max:160'],
            'frase.fr' => ['nullable', 'string', 'max:160'],
            'encuadre' => ['required', Rule::in(array_keys(ImagenCarrusel::ENCUADRES))],
            'orden' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function messages(): array
    {
        return [
            'nuevaFoto.required' => 'Sube una foto para el carrusel.',
            'nuevaFoto.image' => 'Formato no permitido. Usa JPG, PNG, WebP o HEIC.',
            'nuevaFoto.mimes' => 'Formato no permitido. Usa JPG, PNG, WebP o HEIC.',
            'nuevaFoto.max' => 'La foto pesa más de 25 MB.',
            'nuevaFoto.dimensions' => 'La foto mide menos de '.self::ANCHO_MINIMO.' px de ancho y se vería borrosa a pantalla completa. Usa una de al menos 2000 px.',
            'frase.es.max' => 'La frase debe tener como máximo 120 caracteres.',
        ];
    }

    public function updatedNuevaFoto(): void
    {
        $this->validateOnly('nuevaFoto');
    }

    public function nuevo(): void
    {
        $this->editandoId = null;
        $this->frase = ['es' => '', 'en' => '', 'fr' => ''];
        $this->encuadre = 'centro';
        $this->orden = (int) ImagenCarrusel::max('orden') + 1;
        $this->publicado = true;
        $this->nuevaFoto = null;
        $this->imagenActual = null;
        $this->resetValidation();
        $this->mostrarFormulario = true;
    }

    public function editar(int $id): void
    {
        $imagen = ImagenCarrusel::findOrFail($id);

        $this->editandoId = $imagen->id;
        $this->frase = $imagen->getTranslations('frase') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->encuadre = $imagen->encuadre;
        $this->orden = $imagen->orden;
        $this->publicado = $imagen->publicado;
        $this->nuevaFoto = null;
        $this->imagenActual = $imagen->medium_url;
        $this->resetValidation();
        $this->mostrarFormulario = true;
    }

    public function cancelar(): void
    {
        $this->nuevaFoto = null;
        $this->mostrarFormulario = false;
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate();

        $guardada = $this->editandoId ? ImagenCarrusel::findOrFail($this->editandoId) : null;

        $this->completarTraducciones(['frase'], $guardada);

        $datos = [
            'frase' => trim($this->frase['es']) === ''
                ? ['es' => '', 'en' => '', 'fr' => '']
                : array_map(fn ($texto) => trim((string) $texto), $this->frase),
            'encuadre' => $this->encuadre,
            'orden' => $this->orden,
            'publicado' => $this->publicado,
        ];

        if ($this->nuevaFoto) {
            $datos['imagen'] = ProcesadorImagen::guardar($this->binarioFoto(), 'carrusel', 'nuevaFoto');
        }

        $fotoAnterior = $guardada?->imagen;

        $guardada ? $guardada->update($datos) : ImagenCarrusel::create($datos);

        if ($this->nuevaFoto && $fotoAnterior) {
            ProcesadorImagen::eliminar($fotoAnterior);
        }

        $this->nuevaFoto = null;
        $this->mostrarFormulario = false;
        session()->flash('success', 'Imagen del carrusel guardada correctamente.');
    }

    public function eliminar(int $id): void
    {
        $imagen = ImagenCarrusel::findOrFail($id);

        ProcesadorImagen::eliminar($imagen->imagen);

        $imagen->delete();

        session()->flash('success', 'Imagen eliminada del carrusel.');
    }

    public function esHeic(): bool
    {
        return $this->nuevaFoto
            && in_array(strtolower($this->nuevaFoto->getClientOriginalExtension()), ['heic', 'heif'], true);
    }

    protected function binarioFoto()
    {
        if (! $this->esHeic()) {
            return $this->nuevaFoto;
        }

        try {
            return HeicToJpg::convert($this->nuevaFoto->getRealPath())->get();
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                'nuevaFoto' => 'No se pudo convertir la foto HEIC. Expórtala como JPG e inténtalo de nuevo.',
            ]);
        }
    }

    protected function consultaListado(): Builder
    {
        return ImagenCarrusel::query();
    }

    public function render()
    {
        return view('admin.carrusel', [
            'imagenes' => $this->consultaListado()->ordenado()->get(),
        ]);
    }
}
