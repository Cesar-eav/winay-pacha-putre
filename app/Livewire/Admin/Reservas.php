<?php

namespace App\Livewire\Admin;

use App\Models\SolicitudReserva;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.admin', ['titulo' => 'Solicitudes de reserva'])]
class Reservas extends Component
{
    use WithPagination;

    #[Url]
    public string $filtro = 'nuevo';

    public function updatedFiltro(): void
    {
        $this->resetPage();
    }

    public function cambiarEstado(int $id, string $estado): void
    {
        validator(['estado' => $estado], ['estado' => Rule::in(array_keys(SolicitudReserva::ESTADOS))])->validate();

        SolicitudReserva::findOrFail($id)->update(['estado' => $estado]);
    }

    public function eliminar(int $id): void
    {
        SolicitudReserva::findOrFail($id)->delete();

        session()->flash('success', 'Solicitud eliminada.');
    }

    public function render()
    {
        $solicitudes = SolicitudReserva::query()
            ->with('cabana')
            ->when($this->filtro !== 'todas', fn ($q) => $q->where('estado', $this->filtro))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20);

        $totales = SolicitudReserva::selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $pestanas = collect(SolicitudReserva::ESTADOS)
            ->map(fn ($etiqueta, $estado) => [$etiqueta.'s', $totales[$estado] ?? 0])
            ->put('todas', ['Todas', null]);

        return view('admin.reservas', [
            'solicitudes' => $solicitudes,
            'pestanas' => $pestanas,
        ]);
    }
}
