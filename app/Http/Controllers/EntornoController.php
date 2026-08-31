<?php

namespace App\Http\Controllers;

use App\Models\Especie;
use App\Models\LugarEntorno;
use Illuminate\View\View;

class EntornoController extends Controller
{
    public function __invoke(): View
    {
        return view('entorno', [
            'lugares' => LugarEntorno::publicado()->ordenado()->with('imagenes')->get(),
            'especiesPorTipo' => Especie::publicado()->ordenado()->get()->groupBy('tipo'),
        ]);
    }
}
