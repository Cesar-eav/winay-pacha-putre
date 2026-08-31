<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\ServicioLocal;
use App\Models\Tema;
use Illuminate\View\View;

class ContactoController extends Controller
{
    public function __invoke(): View
    {
        return view('contacto', [
            'direccion' => Configuracion::get('contacto_direccion', 'Putre, Región de Arica y Parinacota'),
            'telefono' => Configuracion::get('contacto_telefono', '+56 9 0000 0000'),
            'whatsapp' => Configuracion::get('contacto_whatsapp', '+56 9 0000 0000'),
            'email' => Configuracion::get('contacto_email', 'contacto@winaypachaputre.cl'),
            'servicios' => ServicioLocal::ordenado()->get(),
            'temasPublicoObjetivo' => Tema::categoria('publico_objetivo')->publicado()->ordenado()->get(),
        ]);
    }
}
