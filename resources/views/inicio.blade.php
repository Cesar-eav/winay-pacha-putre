<x-winay-layout>
    <x-slot:titulo>{{ __('Inicio') }}</x-slot:titulo>

    {{-- SLIDER BANNER ANCHO COMPLETO --}}
    @php
        // sizes: con object-cover en un banner alto (móvil), la foto se dibuja más ancha que la pantalla (~70vh × aspecto).
        $banner = collect([
            ['img' => 'cabanas-cerros', 'anchos' => [640, 960, 1440, 2000], 'sizes' => 'max(100vw, 94vh)', 'pos' => 'object-[50%_65%]', 'alt' => __('Cabañas Wiñaypacha Putre al pie de los cerros')],
            ['img' => 'laguna-flamenco', 'anchos' => [640, 1024, 1536], 'sizes' => 'max(100vw, 53vh)', 'pos' => 'object-[50%_70%]', 'alt' => __('Flamenco en una laguna del altiplano')],
            ['img' => 'cabanas', 'anchos' => [640, 960, 1440, 2000], 'sizes' => 'max(100vw, 94vh)', 'pos' => 'object-[50%_65%]', 'alt' => __('Cabañas Wiñaypacha Putre')],
            ['img' => 'recinto', 'anchos' => [640, 960, 1440, 2000], 'sizes' => 'max(100vw, 94vh)', 'pos' => 'object-[50%_60%]', 'alt' => 'Wiñaypacha Putre'],
        ])->map(fn ($s) => $s + [
            'srcset' => collect($s['anchos'])->map(fn ($w) => asset("images/inicio/banner/{$s['img']}-{$w}.webp")." {$w}w")->implode(', '),
        ]);
    @endphp
    <section
        x-data="{
            slide: 0,
            total: {{ $banner->count() }},
            cargadas: 0,
            ir(i) {
                this.slide = (i + this.total) % this.total
                this.cargadas = Math.max(this.cargadas, this.slide + 1)
            },
            init() {
                const precargar = () => this.cargadas = Math.max(this.cargadas, 1)
                document.readyState === 'complete' ? precargar() : window.addEventListener('load', precargar, { once: true })
                if (! matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    setInterval(() => this.ir(this.slide + 1), 5000)
                }
            },
        }"
        class="relative w-full h-[clamp(24rem,max(70vh,38vw),85vh)] overflow-hidden bg-winay-andino"
    >
        @foreach ($banner as $i => $item)
            <img
                @if ($i === 0)
                    src="{{ asset("images/inicio/banner/{$item['img']}-1440.webp") }}"
                    srcset="{{ $item['srcset'] }}"
                    fetchpriority="high"
                @else
                    x-bind:srcset="cargadas >= {{ $i }} ? '{{ $item['srcset'] }}' : null"
                    decoding="async"
                @endif
                sizes="{{ $item['sizes'] }}"
                alt="{{ $item['alt'] }}"
                x-bind:aria-hidden="slide !== {{ $i }}"
                class="absolute inset-0 size-full object-cover {{ $item['pos'] }} transition-opacity duration-700 motion-reduce:transition-none {{ $i ? 'opacity-0' : '' }}"
                :class="{ 'opacity-0': slide !== {{ $i }} }"
            >
        @endforeach

        <button
            type="button"
            @click="ir(slide - 1)"
            class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 size-10 sm:size-14 flex items-center justify-center rounded-full bg-black/30 hover:bg-black/50 text-white transition"
            aria-label="{{ __('Anterior') }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="size-6 sm:size-8" aria-hidden="true"><path d="m15 18-6-6 6-6" /></svg>
        </button>
        <button
            type="button"
            @click="ir(slide + 1)"
            class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 size-10 sm:size-14 flex items-center justify-center rounded-full bg-black/30 hover:bg-black/50 text-white transition"
            aria-label="{{ __('Siguiente') }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="size-6 sm:size-8" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg>
        </button>

        <div class="absolute bottom-4 inset-x-0 flex items-center justify-center gap-2">
            @foreach ($banner as $i => $item)
                <button
                    type="button"
                    @click="ir({{ $i }})"
                    class="size-2.5 rounded-full transition"
                    :class="slide === {{ $i }} ? 'bg-winay-terracota' : 'bg-white/60 hover:bg-white/80'"
                    aria-label="{{ __('Ir a la imagen') }} {{ $i + 1 }}"
                ></button>
            @endforeach
        </div>
    </section>

    <x-alojamientos.grilla
        :items="$cabanasDestacadas"
        :etiqueta="__('Nuestras cabañas')"
        :titulo="__('Cada cabaña, una forma distinta de vivir el altiplano')"
        :url="lroute('cabanas.index')"
        :enlace-texto="__('Precios y detalles')"
        encabezado="h1"
    />

    @if ($habitacionesDestacadas->isNotEmpty())
        <x-alojamientos.editorial
            :items="$habitacionesDestacadas"
            :etiqueta="__('Nuestras habitaciones')"
            :titulo="__('Habitaciones cómodas para descansar en el corazón de Putre')"
            :texto="__('Dos habitaciones que llevan el nombre de paisajes del altiplano, pensadas para descansar después de recorrerlo.')"
            :url="lroute('habitaciones.index')"
            :enlace-texto="__('Ver habitaciones')"
        />
    @endif

    <section class="bg-white border-t border-winay-arena">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid gap-10 lg:grid-cols-2 items-center">
            <div>
                <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ __('Putre, Región de Arica y Parinacota') }}</p>
                <h2 class="text-3xl sm:text-4xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ __('¡Bienvenidos a Wiñay Pacha!') }}</h2>
                <p class="mt-2 text-lg text-stone-600">{{ __('Tu refugio acogedor en el corazón del altiplano.') }}</p>
                <div class="mt-4 space-y-3 text-stone-600">
                    <p>{{ __('Te invitamos a descubrir Putre, un lugar donde la naturaleza, la cultura aymara y los paisajes de la cordillera se unen para regalarte una experiencia inolvidable.') }}</p>
                    <p>{{ __('En Wiñay Pacha Cabañas y Turismo te recibimos con calidez y hospitalidad, ofreciéndote un lugar cómodo y acogedor para descansar, disfrutar de tu estadía y sentirte como en casa. Nuestras cabañas están equipadas para que vivas una experiencia independiente, ya sea que viajes en pareja, en familia o en busca de nuevas aventuras.') }}</p>
                    <p>{{ __('Desde aquí podrás explorar los maravillosos paisajes del altiplano, descubrir pueblos llenos de historia y conectar con la riqueza natural y cultural de nuestra tierra.') }}</p>
                    <p>{{ __('Ven a Putre, respira la tranquilidad de los Andes y déjate sorprender por la magia del altiplano. ¡Será un placer recibirte!') }}</p>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ lroute('cabanas.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                        {{ __('Ver cabañas') }}
                    </a>
                    <a href="{{ lroute('habitaciones.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-winay-tierra border border-winay-tierra hover:bg-winay-arena transition">
                        {{ __('Ver habitaciones') }}
                    </a>
                    <a href="{{ lroute('cultura') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-winay-tierra border border-winay-tierra hover:bg-winay-arena transition">
                        {{ __('Cultura Aymara') }}
                    </a>
                </div>
            </div>
            <img src="{{ asset('images/inicio/bienvenida-1600.webp') }}"
                 srcset="{{ asset('images/inicio/bienvenida-800.webp') }} 800w, {{ asset('images/inicio/bienvenida-1600.webp') }} 1600w"
                 sizes="(min-width: 1024px) 560px, 100vw"
                 width="1600" height="1067"
                 alt="{{ __('Cabañas Wiñaypacha Putre') }}"
                 loading="lazy" decoding="async"
                 class="w-full aspect-3/2 rounded-2xl object-cover shadow-sm">
        </div>
    </section>

    @if ($temaDestacado)
        <section class="bg-white border-t border-winay-arena">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid gap-8 lg:grid-cols-2 items-center">
                <x-galeria-lightbox :imagenes="$temaDestacado->imagenes" :titulo="$temaDestacado->titulo" />
                <div>
                    <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ __('Cultura Aymara') }}</p>
                    <h2 class="text-2xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ $temaDestacado->titulo }}</h2>
                    <p class="mt-4 text-stone-600">{{ Str::limit(strip_tags($temaDestacado->cuerpo), 280) }}</p>
                    <a href="{{ lroute('cultura') }}" class="mt-4 inline-block text-sm font-semibold text-winay-terracota hover:underline">
                        {{ __('Conocer más sobre la cultura aymara →') }}
                    </a>
                </div>
            </div>
        </section>
    @endif
{{-- BOTONES --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ lroute('putre') }}" class="p-6 rounded-2xl border border-winay-arena hover:border-winay-terracota transition">
            <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">Putre</h3>
            <p class="mt-2 text-sm text-stone-600">{{ __('Qué hacer y cómo vivir Putre como un local.') }}</p>
        </a>
        <a href="{{ lroute('entorno') }}" class="p-6 rounded-2xl border border-winay-arena hover:border-winay-terracota transition">
            <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ __('Qué visitar') }}</h3>
            <p class="mt-2 text-sm text-stone-600">{{ __('Lugares del entorno de Putre y el altiplano.') }}</p>
        </a>
        <a href="{{ lroute('nosotros') }}" class="p-6 rounded-2xl border border-winay-arena hover:border-winay-terracota transition">
            <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ __('Nosotros') }}</h3>
            <p class="mt-2 text-sm text-stone-600">{{ __('La historia detrás de WiñayPacha Putre.') }}</p>
        </a>
        <a href="{{ lroute('cultura') }}#calendario" class="p-6 rounded-2xl border border-winay-arena hover:border-winay-terracota transition">
            <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ __('Calendario de fiestas') }}</h3>
            <p class="mt-2 text-sm text-stone-600">{{ __('Fiestas y actividades tradicionales del año aymara.') }}</p>
        </a>
    </section>
</x-winay-layout>
