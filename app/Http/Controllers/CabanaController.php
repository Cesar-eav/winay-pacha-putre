<?php

namespace App\Http\Controllers;

use App\Models\Cabana;
use Illuminate\View\View;

class CabanaController extends Controller
{
    public function index(): View
    {
        return view('cabanas.index', [
            'cabanas' => Cabana::publicado()->ordenado()->with('imagenes')->get(),
        ]);
    }

    public function show(Cabana $cabana): View
    {
        abort_unless($cabana->publicado, 404);

        $cabana->load(['imagenes', 'equipamientos']);

        return view('cabanas.show', [
            'cabana' => $cabana,
            'equipamientosPorAmbito' => $cabana->equipamientos->groupBy('ambito'),
        ]);
    }
}
