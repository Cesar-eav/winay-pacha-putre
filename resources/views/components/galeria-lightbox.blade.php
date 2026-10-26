@props([
    'imagenes',
    'titulo' => '',
])

@if ($imagenes->isEmpty())
    <x-imagen-placeholder :label="$titulo ?: 'Imagen'" class="aspect-video rounded-2xl" />
@else
    @php
        $total = $imagenes->count();
        $mediums = $imagenes->map(fn ($i) => $i['medium_url'] ?? $i['url'])->values();
        $alts = $imagenes->pluck('alt')->values();
    @endphp

    <div
        x-data="{
            images: {{ Illuminate\Support\Js::from($imagenes->pluck('url')) }},
            mediums: {{ Illuminate\Support\Js::from($mediums) }},
            alts: {{ Illuminate\Support\Js::from($alts) }},
            current: 0,
            lightbox: false,
            touchX: null,
            {{-- Solo se cargan la actual y sus vecinas, para no bajar todas las fotos de cada tarjeta --}}
            cargadas: [0, 1, {{ $total - 1 }}],
            ir(i) {
                this.current = (i + this.images.length) % this.images.length;
                [this.current - 1, this.current + 1].forEach(n => {
                    n = (n + this.images.length) % this.images.length;
                    if (! this.cargadas.includes(n)) this.cargadas.push(n);
                });
            },
            anterior() { this.ir(this.current - 1) },
            siguiente() { this.ir(this.current + 1) },
            swipe(e) {
                if (this.touchX === null) return;
                const dx = e.changedTouches[0].clientX - this.touchX;
                this.touchX = null;
                if (Math.abs(dx) > 40) dx < 0 ? this.siguiente() : this.anterior();
            },
        }"
        class="relative h-full"
    >
        <div
            class="relative h-full aspect-video rounded-2xl overflow-hidden bg-winay-arena"
            @touchstart.passive="touchX = $event.changedTouches[0].clientX"
            @touchend="swipe($event)"
        >
            <div class="flex h-full transition-transform duration-500 ease-out motion-reduce:transition-none" :style="`transform: translateX(-${current * 100}%)`">
                @foreach ($mediums as $i => $medium)
                    <button
                        type="button"
                        @click.prevent.stop="lightbox = true"
                        class="w-full h-full shrink-0 focus-visible:outline-3 focus-visible:-outline-offset-3 focus-visible:outline-white"
                        @if ($i > 0) aria-hidden="true" :aria-hidden="current !== {{ $i }}" tabindex="-1" :tabindex="current === {{ $i }} ? 0 : -1" @endif
                    >
                        @if ($i === 0)
                            <img src="{{ $medium }}" alt="{{ $alts[$i] }}" decoding="async" class="w-full h-full object-cover">
                        @else
                            <img :src="cargadas.includes({{ $i }}) ? mediums[{{ $i }}] : null" alt="{{ $alts[$i] }}" decoding="async" class="w-full h-full object-cover">
                        @endif
                    </button>
                @endforeach
            </div>

            @if ($total > 1)
                <button
                    type="button"
                    @click.prevent.stop="anterior()"
                    class="absolute left-2 top-1/2 -translate-y-1/2 flex items-center justify-center size-9 rounded-full bg-white/85 text-winay-tierra shadow-[0_2px_8px_rgb(0_0_0/0.18)] transition hover:bg-white hover:text-winay-terracota focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    aria-label="{{ __('Anterior') }}"
                >
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15 5l-7 7 7 7" />
                    </svg>
                </button>
                <button
                    type="button"
                    @click.prevent.stop="siguiente()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center justify-center size-9 rounded-full bg-white/85 text-winay-tierra shadow-[0_2px_8px_rgb(0_0_0/0.18)] transition hover:bg-white hover:text-winay-terracota focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    aria-label="{{ __('Siguiente') }}"
                >
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="absolute bottom-3 inset-x-0 flex justify-center gap-1.5">
                    @foreach ($mediums as $i => $medium)
                        <button
                            type="button"
                            @click.prevent.stop="ir({{ $i }})"
                            class="h-1.5 rounded-full shadow-[0_1px_3px_rgb(0_0_0/0.3)] transition-all"
                            :class="current === {{ $i }} ? 'w-5 bg-white' : 'w-1.5 bg-white/60 hover:bg-white/90'"
                            aria-label="{{ __('Ir a la imagen') }} {{ $i + 1 }}"
                        ></button>
                    @endforeach
                </div>
            @endif
        </div>

        <div
            x-show="lightbox"
            x-cloak
            x-transition
            @click.self.prevent.stop="lightbox = false"
            @keydown.escape.window="lightbox = false"
            @keydown.arrow-left.window="lightbox && anterior()"
            @keydown.arrow-right.window="lightbox && siguiente()"
            @touchstart.passive="touchX = $event.changedTouches[0].clientX"
            @touchend="swipe($event)"
            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
        >
            <button type="button" @click.prevent.stop="lightbox = false" class="absolute top-4 right-4 text-white/80 hover:text-white text-2xl leading-none" aria-label="{{ __('Cerrar') }}">
                &times;
            </button>

            @if ($total > 1)
                <button type="button" @click.prevent.stop="anterior()"
                        class="absolute left-4 text-white/80 hover:text-white text-3xl" aria-label="{{ __('Anterior') }}">
                    &#8249;
                </button>
                <button type="button" @click.prevent.stop="siguiente()"
                        class="absolute right-4 text-white/80 hover:text-white text-3xl" aria-label="{{ __('Siguiente') }}">
                    &#8250;
                </button>
            @endif

            <img :src="lightbox ? images[current] : ''" :alt="alts[current]" class="max-w-full max-h-full object-contain rounded-lg">

            @if ($total > 1)
                <div class="absolute bottom-4 text-white/70 text-sm" x-text="(current + 1) + ' / ' + images.length"></div>
            @endif
        </div>
    </div>
@endif
