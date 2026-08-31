<x-winay-layout>
    <x-slot:titulo>{{ $cabana->nombre }}</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 lg:grid-cols-2">
        <x-galeria-lightbox :imagenes="$cabana->imagenes" :titulo="$cabana->nombre" />

        <div>
            <h1 class="text-3xl font-bold text-winay-tierra">{{ $cabana->nombre }}</h1>
            <p class="mt-1 text-stone-500">Hasta {{ $cabana->capacidad }} personas</p>

            @if ($cabana->precio_desde)
                <p class="mt-3 text-lg font-semibold text-winay-terracota">Desde {{ $cabana->precio_desde }}</p>
            @endif

            <div class="mt-4 text-stone-600 space-y-3">{!! $cabana->descripcion !!}</div>

            @foreach ($equipamientosPorAmbito as $ambito => $items)
                <div class="mt-6">
                    <h2 class="font-semibold text-winay-tierra">
                        {{ $ambito === 'cabana' ? 'Equipamiento de la cabaña' : 'Equipamiento de la habitación' }}
                    </h2>
                    <ul class="mt-2 grid grid-cols-2 gap-2 text-sm text-stone-600">
                        @foreach ($items as $item)
                            <li>{{ $item->nombre }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <a href="{{ route('reserva', ['cabana' => $cabana->slug]) }}"
               class="mt-8 inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                Reservar esta cabaña
            </a>
        </div>
    </section>
</x-winay-layout>
