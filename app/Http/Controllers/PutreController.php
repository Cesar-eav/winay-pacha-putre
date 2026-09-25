<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use Illuminate\View\View;

class PutreController extends Controller
{
    public function __invoke(): View
    {
        return view('putre', [
            'temas' => Tema::categoria('putre_blog')
                ->publicado()
                ->ordenado()
                ->with('imagenes')
                ->get(),
        ]);
    }
}
