<?php

namespace App\Http\Controllers;

use App\Models\PaginaNosotros;
use Illuminate\View\View;

class NosotrosController extends Controller
{
    public function __invoke(): View
    {
        return view('nosotros', [
            'pagina' => PaginaNosotros::singleton()->load('imagenes'),
        ]);
    }
}
