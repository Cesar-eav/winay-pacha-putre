<?php

namespace App\Livewire;

use App\Models\Cabana;
use App\Models\SolicitudReserva;
use Illuminate\Support\Collection;
use Livewire\Component;

class FormularioReserva extends Component
{
    public string $nombre = '';

    public string $apellido = '';

    public string $correo = '';

    public string $whatsapp = '';

    public string $fechaLlegada = '';

    public string $fechaSalida = '';

    public int $numPersonas = 1;

    public ?int $cabanaId = null;

    public string $comentarios = '';

    public bool $enviado = false;

    public Collection $cabanas;

    public function mount(?string $cabana = null): void
    {
        $this->cabanas = Cabana::publicado()->ordenado()->get();

        if ($cabana) {
            $this->cabanaId = $this->cabanas->firstWhere('slug', $cabana)?->id;
        }
    }

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'email', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:50'],
            'fechaLlegada' => ['required', 'date', 'after_or_equal:today'],
            'fechaSalida' => ['required', 'date', 'after:fechaLlegada'],
            'numPersonas' => ['required', 'integer', 'min:1'],
            'cabanaId' => ['nullable', 'exists:cabanas,id'],
            'comentarios' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function guardar(): void
    {
        $this->validate();

        SolicitudReserva::create([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'correo' => $this->correo,
            'whatsapp' => $this->whatsapp,
            'fecha_llegada' => $this->fechaLlegada,
            'fecha_salida' => $this->fechaSalida,
            'num_personas' => $this->numPersonas,
            'cabana_id' => $this->cabanaId,
            'comentarios' => $this->comentarios,
            'estado' => 'nuevo',
        ]);

        // TODO: notificar al anfitrión (mail/WhatsApp) cuando exista integración.

        $this->reset(['nombre', 'apellido', 'correo', 'whatsapp', 'fechaLlegada', 'fechaSalida', 'numPersonas', 'cabanaId', 'comentarios']);
        $this->enviado = true;
    }

    public function render()
    {
        return view('livewire.formulario-reserva');
    }
}
