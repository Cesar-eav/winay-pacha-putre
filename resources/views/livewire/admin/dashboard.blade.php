<div>
    <h1 class="text-2xl font-bold text-winay-tierra mb-6">Dashboard</h1>

    <div class="grid gap-4 sm:grid-cols-2 mb-8">
        <div class="bg-white rounded-xl border border-stone-200 p-5">
            <p class="text-sm text-stone-500">Mensajes de contacto</p>
            <p class="mt-1 text-3xl font-bold text-winay-tierra">{{ $leadsContactoTotal }}</p>
            <p class="mt-1 text-xs text-stone-500">
                {{ $leadsContactoPendientes }} {{ Str::plural('pendiente', $leadsContactoPendientes) }} por atender
            </p>
        </div>

        <div class="bg-white rounded-xl border border-stone-200 p-5">
            <p class="text-sm text-stone-500">Solicitudes de reserva</p>
            <p class="mt-1 text-3xl font-bold text-winay-tierra">{{ $solicitudesReservaTotal }}</p>
            <p class="mt-1 text-xs text-stone-500">
                {{ $solicitudesReservaNuevas }} {{ Str::plural('nueva', $solicitudesReservaNuevas) }} sin gestionar
            </p>
        </div>
    </div>

    <h2 class="text-sm font-semibold uppercase tracking-wide text-stone-500 mb-3">Contenido por sección</h2>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($secciones as $seccion)
            <a href="{{ $seccion['ruta'] }}" class="block bg-white rounded-xl border border-stone-200 p-5 hover:border-winay-terracota transition-colors">
                <p class="text-sm text-stone-500">{{ $seccion['nombre'] }}</p>
                <p class="mt-1 text-3xl font-bold text-winay-tierra">{{ $seccion['total'] }}</p>

                @if (count($seccion['detalle']))
                    <ul class="mt-3 space-y-1 text-xs text-stone-500">
                        @foreach ($seccion['detalle'] as $etiqueta => $total)
                            <li class="flex items-center justify-between">
                                <span>{{ $etiqueta }}</span>
                                <span class="font-medium text-stone-700">{{ $total }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </a>
        @endforeach
    </div>
</div>
