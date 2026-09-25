<x-winay-layout>
    <x-slot:titulo>Qué Visitar</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-winay-tierra">Qué Visitar</h1>
        <p class="mt-2 text-stone-600 max-w-2xl">Lugares del entorno de Putre y el altiplano.</p>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($lugares as $lugar)
                <a href="{{ route('entorno.show', $lugar) }}" class="block rounded-2xl border border-winay-arena overflow-hidden hover:border-winay-terracota transition">
                    <div class="aspect-video">
                        <x-galeria-lightbox :imagenes="$lugar->imagenes" :titulo="$lugar->nombre" />
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-winay-tierra">{{ $lugar->nombre }}</h3>
                        <p class="mt-1 text-xs text-stone-500">{{ $lugar->ubicacion_texto }}</p>
                        <p class="mt-2 text-sm text-stone-600">{{ Str::limit(strip_tags($lugar->descripcion), 140) }}</p>
                    </div>
                </a>
            @empty
                <p class="text-stone-500">Aún no hay lugares publicados.</p>
            @endforelse
        </div>
    </section>
</x-winay-layout>
