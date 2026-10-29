<?php

namespace App\Support;

use App\Models\Configuracion;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Avisos
{
    public const CORREO_POR_DEFECTO = 'contacto@winaypachaputre.cl';

    // El lead ya quedó guardado y visible en el panel: un fallo de Mailgun no debe mostrarle un error al visitante.
    public static function notificarAnfitrion(Mailable $correo): void
    {
        try {
            Mail::to(Configuracion::get('contacto_email', self::CORREO_POR_DEFECTO))
                ->locale('es')
                ->send($correo);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
