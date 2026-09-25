<?php

namespace App\Http\Controllers;

use App\Models\LugarEntorno;
use Illuminate\View\View;

class EntornoController extends Controller
{
    public function index(): View
    {
        return view('entorno.index', [
            'lugares' => LugarEntorno::publicado()->ordenado()->with('imagenes')->get(),
        ]);
    }

    public function show(LugarEntorno $lugar): View
    {
        abort_unless($lugar->publicado, 404);

        $lugar->load('imagenes');

        return view('entorno.show', [
            'lugar' => $lugar,
        ]);
    }
}
