<?php

namespace App\Mail;

use App\Models\LeadContacto;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NuevoMensajeContacto extends Mailable
{
    public function __construct(public LeadContacto $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->lead->correo, $this->lead->nombre)],
            subject: 'Nuevo mensaje de contacto: '.$this->lead->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.nuevo-mensaje-contacto');
    }
}
