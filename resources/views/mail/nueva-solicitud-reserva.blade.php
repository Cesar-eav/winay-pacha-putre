<x-mail::message>
# Nueva solicitud de reserva

**Nombre:** {{ $solicitud->nombre }} {{ $solicitud->apellido }}<br>
**Correo:** {{ $solicitud->correo }}<br>
**WhatsApp:** {{ $solicitud->whatsapp }}

<x-mail::table>
| | |
|:--|:--|
| Llegada | {{ $solicitud->fecha_llegada->format('d/m/Y') }} |
| Salida | {{ $solicitud->fecha_salida->format('d/m/Y') }} |
| Noches | {{ (int) $solicitud->fecha_llegada->diffInDays($solicitud->fecha_salida) }} |
| Personas | {{ $solicitud->num_personas }} |
| Alojamiento | {{ $solicitud->cabana?->nombre ?? 'Sin preferencia' }} |
</x-mail::table>

@if ($solicitud->comentarios)
<x-mail::panel>
{{ $solicitud->comentarios }}
</x-mail::panel>
@endif

Responde este correo para contestarle directamente. La reserva no está confirmada hasta que la coordines con el huésped.

<x-mail::button :url="route('admin.reservas')">
Ver en el panel
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
