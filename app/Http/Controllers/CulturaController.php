<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use Illuminate\View\View;

class CulturaController extends Controller
{
    public function __invoke(): View
    {
        return view('cultura', [
            'temas' => Tema::categoria('cultura')->publicado()->ordenado()->with('imagenes')->get(),
        ]);
    }
}
