<div>
    @include('admin.partials.banner-exito')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-winay-tierra">Mensajes de contacto</h1>

        <div class="inline-flex rounded-full bg-white border border-stone-200 p-1 text-sm">
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
        @forelse ($leads as $lead)
            <article wire:key="lead-{{ $lead->id }}" class="bg-white rounded-2xl border p-5 {{ $lead->atendido ? 'border-stone-200' : 'border-winay-terracota/40' }}">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="font-semibold text-winay-tierra">{{ $lead->nombre }}</h2>
                        <p class="text-xs text-stone-500">
                            {{ $lead->created_at->format('d-m-Y H:i') }} · {{ $lead->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <button type="button" wire:click="alternarAtendido({{ $lead->id }})"
                            class="px-2 py-0.5 rounded-full text-xs {{ $lead->atendido ? 'bg-winay-andino/10 text-winay-andino hover:bg-winay-andino/20' : 'bg-winay-terracota/10 text-winay-terracota hover:bg-winay-terracota/20' }}">
                        {{ $lead->atendido ? 'Atendido' : 'Pendiente' }}
                    </button>
                </div>

                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-sm">
                    <a href="mailto:{{ $lead->correo }}" class="text-winay-terracota hover:underline break-all">{{ $lead->correo }}</a>
                    @if ($lead->telefono)
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $lead->telefono) }}" class="text-stone-600 hover:text-winay-terracota">{{ $lead->telefono }}</a>
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $lead->telefono) }}" target="_blank" rel="noopener" class="text-winay-andino hover:underline">WhatsApp</a>
                    @endif
                </div>

                <div x-data="{ abierto: false, largo: false }" x-init="$nextTick(() => largo = $refs.texto.scrollHeight > $refs.texto.clientHeight)" class="mt-3">
                    <p x-ref="texto" :class="abierto ? '' : 'line-clamp-3'" class="text-sm text-stone-700 whitespace-pre-line">{{ $lead->mensaje }}</p>
                    <button type="button" x-show="largo" x-cloak @click="abierto = ! abierto" class="mt-1 text-xs text-winay-terracota hover:underline"
                            x-text="abierto ? 'Ver menos' : 'Ver mensaje completo'"></button>
                </div>

                <div class="mt-4 text-right">
                    <button type="button" wire:click="eliminar({{ $lead->id }})" onclick="return confirm('¿Eliminar este mensaje?')" class="text-sm text-red-600 hover:underline">Eliminar</button>
                </div>
            </article>
        @empty
            <div class="bg-white rounded-2xl border border-stone-200 px-4 py-8 text-center text-sm text-stone-500">
                @switch($filtro)
                    @case('pendientes') No hay mensajes pendientes. @break
                    @case('atendidos') No hay mensajes atendidos. @break
                    @default No hay mensajes todavía.
                @endswitch
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $leads->links() }}
    </div>
</div>
