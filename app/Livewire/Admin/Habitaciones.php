<?php

namespace App\Livewire\Admin;

use App\Models\Cabana;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.admin', ['titulo' => 'Habitaciones'])]
class Habitaciones extends Cabanas
{
    protected function tipo(): string
    {
        return Cabana::TIPO_HABITACION;
    }

    protected function etiquetas(): array
    {
        return ['singular' => 'Habitación', 'plural' => 'Habitaciones', 'nueva' => 'Nueva habitación', 'carpeta' => 'habitaciones'];
    }
}
