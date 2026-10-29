@php $esHabitacion = $tipo === \App\Models\Cabana::TIPO_HABITACION; @endphp

<x-winay-layout>
    <x-slot:titulo>{{ $cabana->nombre }}</x-slot:titulo>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-16">
        <x-galeria-mosaico :imagenes="$cabana->galeriaPublica()" :titulo="$cabana->nombre" />

        <section class="mt-8 lg:mt-10 grid gap-10 lg:grid-cols-3 lg:items-start">
            <div class="lg:col-span-2">
                <h1 class="text-5xl sm:text-6xl nombre-alojamiento text-winay-tierra text-balance">{{ $cabana->nombre }}</h1>
                @include('cabanas.partials.detalles', ['clase' => 'mt-2 text-stone-500'])

                <div class="mt-6 max-w-prose text-stone-600 space-y-3">{!! $cabana->descripcion !!}</div>

                @if ($equipamientosPorCategoria->isNotEmpty())
                    <div class="mt-8">
                        <h2 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ __('Equipamiento') }}</h2>
                        @foreach ($equipamientosPorCategoria as $categoria => $items)
                            <h3 class="mt-5 text-xs font-semibold uppercase tracking-wide text-stone-500">
                                {{ \App\Support\CatalogoEquipamiento::categoriaNombre($categoria) }}
                            </h3>
                            <ul class="mt-1 grid gap-x-8 sm:grid-cols-2 text-sm text-stone-600">
                                @foreach ($items as $item)
                                    <li class="flex items-center gap-3 border-b border-winay-arena py-2.5">
                                        <x-icono-equipamiento :equipamiento="$item" class="text-winay-terracota" />
                                        {{ $item->nombre }}
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="rounded-2xl border border-winay-arena bg-white p-6 shadow-[0_4px_20px_rgb(107_68_35/0.08)] lg:sticky lg:top-28">
                @if ($cabana->numero)
                    <p class="text-sm font-semibold text-winay-terracota">{{ __($esHabitacion ? 'Habitación N° :n' : 'Cabaña N° :n', ['n' => $cabana->numero]) }}</p>
                @endif

                @if ($cabana->tarifas)
                    <h2 class="mt-1 font-display text-xl font-medium tracking-[-0.01em] text-winay-tierra">{{ __('Precio por noche') }}</h2>
                    <dl class="mt-2 text-sm">
                        @foreach ($cabana->tarifas as $personas => $monto)
                            <div class="flex items-baseline justify-between gap-4 border-b border-winay-arena py-2.5">
                                <dt class="text-stone-600">{{ trans_choice(':n persona|:n personas', $personas, ['n' => $personas]) }}</dt>
                                <dd class="font-display text-lg font-medium text-winay-tierra">{{ \App\Models\Cabana::formatearPrecio($monto) }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @elseif ($cabana->precio_texto)
                    <p class="mt-1 font-display text-3xl font-medium tracking-[-0.01em] text-winay-tierra">{{ $cabana->precio_texto }}</p>
                @endif

                <a href="{{ lroute('reserva', ['cabana' => $cabana->slug]) }}"
                   class="mt-5 flex w-full items-center justify-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                    {{ $esHabitacion ? __('Reservar esta habitación') : __('Reservar esta cabaña') }}
                </a>
            </aside>
        </section>
    </div>
</x-winay-layout>
