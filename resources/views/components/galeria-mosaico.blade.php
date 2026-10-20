@props([
    'imagenes',
    'titulo' => '',
])

@php
    $imagenes = $imagenes->values();
    $total = $imagenes->count();
    $mosaico = $imagenes->take(5);
    $cantidadMosaico = $mosaico->count();

    $grilla = match (true) {
        $cantidadMosaico === 1 => 'grid-cols-1',
        $cantidadMosaico === 2 => 'grid-cols-2',
        default => 'grid-cols-4 grid-rows-2',
    };

    $celda = fn (int $i) => match (true) {
        $cantidadMosaico < 3 => '',
        $i === 0 => 'col-span-2 row-span-2',
        $cantidadMosaico === 3, $cantidadMosaico === 4 && $i === 1 => 'col-span-2',
        default => '',
    };

    $alt = fn ($imagen) => $imagen->alt ?: $titulo;
@endphp

@if ($imagenes->isEmpty())
    <x-imagen-placeholder :label="$titulo ?: 'Imagen'" class="aspect-[4/3] md:aspect-[21/9] rounded-2xl" />
@else
    <div
        x-data="{
            images: {{ Illuminate\Support\Js::from($imagenes->map(fn ($i) => $i->url)) }},
            alts: {{ Illuminate\Support\Js::from($imagenes->map($alt)) }},
            current: 0,
            visible: 0,
            lightbox: false,
            touchX: null,
            abrir(i) { this.current = i; this.lightbox = true },
            anterior() { this.current = this.current === 0 ? this.images.length - 1 : this.current - 1 },
            siguiente() { this.current = this.current === this.images.length - 1 ? 0 : this.current + 1 },
            swipe(e) {
                const dx = e.changedTouches[0].clientX - this.touchX;
                if (Math.abs(dx) > 50) dx < 0 ? this.siguiente() : this.anterior();
            },
        }"
        class="relative"
    >
        <div
            x-ref="carrusel"
            @scroll.debounce.50ms="visible = Math.round($el.scrollLeft / $el.clientWidth)"
            class="md:hidden flex overflow-x-auto snap-x snap-mandatory rounded-2xl [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
        >
            @foreach ($imagenes as $i => $imagen)
                <button type="button" @click="abrir({{ $i }})" class="w-full shrink-0 snap-center aspect-[4/3] focus-visible:outline-3 focus-visible:-outline-offset-3 focus-visible:outline-white">
                    <img src="{{ $imagen->medium_url }}" alt="{{ $alt($imagen) }}" @if ($i > 0) loading="lazy" @endif decoding="async" class="w-full h-full object-cover">
                </button>
            @endforeach
        </div>

        @if ($total > 1)
            <div class="md:hidden pointer-events-none absolute bottom-3 right-3 rounded-full bg-black/60 px-2.5 py-1 text-xs font-medium tabular-nums text-white">
                <span x-text="visible + 1">1</span> / {{ $total }}
            </div>
        @endif

        <div class="hidden md:grid {{ $grilla }} gap-2 aspect-[2/1] lg:aspect-[21/9] rounded-2xl overflow-hidden">
            @foreach ($mosaico as $i => $imagen)
                <button type="button" @click="abrir({{ $i }})" class="group relative overflow-hidden bg-winay-arena {{ $celda($i) }} focus-visible:outline-3 focus-visible:-outline-offset-3 focus-visible:outline-white">
                    <img
                        src="{{ $imagen->medium_url }}"
                        alt="{{ $alt($imagen) }}"
                        @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif
                        decoding="async"
                        class="absolute inset-0 w-full h-full object-cover transition duration-300 ease-out group-hover:brightness-90"
                    >
                </button>
            @endforeach
        </div>

        @if ($total > 1)
            <button
                type="button"
                @click="abrir(0)"
                class="hidden md:inline-flex absolute bottom-4 right-4 items-center gap-2 rounded-full bg-white/95 px-4 py-2 text-sm font-semibold text-winay-tierra shadow-[0_2px_10px_rgb(0_0_0/0.18)] transition hover:bg-white hover:text-winay-terracota focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-winay-terracota"
            >
                <svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="1.75" y="1.75" width="5" height="5" rx="1" />
                    <rect x="9.25" y="1.75" width="5" height="5" rx="1" />
                    <rect x="1.75" y="9.25" width="5" height="5" rx="1" />
                    <rect x="9.25" y="9.25" width="5" height="5" rx="1" />
                </svg>
                {{ __('Ver las :n fotos', ['n' => $total]) }}
            </button>
        @endif

        <div
            x-show="lightbox"
            x-cloak
            x-transition.opacity
            x-trap.noscroll.inert="lightbox"
            role="dialog"
            aria-modal="true"
            aria-label="{{ $titulo }}"
            @click.self="lightbox = false"
            @keydown.escape.window="lightbox = false"
            @keydown.arrow-left.window="lightbox && anterior()"
            @keydown.arrow-right.window="lightbox && siguiente()"
            @touchstart.passive="touchX = $event.changedTouches[0].clientX"
            @touchend="swipe($event)"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 sm:p-12"
        >
            <img :src="lightbox ? images[current] : ''" :alt="alts[current]" class="max-w-full max-h-full object-contain rounded-lg select-none">

            <button type="button" @click="lightbox = false" class="absolute top-4 right-4 rounded-full p-2 text-white/80 transition hover:bg-white/10 hover:text-white focus-visible:outline-2 focus-visible:outline-white" aria-label="{{ __('Cerrar') }}">
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            @if ($total > 1)
                <button type="button" @click="anterior()" class="absolute left-2 sm:left-4 rounded-full p-2 text-white/80 transition hover:bg-white/10 hover:text-white focus-visible:outline-2 focus-visible:outline-white" aria-label="{{ __('Anterior') }}">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15 5l-7 7 7 7" />
                    </svg>
                </button>
                <button type="button" @click="siguiente()" class="absolute right-2 sm:right-4 rounded-full p-2 text-white/80 transition hover:bg-white/10 hover:text-white focus-visible:outline-2 focus-visible:outline-white" aria-label="{{ __('Siguiente') }}">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="absolute bottom-4 text-sm tabular-nums text-white/70" x-text="(current + 1) + ' / ' + images.length"></div>
            @endif
        </div>
    </div>
@endif
