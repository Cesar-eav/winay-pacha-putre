<x-winay-layout>
    <x-slot:titulo>{{ $cabana->nombre }}</x-slot:titulo>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-16">
        <x-galeria-mosaico :imagenes="$cabana->galeriaPublica()" :titulo="$cabana->nombre" />

        <section class="mt-8 lg:mt-10 grid gap-10 lg:grid-cols-3 lg:items-start">
            <div class="lg:col-span-2">
                <h1 class="text-4xl sm:text-5xl font-display font-medium tracking-[-0.01em] text-winay-tierra text-balance">{{ $cabana->nombre }}</h1>
                @include('cabanas.partials.detalles', ['clase' => 'mt-2 text-stone-500'])

                <div class="mt-6 max-w-prose text-stone-600 space-y-3">{!! $cabana->descripcion !!}</div>

                @foreach ($equipamientosPorAmbito as $ambito => $items)
                    <div class="mt-8">
                        <h2 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">
                            {{ $ambito === 'cabana' ? __('Equipamiento de la cabaña') : __('Equipamiento de la habitación') }}
                        </h2>
                        <ul class="mt-2 grid grid-cols-2 gap-2 text-sm text-stone-600">
                            @foreach ($items as $item)
                                <li>{{ $item->nombre }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <aside class="rounded-2xl border border-winay-arena bg-white p-6 shadow-[0_4px_20px_rgb(107_68_35/0.08)] lg:sticky lg:top-28">
                @if ($cabana->numero)
                    <p class="text-sm font-semibold text-winay-terracota">{{ __('Cabaña N° :n', ['n' => $cabana->numero]) }}</p>
                @endif

                @if ($cabana->precio_texto)
                    <p class="mt-1 font-display text-3xl font-medium tracking-[-0.01em] text-winay-tierra">{{ $cabana->precio_texto }}</p>
                @endif

                <a href="{{ lroute('reserva', ['cabana' => $cabana->slug]) }}"
                   class="mt-5 flex w-full items-center justify-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                    {{ __('Reservar esta cabaña') }}
                </a>
            </aside>
        </section>
    </div>
</x-winay-layout>
