<x-winay-layout>
    <x-slot:titulo>Cultura Aymara</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-winay-tierra">Cultura Aymara</h1>
        <p class="mt-2 text-stone-600 max-w-2xl">
            Contenido de bienvenida a la cosmovisión, tradiciones y territorio del pueblo aymara. (Placeholder — pendiente de contenido definitivo del cliente.)
        </p>

        <div class="mt-10 space-y-16">
            @forelse ($temas as $tema)
                <article class="grid gap-8 lg:grid-cols-2 items-center">
                    <x-galeria-lightbox :imagenes="$tema->imagenes" :titulo="$tema->titulo" />
                    <div>
                        <h2 class="text-xl font-bold text-winay-tierra">{{ $tema->titulo }}</h2>
                        <div class="mt-3 text-stone-600 space-y-3">{!! $tema->cuerpo !!}</div>
                    </div>
                </article>
            @empty
                <p class="text-stone-500">Aún no hay contenido publicado en esta sección.</p>
            @endforelse
        </div>
    </section>
</x-winay-layout>
