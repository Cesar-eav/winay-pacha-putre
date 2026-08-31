<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use Illuminate\View\View;

class PutreController extends Controller
{
    public function __invoke(): View
    {
        return view('putre', [
            'actividades' => Tema::categoria('actividad')->publicado()->ordenado()->with('imagenes')->get(),
            'viveLocal' => Tema::categoria('vive_local')->publicado()->ordenado()->with('imagenes')->get(),
        ]);
    }
}
