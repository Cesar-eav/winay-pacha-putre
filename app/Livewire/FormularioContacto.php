<?php

namespace App\Livewire;

use App\Models\LeadContacto;
use Livewire\Component;

class FormularioContacto extends Component
{
    public string $nombre = '';

    public string $correo = '';

    public string $telefono = '';

    public string $mensaje = '';

    public bool $enviado = false;

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'mensaje' => ['required', 'string', 'max:2000'],
        ];
    }

    public function guardar(): void
    {
        $this->validate();

        LeadContacto::create([
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'mensaje' => $this->mensaje,
        ]);

        // TODO: notificar al anfitrión (mail/WhatsApp) cuando exista integración.

        $this->reset(['nombre', 'correo', 'telefono', 'mensaje']);
        $this->enviado = true;
    }

    public function render()
    {
        return view('livewire.formulario-contacto');
    }
}
