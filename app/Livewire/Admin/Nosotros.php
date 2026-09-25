<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesGaleria;
use App\Livewire\Concerns\TranslatesWithDeepl;
use App\Models\PaginaNosotros;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin', ['titulo' => 'Nosotros'])]
class Nosotros extends Component
{
    use ManagesGaleria, TranslatesWithDeepl;

    public array $tituloHistoria = ['es' => '', 'en' => '', 'fr' => ''];

    public array $historia = ['es' => '', 'en' => '', 'fr' => ''];

    public array $tituloMensaje = ['es' => '', 'en' => '', 'fr' => ''];

    public array $mensaje = ['es' => '', 'en' => '', 'fr' => ''];

    protected function rules(): array
    {
        return [
            'tituloHistoria.es' => ['required', 'string', 'max:255'],
            'historia.es' => ['required', 'string'],
            'tituloMensaje.es' => ['required', 'string', 'max:255'],
            'mensaje.es' => ['required', 'string'],
            'nuevasFotos.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif,bmp,heic,heif', 'max:5120'],
        ];
    }

    public function mount(): void
    {
        $pagina = PaginaNosotros::singleton();

        $this->tituloHistoria = $pagina->getTranslations('titulo_historia') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->historia = $pagina->getTranslations('historia') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->tituloMensaje = $pagina->getTranslations('titulo_mensaje') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->mensaje = $pagina->getTranslations('mensaje') + ['es' => '', 'en' => '', 'fr' => ''];
        $this->cargarGaleria($pagina);
    }

    public function guardar(): void
    {
        $this->validate();

        $pagina = PaginaNosotros::singleton();

        $pagina->update([
            'titulo_historia' => $this->tituloHistoria,
            'historia' => $this->historia,
            'titulo_mensaje' => $this->tituloMensaje,
            'mensaje' => $this->mensaje,
        ]);

        $this->guardarGaleria($pagina, 'nosotros');

        session()->flash('success', 'Página Nosotros actualizada.');
    }

    public function render()
    {
        return view('livewire.admin.nosotros');
    }
}
