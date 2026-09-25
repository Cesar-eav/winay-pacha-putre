<x-winay-layout>
    <x-slot:titulo>Inicio</x-slot:titulo>

    {{-- SLIDER BANNER ANCHO COMPLETO --}}
    <section
        x-data="{
            slide: 0,
            slides: {{ Illuminate\Support\Js::from([
                ['src' => asset('images/inicio/61.JPG'), 'alt' => 'Vista del altiplano en Putre'],
                ['src' => asset('images/placeholder/50.JPG'), 'alt' => 'Wiñaypacha Putre'],
                ['src' => asset('images/placeholder/cabana-ejemplo.jpg'), 'alt' => 'Cabañas Wiñaypacha Putre'],
            ]) }},
            init() {
                setInterval(() => { this.slide = (this.slide + 1) % this.slides.length }, 5000)
            },
        }"
        class="relative w-full h-[90vh] min-h-80 max-h-150 overflow-hidden"
    >
        <template x-for="(item, i) in slides" :key="i">
            <img
                :src="item.src"
                :alt="item.alt"
                x-show="slide === i"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-700"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 w-full h-full object-cover"
            >
        </template>

        <button
            type="button"
            @click="slide = slide === 0 ? slides.length - 1 : slide - 1"
            class="absolute left-4 top-1/2 -translate-y-1/2 size-10 flex items-center justify-center rounded-full bg-black/30 hover:bg-black/50 text-white transition"
            aria-label="Anterior"
        >
            &#8249;
        </button>
        <button
            type="button"
            @click="slide = slide === slides.length - 1 ? 0 : slide + 1"
            class="absolute right-4 top-1/2 -translate-y-1/2 size-10 flex items-center justify-center rounded-full bg-black/30 hover:bg-black/50 text-white transition"
            aria-label="Siguiente"
        >
            &#8250;
        </button>

        <div class="absolute bottom-4 inset-x-0 flex items-center justify-center gap-2">
            <template x-for="(item, i) in slides" :key="i">
                <button
                    type="button"
                    @click="slide = i"
                    class="size-2.5 rounded-full transition"
                    :class="slide === i ? 'bg-winay-terracota' : 'bg-white/60 hover:bg-white/80'"
                    :aria-label="'Ir a la imagen ' + (i + 1)"
                ></button>
            </template>
        </div>
    </section>

    {{-- BANNER VARIAS IMAGENES   --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">Nuestras cabañas</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-winay-tierra">Cada cabaña, una forma distinta de vivir el altiplano</h1>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-0">
            @foreach ($cabanasDestacadas as $cabana)
                @php $imagen = $cabana->imagenes->first(); @endphp
                <a href="{{ route('cabanas.show', $cabana) }}"
                   class="group relative block aspect-2/1 overflow-hidden border border-winay-arena hover:border-winay-terracota hover:shadow-xl transition">
                    <img src="{{ $imagen ? $imagen->url : asset('images/placeholder/cabana-ejemplo.jpg') }}"
                         alt="{{ $imagen && $imagen->alt ? $imagen->alt : $cabana->nombre }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500 ease-out">

                    <span class="absolute inset-0 bg-linear-to-t from-black/80 via-black/10 to-transparent group-hover:from-black/90 group-hover:via-black/40 transition duration-300"></span>

                    <span class="absolute inset-0 flex items-center justify-center">
                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-white bg-winay-terracota px-5 py-2.5 rounded-full shadow-lg opacity-0 scale-75 group-hover:opacity-100 group-hover:scale-100 transition duration-300 ease-out">
                            Leer más
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3.5">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>

                    <span class="absolute inset-0 flex items-center justify-center p-4 text-center group-hover:opacity-0 transition duration-300">
                        <span class="text-white font-semibold text-4xl drop-shadow">{{ $cabana->nombre }}</span>
                    </span>
                </a>
            @endforeach

            <a href="{{ route('cabanas.index') }}"
               class="group relative block aspect-2/1 overflow-hidden border border-winay-arena hover:border-winay-terracota transition">
                <x-imagen-placeholder label="" color="andino" class="w-full h-full" />
                <span class="absolute inset-0 flex items-center justify-center bg-linear-to-t from-black/60 to-transparent p-4">
                    <span class="text-white font-semibold">Conoce todas las cabañas</span>
                </span>
            </a>
        </div>
    </section>

    <section class="bg-white border-t border-winay-arena">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid gap-10 lg:grid-cols-2 items-center">
            <div>
                <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">Putre, Región de Arica y Parinacota</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-winay-tierra">{{ $heroTitulo }}</h2>
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
            <img src="{{ asset('images/placeholder/50.JPG') }}"
                 alt="Wiñaypacha Putre"
                 class="w-full aspect-video rounded-2xl object-cover">
        </div>
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
            <p class="mt-2 text-sm text-stone-600">Lugares del entorno de Putre y el altiplano.</p>
        </a>
        <a href="{{ route('nosotros') }}" class="p-6 rounded-2xl border border-winay-arena hover:border-winay-terracota transition">
            <h3 class="font-semibold text-winay-tierra">Nosotros</h3>
            <p class="mt-2 text-sm text-stone-600">La historia detrás de WiñayPacha Putre.</p>
        </a>
    </section>
</x-winay-layout>
