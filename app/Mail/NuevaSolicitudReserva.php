<?php

namespace App\Mail;

use App\Models\SolicitudReserva;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NuevaSolicitudReserva extends Mailable
{
    public function __construct(public SolicitudReserva $solicitud) {}

    public function envelope(): Envelope
    {
        $nombre = $this->solicitud->nombre.' '.$this->solicitud->apellido;

        return new Envelope(
            replyTo: [new Address($this->solicitud->correo, $nombre)],
            subject: 'Nueva solicitud de reserva: '.$nombre.' ('.$this->solicitud->fecha_llegada->format('d/m/Y').')',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.nueva-solicitud-reserva');
    }
}
