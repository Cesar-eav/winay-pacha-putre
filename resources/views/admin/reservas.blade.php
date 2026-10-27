@php
    $coloresEstado = [
        'nuevo' => 'bg-winay-terracota/10 text-winay-terracota',
        'contactado' => 'bg-amber-50 text-amber-800',
        'confirmado' => 'bg-winay-andino/10 text-winay-andino',
        'cerrado' => 'bg-stone-100 text-stone-500',
    ];
@endphp

<div>
    @include('admin.partials.banner-exito')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-winay-tierra">Solicitudes de reserva</h1>

        <div class="inline-flex flex-wrap rounded-2xl sm:rounded-full bg-white border border-stone-200 p-1 text-sm">
            @foreach ($pestanas as $clave => [$etiqueta, $total])
                <button type="button" wire:click="$set('filtro', '{{ $clave }}')"
                        class="px-3 py-1.5 rounded-full {{ $filtro === $clave ? 'bg-winay-terracota text-white' : 'text-stone-600 hover:text-winay-terracota' }}">
                    {{ $etiqueta }}
                    @if (! is_null($total))
                        <span class="ml-1 text-xs {{ $filtro === $clave ? 'text-white/80' : 'text-stone-400' }}">{{ $total }}</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <div class="space-y-4">
        @forelse ($solicitudes as $solicitud)
            @php($noches = (int) $solicitud->fecha_llegada->diffInDays($solicitud->fecha_salida))
            <article wire:key="solicitud-{{ $solicitud->id }}" class="bg-white rounded-2xl border p-5 {{ $solicitud->estado === 'nuevo' ? 'border-winay-terracota/40' : 'border-stone-200' }}">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="font-semibold text-winay-tierra">{{ $solicitud->nombre }} {{ $solicitud->apellido }}</h2>
                        <p class="text-xs text-stone-500">
                            Recibida {{ $solicitud->created_at->format('d-m-Y H:i') }} · {{ $solicitud->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <select wire:change="cambiarEstado({{ $solicitud->id }}, $event.target.value)" aria-label="Estado"
                            class="rounded-full border-0 py-1 pl-3 pr-8 text-xs font-medium focus:ring-winay-terracota {{ $coloresEstado[$solicitud->estado] ?? '' }}">
                        @foreach (\App\Models\SolicitudReserva::ESTADOS as $valor => $etiqueta)
                            <option value="{{ $valor }}" @selected($solicitud->estado === $valor)>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>

                <dl class="mt-4 grid gap-3 sm:grid-cols-3 text-sm">
                    <div>
                        <dt class="text-xs text-stone-500">Fechas</dt>
                        <dd class="text-stone-800">
                            {{ $solicitud->fecha_llegada->format('d-m-Y') }} → {{ $solicitud->fecha_salida->format('d-m-Y') }}
                            <span class="text-stone-500">({{ $noches }} {{ Str::plural('noche', $noches) }})</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-stone-500">Personas</dt>
                        <dd class="text-stone-800">{{ $solicitud->num_personas }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-stone-500">Alojamiento</dt>
                        <dd class="text-stone-800">
                            @if ($solicitud->cabana)
                                {{ $solicitud->cabana->nombre }}
                                <span class="text-stone-500">({{ $solicitud->cabana->tipo === \App\Models\Cabana::TIPO_HABITACION ? 'habitación' : 'cabaña' }})</span>
                            @else
                                <span class="text-stone-500">Sin preferencia</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-sm">
                    <a href="mailto:{{ $solicitud->correo }}" class="text-winay-terracota hover:underline break-all">{{ $solicitud->correo }}</a>
                    @if ($solicitud->whatsapp)
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $solicitud->whatsapp) }}" class="text-stone-600 hover:text-winay-terracota">{{ $solicitud->whatsapp }}</a>
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $solicitud->whatsapp) }}" target="_blank" rel="noopener" class="text-winay-andino hover:underline">WhatsApp</a>
                    @endif
                </div>

                @if ($solicitud->comentarios)
                    <div x-data="{ abierto: false, largo: false }" x-init="$nextTick(() => largo = $refs.texto.scrollHeight > $refs.texto.clientHeight)" class="mt-3">
                        <p x-ref="texto" :class="abierto ? '' : 'line-clamp-3'" class="text-sm text-stone-700 whitespace-pre-line">{{ $solicitud->comentarios }}</p>
                        <button type="button" x-show="largo" x-cloak @click="abierto = ! abierto" class="mt-1 text-xs text-winay-terracota hover:underline"
                                x-text="abierto ? 'Ver menos' : 'Ver comentario completo'"></button>
                    </div>
                @endif

                <div class="mt-4 text-right">
                    <button type="button" wire:click="eliminar({{ $solicitud->id }})" onclick="return confirm('¿Eliminar esta solicitud?')" class="text-sm text-red-600 hover:underline">Eliminar</button>
                </div>
            </article>
        @empty
            <div class="bg-white rounded-2xl border border-stone-200 px-4 py-8 text-center text-sm text-stone-500">
                {{ $filtro === 'todas' ? 'No hay solicitudes todavía.' : 'No hay solicitudes en este estado.' }}
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $solicitudes->links() }}
    </div>
</div>
