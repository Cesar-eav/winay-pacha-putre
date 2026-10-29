<x-mail::message>
# Nuevo mensaje de contacto

**Nombre:** {{ $lead->nombre }}<br>
**Correo:** {{ $lead->correo }}<br>
@if ($lead->telefono)
**Teléfono:** {{ $lead->telefono }}<br>
@endif

<x-mail::panel>
{{ $lead->mensaje }}
</x-mail::panel>

Responde este correo para contestarle directamente.

<x-mail::button :url="route('admin.contacto')">
Ver en el panel
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
