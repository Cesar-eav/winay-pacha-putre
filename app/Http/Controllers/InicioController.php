<?php

namespace App\Http\Controllers;

use App\Models\Cabana;
use App\Models\Galeria;
use App\Models\ImagenCarrusel;
use Illuminate\View\View;

class InicioController extends Controller
{
    public function __invoke(): View
    {
        $cabanasDestacadas = Cabana::tipo(Cabana::TIPO_CABANA)->publicado()->ordenado()->with('imagenes')->get();
        $habitacionesDestacadas = Cabana::tipo(Cabana::TIPO_HABITACION)->publicado()->ordenado()->with('imagenes')->get();

        return view('inicio', [
            'carrusel' => ImagenCarrusel::publicado()->ordenado()->get(),
            'fotosHuespedes' => Galeria::where('clave', Galeria::HUESPEDES)->first()?->imagenes ?? collect(),
            'cabanasDestacadas' => $cabanasDestacadas,
            'habitacionesDestacadas' => $habitacionesDestacadas,
        ]);
    }
}
