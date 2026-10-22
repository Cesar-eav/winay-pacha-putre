<?php

namespace App\Http\Controllers;

use App\Models\Cabana;
use App\Models\Tema;
use Illuminate\View\View;

class InicioController extends Controller
{
    public function __invoke(): View
    {
        $temaDestacado = Tema::categoria('cultura')->publicado()->ordenado()->with('imagenes')->first();
        $cabanasDestacadas = Cabana::tipo(Cabana::TIPO_CABANA)->publicado()->ordenado()->with('imagenes')->get();
        $habitacionesDestacadas = Cabana::tipo(Cabana::TIPO_HABITACION)->publicado()->ordenado()->with('imagenes')->get();

        return view('inicio', [
            'temaDestacado' => $temaDestacado,
            'cabanasDestacadas' => $cabanasDestacadas,
            'habitacionesDestacadas' => $habitacionesDestacadas,
        ]);
    }
}
