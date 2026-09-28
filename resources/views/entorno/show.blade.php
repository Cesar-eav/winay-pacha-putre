<x-winay-layout>
    <x-slot:titulo>{{ $lugar->nombre }}</x-slot:titulo>

    @php
        $portada = $lugar->imagenes->first();
    @endphp

    {{-- Hero full-bleed --}}
    <section class="relative w-full h-[60vh] min-h-96 max-h-160 overflow-hidden">
        @if ($portada)
            <img
                src="{{ $portada->url }}"
                alt="{{ $portada->alt }}"
                class="absolute inset-0 w-full h-full object-cover"
            >
        @else
            <x-imagen-placeholder :label="$lugar->nombre" class="absolute inset-0" />
        @endif
        <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"></div>

        <a href="{{ lroute('entorno') }}" class="absolute top-6 left-4 sm:left-6 lg:left-8 inline-flex items-center gap-1.5 text-sm text-white/90 hover:text-white bg-black/30 hover:bg-black/50 backdrop-blur px-3 py-1.5 rounded-full transition">
            &larr; {{ __('Qué Visitar') }}
        </a>

        <div class="absolute inset-0 flex items-end">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 w-full">
                @if ($lugar->ubicacion_texto)
                    <p class="text-sm uppercase tracking-widest text-winay-arena/90 font-semibold mb-3 drop-shadow">
                        {{ $lugar->ubicacion_texto }}
                    </p>
                @endif
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white drop-shadow-lg max-w-3xl">
                    {{ $lugar->nombre }}
                </h1>
            </div>
        </div>
    </section>

    {{-- Descripción --}}
    <section class="bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            <div class="text-lg text-stone-600 leading-relaxed space-y-4">{!! $lugar->descripcion !!}</div>
        </div>
    </section>

    {{-- Galería + Cómo llegar --}}
    @if ($lugar->imagenes->isNotEmpty() || $lugar->ubicacion_texto)
        <section class="bg-winay-arena/40 border-t border-winay-arena">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid gap-10 items-start @if ($lugar->imagenes->isNotEmpty() && $lugar->ubicacion_texto) lg:grid-cols-5 @endif">
                @if ($lugar->imagenes->isNotEmpty())
                    <div @class(['lg:col-span-3' => $lugar->ubicacion_texto])>
                        <x-galeria-lightbox :imagenes="$lugar->imagenes" :titulo="$lugar->nombre" />
                    </div>
                @endif

                @if ($lugar->ubicacion_texto)
                    <div @class(['lg:col-span-2' => $lugar->imagenes->isNotEmpty(), 'max-w-xl mx-auto text-center' => $lugar->imagenes->isEmpty(), 'p-6 rounded-2xl bg-white border border-winay-arena' => true])>
                        <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ __('Cómo llegar') }}</p>
                        <p class="text-stone-600 leading-relaxed">{{ $lugar->ubicacion_texto }}</p>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- CTA de cierre --}}
    <section class="bg-white border-t border-winay-arena">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h2 class="text-2xl lg:text-3xl font-bold text-winay-tierra">{{ __('¿Listo para conocerlo en persona?') }}</h2>
            <p class="mt-3 text-stone-600 max-w-xl mx-auto">{{ __('Nuestras cabañas son la base ideal para explorar este rincón del altiplano de Putre.') }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ lroute('cabanas.index') }}" class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                    {{ __('Ver cabañas') }}
                </a>
                <a href="{{ lroute('reserva') }}" class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold text-winay-tierra border border-winay-tierra hover:bg-winay-arena transition">
                    {{ __('Solicitar reserva') }}
                </a>
            </div>
        </div>
    </section>
</x-winay-layout>
