<?php

namespace App\Livewire\Admin;

use App\Models\LeadContacto;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.admin', ['titulo' => 'Mensajes de contacto'])]
class Contacto extends Component
{
    use WithPagination;

    #[Url]
    public string $filtro = 'pendientes';

    public function updatedFiltro(): void
    {
        $this->resetPage();
    }

    public function alternarAtendido(int $id): void
    {
        $lead = LeadContacto::findOrFail($id);
        $lead->update(['atendido' => ! $lead->atendido]);
    }

    public function eliminar(int $id): void
    {
        LeadContacto::findOrFail($id)->delete();

        session()->flash('success', 'Mensaje eliminado.');
    }

    public function render()
    {
        $leads = LeadContacto::query()
            ->when($this->filtro === 'pendientes', fn ($q) => $q->where('atendido', false))
            ->when($this->filtro === 'atendidos', fn ($q) => $q->where('atendido', true))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.contacto', [
            'leads' => $leads,
            'pestanas' => [
                'pendientes' => ['Pendientes', LeadContacto::where('atendido', false)->count()],
                'atendidos' => ['Atendidos', LeadContacto::where('atendido', true)->count()],
                'todos' => ['Todos', null],
            ],
        ]);
    }
}
