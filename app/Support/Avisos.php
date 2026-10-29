<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Avisos
{
    // El lead ya quedó guardado y visible en el panel: un fallo de Mailgun no debe mostrarle un error al visitante.
    public static function notificarAnfitrion(Mailable $correo): void
    {
        try {
            Mail::to(config('mail.contacto'))
                ->locale('es')
                ->send($correo);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
