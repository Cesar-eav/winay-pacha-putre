<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadContacto extends Model
{
    protected $table = 'leads_contacto';

    protected $fillable = ['nombre', 'correo', 'telefono', 'mensaje', 'atendido'];

    protected $casts = [
        'atendido' => 'boolean',
    ];
}
