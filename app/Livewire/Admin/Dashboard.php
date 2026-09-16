<?php

namespace App\Livewire\Admin;

use App\Models\Cabana;
use App\Models\Especie;
use App\Models\LeadContacto;
use App\Models\LugarEntorno;
use App\Models\SolicitudReserva;
use App\Models\Tema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin', ['titulo' => 'Dashboard'])]
class Dashboard extends Component
{
    protected const ETIQUETAS_CATEGORIA_TEMA = [
        'cultura' => 'Cultura',
        'actividad' => 'Actividad',
        'vive_local' => 'Vive Local',
        'publico_objetivo' => 'Público Objetivo',
    ];

    public function render()
    {
        $temasPorCategoria = Tema::selectRaw('categoria, count(*) as total')
            ->groupBy('categoria')
            ->pluck('total', 'categoria');

        $especiesPorTipo = Especie::selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        return view('livewire.admin.dashboard', [
            'leadsContactoTotal' => LeadContacto::count(),
            'leadsContactoPendientes' => LeadContacto::where('atendido', false)->count(),
            'solicitudesReservaTotal' => SolicitudReserva::count(),
            'solicitudesReservaNuevas' => SolicitudReserva::where('estado', 'nuevo')->count(),
            'secciones' => [
                [
                    'nombre' => 'Temas',
                    'ruta' => route('admin.temas'),
                    'total' => Tema::count(),
                    'detalle' => $temasPorCategoria->mapWithKeys(
                        fn ($total, $categoria) => [(self::ETIQUETAS_CATEGORIA_TEMA[$categoria] ?? $categoria) => $total]
                    ),
                ],
                [
                    'nombre' => 'Cabañas',
                    'ruta' => route('admin.cabanas'),
                    'total' => Cabana::count(),
                    'detalle' => [
                        'Publicadas' => Cabana::where('publicado', true)->count(),
                        'Borrador' => Cabana::where('publicado', false)->count(),
                    ],
                ],
                [
                    'nombre' => 'Qué Visitar — Lugares',
                    'ruta' => route('admin.lugares'),
                    'total' => LugarEntorno::count(),
                    'detalle' => [
                        'Publicados' => LugarEntorno::where('publicado', true)->count(),
                        'Borrador' => LugarEntorno::where('publicado', false)->count(),
                    ],
                ],
                [
                    'nombre' => 'Qué Visitar — Flora y Fauna',
                    'ruta' => route('admin.especies'),
                    'total' => Especie::count(),
                    'detalle' => $especiesPorTipo->mapWithKeys(
                        fn ($total, $tipo) => [ucfirst($tipo) => $total]
                    ),
                ],
            ],
        ]);
    }
}
