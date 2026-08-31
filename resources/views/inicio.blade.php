<x-winay-layout>
    <x-slot:titulo>Inicio</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid gap-10 lg:grid-cols-2 items-center">
        <div>
            <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">Putre, Región de Arica y Parinacota</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-winay-tierra">{{ $heroTitulo }}</h1>
            <p class="mt-2 text-lg text-stone-600">{{ $heroSubtitulo }}</p>
            <p class="mt-4 text-stone-600">{{ $heroTexto }}</p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('cabanas.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                    Ver cabañas
                </a>
                <a href="{{ route('cultura') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-winay-tierra border border-winay-tierra hover:bg-winay-arena transition">
                    Cultura Aymara
                </a>
            </div>
        </div>

        <x-imagen-placeholder label="Imagen: Wiñay Pacha Putre" color="terracota" class="aspect-video rounded-2xl" />
    </section>

    @if ($temaDestacado)
        <section class="bg-white border-t border-winay-arena">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid gap-8 lg:grid-cols-2 items-center">
                <x-galeria-lightbox :imagenes="$temaDestacado->imagenes" :titulo="$temaDestacado->titulo" />
                <div>
                    <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">Cultura Aymara</p>
                    <h2 class="text-2xl font-bold text-winay-tierra">{{ $temaDestacado->titulo }}</h2>
                    <p class="mt-4 text-stone-600">{{ Str::limit(strip_tags($temaDestacado->cuerpo), 280) }}</p>
                    <a href="{{ route('cultura') }}" class="mt-4 inline-block text-sm font-semibold text-winay-terracota hover:underline">
                        Conocer más sobre la cultura aymara →
                    </a>
                </div>
            </div>
        </section>
    @endif

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid gap-6 sm:grid-cols-3">
        <a href="{{ route('putre') }}" class="p-6 rounded-2xl border border-winay-arena hover:border-winay-terracota transition">
            <h3 class="font-semibold text-winay-tierra">Putre</h3>
            <p class="mt-2 text-sm text-stone-600">Qué hacer y cómo vivir Putre como un local.</p>
        </a>
        <a href="{{ route('entorno') }}" class="p-6 rounded-2xl border border-winay-arena hover:border-winay-terracota transition">
            <h3 class="font-semibold text-winay-tierra">Qué visitar</h3>
            <p class="mt-2 text-sm text-stone-600">Lugares del entorno, flora y fauna del altiplano.</p>
        </a>
        <a href="{{ route('nosotros') }}" class="p-6 rounded-2xl border border-winay-arena hover:border-winay-terracota transition">
            <h3 class="font-semibold text-winay-tierra">Nosotros</h3>
            <p class="mt-2 text-sm text-stone-600">La historia detrás de Wiñay Pacha Putre.</p>
        </a>
    </section>
</x-winay-layout>
