<x-winay-layout>
    <x-slot:titulo>{{ __('Inicio') }}</x-slot:titulo>

    {{-- SLIDER BANNER ANCHO COMPLETO --}}
    @if ($carrusel->isNotEmpty())
    @php
        $varias = $carrusel->count() > 1;
    @endphp
    <section
        x-data="{
            slide: 0,
            total: {{ $carrusel->count() }},
            cargadas: 0,
            ir(i) {
                this.slide = (i + this.total) % this.total
                this.cargadas = Math.max(this.cargadas, this.slide + 1)
            },
            init() {
                const precargar = () => this.cargadas = Math.max(this.cargadas, 1)
                document.readyState === 'complete' ? precargar() : window.addEventListener('load', precargar, { once: true })
                if (this.total > 1 && ! matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    setInterval(() => this.ir(this.slide + 1), 5000)
                }
            },
        }"
        class="relative w-full h-[clamp(24rem,max(70vh,38vw),85vh)] overflow-hidden bg-winay-andino"
    >
        @foreach ($carrusel as $i => $item)
            @php
                $srcset = "{$item->thumb_url} 480w, {$item->medium_url} 1200w, {$item->url} 2000w";
                $frase = $item->frase;
            @endphp
            {{-- sizes: con object-cover en un banner alto (móvil), una foto 16:9 se dibuja más ancha que la pantalla (~70vh × 16/9). --}}
            <div
                x-bind:aria-hidden="slide !== {{ $i }}"
                class="absolute inset-0 transition-opacity duration-700 motion-reduce:transition-none {{ $i ? 'opacity-0' : '' }}"
                :class="{ 'opacity-0': slide !== {{ $i }} }"
            >
                <img
                    @if ($i === 0)
                        src="{{ $item->medium_url }}"
                        srcset="{{ $srcset }}"
                        fetchpriority="high"
                    @else
                        x-bind:srcset="cargadas >= {{ $i }} ? '{{ $srcset }}' : null"
                        decoding="async"
                    @endif
                    sizes="max(100vw, 125vh)"
                    alt="{{ $frase ?: 'Wiñaypacha Putre' }}"
                    class="absolute inset-0 size-full object-cover {{ $item->clase_encuadre }}"
                >
                @if ($frase)
                    <div class="absolute inset-x-0 bottom-0 h-1/2 bg-linear-to-t from-black/60 to-transparent"></div>
                    <p class="absolute left-4 right-4 sm:left-8 bottom-12 sm:bottom-14 max-w-2xl text-2xl sm:text-4xl font-display font-medium tracking-[-0.01em] text-white text-balance drop-shadow-sm" aria-hidden="true">
                        {{ $frase }}
                    </p>
                @endif
            </div>
        @endforeach

        @if ($varias)
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
                @foreach ($carrusel as $i => $item)
                    <button
                        type="button"
                        @click="ir({{ $i }})"
                        class="size-2.5 rounded-full transition"
                        :class="slide === {{ $i }} ? 'bg-winay-terracota' : 'bg-white/60 hover:bg-white/80'"
                        aria-label="{{ __('Ir a la imagen') }} {{ $i + 1 }}"
                    ></button>
                @endforeach
            </div>
        @endif
    </section>
    @endif

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
                <h2 class="text-3xl sm:text-4xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ __('¡Bienvenidos a Wiñaypacha!') }}</h2>
                <p class="mt-2 text-lg text-stone-600">{{ __('Tu refugio acogedor en el corazón del altiplano.') }}</p>
                <div class="mt-4 space-y-3 text-stone-600">
                    <p>{{ __('Te invitamos a descubrir Putre, un lugar donde la naturaleza, la cultura aymara y los paisajes de la cordillera se unen para regalarte una experiencia inolvidable.') }}</p>
                    <p>{{ __('En Wiñaypacha Cabañas y Turismo te recibimos con calidez y hospitalidad, ofreciéndote un lugar cómodo y acogedor para descansar, disfrutar de tu estadía y sentirte como en casa. Nuestras cabañas están equipadas para que vivas una experiencia independiente, ya sea que viajes en pareja, en familia o en busca de nuevas aventuras.') }}</p>
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

    @if ($fotosHuespedes->isNotEmpty())
    <section class="bg-winay-arena/40 border-t border-winay-arena">
        <div
            x-data="{
                pasar(dir) {
                    const pista = this.$refs.pista
                    pista.scrollBy({ left: dir * pista.firstElementChild.offsetWidth * 1.05, behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })
                },
            }"
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16"
        >
            <div class="flex items-end justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ __('Nuestros huéspedes') }}</p>
                    <h2 class="text-3xl sm:text-4xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ __('Quienes ya vivieron Wiñaypacha') }}</h2>
                </div>
                <div class="hidden sm:flex gap-2 shrink-0">
                    <button type="button" @click="pasar(-1)" class="size-10 flex items-center justify-center rounded-full border border-winay-tierra text-winay-tierra hover:bg-winay-tierra hover:text-white transition" aria-label="{{ __('Anterior') }}">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7" /></svg>
                    </button>
                    <button type="button" @click="pasar(1)" class="size-10 flex items-center justify-center rounded-full border border-winay-tierra text-winay-tierra hover:bg-winay-tierra hover:text-white transition" aria-label="{{ __('Siguiente') }}">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>

            <ul x-ref="pista" class="flex gap-4 overflow-x-auto snap-x snap-mandatory -mx-4 px-4 sm:mx-0 sm:px-0 scroll-px-4 sm:scroll-px-0 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @foreach ($fotosHuespedes as $foto)
                    <li class="snap-start shrink-0 w-[72%] sm:w-[calc((100%-2rem)/3)] lg:w-[calc((100%-3rem)/4)]">
                        <img src="{{ $foto->thumb_url }}"
                             srcset="{{ $foto->thumb_url }} 480w, {{ $foto->medium_url }} 1200w"
                             sizes="(min-width: 1024px) 260px, (min-width: 640px) 31vw, 72vw"
                             width="480" height="640"
                             alt="{{ __($foto->alt ?: 'Huéspedes en Wiñaypacha Putre') }}"
                             loading="lazy" decoding="async"
                             class="w-full aspect-3/4 rounded-2xl object-cover bg-winay-arena shadow-sm">
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    @endif

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
            <p class="mt-2 text-sm text-stone-600">{{ __('La historia detrás de Wiñaypacha Putre.') }}</p>
        </a>
        <a href="{{ lroute('cultura') }}#calendario" class="p-6 rounded-2xl border border-winay-arena hover:border-winay-terracota transition">
            <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ __('Calendario de fiestas') }}</h3>
            <p class="mt-2 text-sm text-stone-600">{{ __('Fiestas y actividades tradicionales del año aymara.') }}</p>
        </a>
    </section>
</x-winay-layout>
