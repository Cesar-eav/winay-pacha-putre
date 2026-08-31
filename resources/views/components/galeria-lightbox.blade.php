@props([
    'imagenes',
    'titulo' => '',
])

@if ($imagenes->isEmpty())
    <x-imagen-placeholder :label="$titulo ?: 'Imagen'" class="aspect-video rounded-2xl" />
@else
    <div
        x-data="{
            images: {{ Illuminate\Support\Js::from($imagenes->pluck('url')) }},
            alts: {{ Illuminate\Support\Js::from($imagenes->pluck('alt')) }},
            current: 0,
            lightbox: false,
        }"
    >
        <button type="button" @click="lightbox = true" class="block w-full aspect-video rounded-2xl overflow-hidden">
            <img :src="images[current]" :alt="alts[current]" class="w-full h-full object-cover">
        </button>

        @if ($imagenes->count() > 1)
            <div class="mt-3 flex gap-2 overflow-x-auto">
                <template x-for="(image, i) in images" :key="i">
                    <button
                        type="button"
                        @click="current = i"
                        class="shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2"
                        :class="current === i ? 'border-winay-terracota' : 'border-transparent'"
                    >
                        <img :src="image" :alt="alts[i]" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        @endif

        <div
            x-show="lightbox"
            x-cloak
            x-transition
            @click.self="lightbox = false"
            @keydown.escape.window="lightbox = false"
            @keydown.arrow-left.window="current = current === 0 ? images.length - 1 : current - 1"
            @keydown.arrow-right.window="current = current === images.length - 1 ? 0 : current + 1"
            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
        >
            <button type="button" @click="lightbox = false" class="absolute top-4 right-4 text-white/80 hover:text-white text-2xl leading-none" aria-label="Cerrar">
                &times;
            </button>

            @if ($imagenes->count() > 1)
                <button type="button" @click.stop="current = current === 0 ? images.length - 1 : current - 1"
                        class="absolute left-4 text-white/80 hover:text-white text-3xl" aria-label="Anterior">
                    &#8249;
                </button>
                <button type="button" @click.stop="current = current === images.length - 1 ? 0 : current + 1"
                        class="absolute right-4 text-white/80 hover:text-white text-3xl" aria-label="Siguiente">
                    &#8250;
                </button>
            @endif

            <img :src="images[current]" :alt="alts[current]" class="max-w-full max-h-full object-contain rounded-lg">

            @if ($imagenes->count() > 1)
                <div class="absolute bottom-4 text-white/70 text-sm" x-text="(current + 1) + ' / ' + images.length"></div>
            @endif
        </div>
    </div>
@endif
