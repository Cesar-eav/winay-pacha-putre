<x-winay-layout>
    <x-slot:titulo>Cabañas</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-winay-tierra">Cabañas</h1>
        <p class="mt-2 text-stone-600 max-w-2xl">
            Precios y disponibilidad son informativos — la reserva se confirma manualmente con el anfitrión.
        </p>

        <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($cabanas as $cabana)
                <a href="{{ route('cabanas.show', $cabana) }}" class="block rounded-2xl border border-winay-arena overflow-hidden hover:border-winay-terracota transition">
                    <div class="aspect-video">
                        <x-galeria-lightbox :imagenes="$cabana->imagenes" :titulo="$cabana->nombre" />
                    </div>
                    <div class="p-5">
                        <h2 class="font-semibold text-winay-tierra">{{ $cabana->nombre }}</h2>
                        <p class="mt-1 text-sm text-stone-500">Hasta {{ $cabana->capacidad }} personas</p>
                        <p class="mt-2 text-sm text-stone-600">{{ Str::limit(strip_tags($cabana->descripcion), 120) }}</p>
                        @if ($cabana->precio_desde)
                            <p class="mt-3 text-sm font-semibold text-winay-terracota">Desde {{ $cabana->precio_desde }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-stone-500">Aún no hay cabañas publicadas.</p>
            @endforelse
        </div>
    </section>
</x-winay-layout>
