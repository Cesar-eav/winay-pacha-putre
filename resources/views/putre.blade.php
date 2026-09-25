<x-winay-layout>
    <x-slot:titulo>Putre</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold tracking-tight text-winay-tierra">Putre</h1>
        <p class="mt-3 text-lg text-stone-600">
            A 3.500 metros de altura, entre volcanes y cielos despejados, Putre conserva el trazo y el ritmo de un pueblo altiplánico centenario. Esto es lo que vas a encontrar al recorrerlo.
        </p>

        <div class="mt-14">
            @forelse ($temas as $tema)
                <article class="grid gap-8 lg:grid-cols-2 items-center">
                    <div class="{{ $loop->even ? 'lg:order-2' : '' }}">
                        <x-galeria-lightbox :imagenes="$tema->imagenes" :titulo="$tema->titulo" />
                    </div>
                    <div class="max-w-prose {{ $loop->even ? 'lg:order-1' : '' }}">
                        <h2 class="text-2xl font-bold tracking-tight text-winay-tierra">{{ $tema->titulo }}</h2>
                        <div class="mt-3 text-stone-600 leading-relaxed space-y-3">{!! $tema->cuerpo !!}</div>
                    </div>
                </article>

                @unless ($loop->last)
                    <div class="my-14 flex justify-center" aria-hidden="true">
                        <svg viewBox="0 0 5 5" class="h-6 w-6 text-winay-terracota/60" fill="currentColor">
                            <rect x="1" y="0" width="1" height="1" /><rect x="2" y="0" width="1" height="1" /><rect x="3" y="0" width="1" height="1" />
                            <rect x="0" y="1" width="1" height="1" /><rect x="1" y="1" width="1" height="1" /><rect x="2" y="1" width="1" height="1" /><rect x="3" y="1" width="1" height="1" /><rect x="4" y="1" width="1" height="1" />
                            <rect x="0" y="2" width="1" height="1" /><rect x="1" y="2" width="1" height="1" /><rect x="2" y="2" width="1" height="1" /><rect x="3" y="2" width="1" height="1" /><rect x="4" y="2" width="1" height="1" />
                            <rect x="0" y="3" width="1" height="1" /><rect x="1" y="3" width="1" height="1" /><rect x="2" y="3" width="1" height="1" /><rect x="3" y="3" width="1" height="1" /><rect x="4" y="3" width="1" height="1" />
                            <rect x="1" y="4" width="1" height="1" /><rect x="2" y="4" width="1" height="1" /><rect x="3" y="4" width="1" height="1" />
                        </svg>
                    </div>
                @endunless
            @empty
                <p class="text-stone-500">Aún no hay contenido publicado.</p>
            @endforelse
        </div>
    </section>
</x-winay-layout>
