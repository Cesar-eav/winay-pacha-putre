<?php

namespace App\Http\Controllers;

use App\Models\Cabana;
use App\Support\CatalogoEquipamiento;
use Illuminate\View\View;

class CabanaController extends Controller
{
    public function index(): View
    {
        return $this->listado(Cabana::TIPO_CABANA);
    }

    public function show(Cabana $cabana): View
    {
        return $this->ficha($cabana, Cabana::TIPO_CABANA);
    }

    public function habitaciones(): View
    {
        return $this->listado(Cabana::TIPO_HABITACION);
    }

    public function habitacion(Cabana $habitacion): View
    {
        return $this->ficha($habitacion, Cabana::TIPO_HABITACION);
    }

    private function listado(string $tipo): View
    {
        return view('cabanas.index', [
            'tipo' => $tipo,
            'cabanas' => Cabana::tipo($tipo)->publicado()->ordenado()->with('imagenes')->get(),
        ]);
    }

    private function ficha(Cabana $cabana, string $tipo): View
    {
        abort_unless($cabana->publicado && $cabana->tipo === $tipo, 404);

        $cabana->load(['imagenes', 'equipamientos']);

        return view('cabanas.show', [
            'tipo' => $tipo,
            'cabana' => $cabana,
            'equipamientosPorCategoria' => CatalogoEquipamiento::agrupar($cabana->equipamientos),
        ]);
    }
}
