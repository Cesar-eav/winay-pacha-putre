<?php

namespace App\Livewire\Admin;

use App\Models\Cabana;
use App\Models\Especie;
use App\Models\Galeria;
use App\Models\ImagenCarrusel;
use App\Models\LeadContacto;
use App\Models\LugarEntorno;
use App\Models\SolicitudReserva;
use App\Models\Tema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.admin', ['titulo' => 'Dashboard'])]
class Dashboard extends Component
{
    public function render()
    {
        $temasPorCategoria = Tema::selectRaw('categoria, count(*) as total')
            ->whereIn('categoria', array_keys(Tema::CATEGORIAS))
            ->groupBy('categoria')
            ->pluck('total', 'categoria');

        $especiesPorTipo = Especie::selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        return view('admin.dashboard', [
            'leadsContactoTotal' => LeadContacto::count(),
            'leadsContactoPendientes' => LeadContacto::where('atendido', false)->count(),
            'solicitudesReservaTotal' => SolicitudReserva::count(),
            'solicitudesReservaNuevas' => SolicitudReserva::where('estado', 'nuevo')->count(),
            'secciones' => [
                [
                    'nombre' => 'Carrusel del inicio',
                    'ruta' => route('admin.carrusel'),
                    'total' => ImagenCarrusel::count(),
                    'detalle' => [
                        'Publicadas' => ImagenCarrusel::where('publicado', true)->count(),
                        'Borrador' => ImagenCarrusel::where('publicado', false)->count(),
                    ],
                ],
                [
                    'nombre' => 'Fotos de huéspedes',
                    'ruta' => route('admin.huespedes'),
                    'total' => Galeria::where('clave', Galeria::HUESPEDES)->first()?->imagenes()->count() ?? 0,
                    'detalle' => [],
                ],
                [
                    'nombre' => 'Temas',
                    'ruta' => route('admin.temas'),
                    'total' => $temasPorCategoria->sum(),
                    'detalle' => $temasPorCategoria->mapWithKeys(
                        fn ($total, $categoria) => [Tema::CATEGORIAS[$categoria] => $total]
                    ),
                ],
                [
                    'nombre' => 'Cabañas',
                    'ruta' => route('admin.cabanas'),
                    'total' => Cabana::tipo(Cabana::TIPO_CABANA)->count(),
                    'detalle' => [
                        'Publicadas' => Cabana::tipo(Cabana::TIPO_CABANA)->where('publicado', true)->count(),
                        'Borrador' => Cabana::tipo(Cabana::TIPO_CABANA)->where('publicado', false)->count(),
                    ],
                ],
                [
                    'nombre' => 'Habitaciones',
                    'ruta' => route('admin.habitaciones'),
                    'total' => Cabana::tipo(Cabana::TIPO_HABITACION)->count(),
                    'detalle' => [
                        'Publicadas' => Cabana::tipo(Cabana::TIPO_HABITACION)->where('publicado', true)->count(),
                        'Borrador' => Cabana::tipo(Cabana::TIPO_HABITACION)->where('publicado', false)->count(),
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
