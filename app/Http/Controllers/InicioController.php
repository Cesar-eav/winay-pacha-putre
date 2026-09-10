<?php

namespace App\Http\Controllers;

use App\Models\Cabana;
use App\Models\Configuracion;
use App\Models\Tema;
use Illuminate\View\View;

class InicioController extends Controller
{
    public function __invoke(): View
    {
        $temaDestacado = Tema::categoria('cultura')->publicado()->ordenado()->with('imagenes')->first();
        $cabanasDestacadas = Cabana::publicado()->ordenado()->with('imagenes')->take(3)->get();

        return view('inicio', [
            'heroTitulo' => Configuracion::get('inicio_titulo', 'Wiñaypacha Putre'),
            'heroSubtitulo' => Configuracion::get('inicio_subtitulo', 'Cabañas en el corazón del altiplano aymara'),
            'heroTexto' => Configuracion::get('inicio_texto', 'Contenido de bienvenida pendiente de definir con el cliente.'),
            'temaDestacado' => $temaDestacado,
            'cabanasDestacadas' => $cabanasDestacadas,
        ]);
    }
}
