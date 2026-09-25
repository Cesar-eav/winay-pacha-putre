<x-winay-layout>
    <x-slot:titulo>{{ $lugar->nombre }}</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 lg:grid-cols-2">
        <x-galeria-lightbox :imagenes="$lugar->imagenes" :titulo="$lugar->nombre" />

        <div>
            <a href="{{ route('entorno') }}" class="text-sm text-winay-terracota hover:text-winay-tierra">&larr; Qué Visitar</a>

            <h1 class="mt-2 text-3xl font-bold text-winay-tierra">{{ $lugar->nombre }}</h1>
            <p class="mt-1 text-stone-500">{{ $lugar->ubicacion_texto }}</p>

            <div class="mt-4 text-stone-600 space-y-3">{!! $lugar->descripcion !!}</div>
        </div>
    </section>
</x-winay-layout>
