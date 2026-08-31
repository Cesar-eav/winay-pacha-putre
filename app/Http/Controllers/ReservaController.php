<?php

namespace App\Http\Controllers;

use App\Models\Cabana;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservaController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('reserva', [
            'cabanaSlug' => $request->query('cabana'),
            'cabanas' => Cabana::publicado()->ordenado()->get(),
        ]);
    }
}
