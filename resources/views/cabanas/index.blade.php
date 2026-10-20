<x-winay-layout>
    <x-slot:titulo>{{ __('Cabañas') }}</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ __('Cabañas') }}</h1>
        <p class="mt-2 text-stone-600 max-w-2xl">
            {{ __('Precios y disponibilidad son informativos — la reserva se confirma manualmente con el anfitrión.') }}
        </p>

        <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($cabanas as $cabana)
                <a href="{{ lroute('cabanas.show', $cabana) }}" class="block rounded-2xl border border-winay-arena overflow-hidden hover:border-winay-terracota transition">
                    @if ($cabana->portada_url)
                        <img src="{{ $cabana->portada_url }}" alt="{{ $cabana->portada_alt }}" loading="lazy" decoding="async" class="w-full aspect-video object-cover">
                    @else
                        <x-imagen-placeholder :label="$cabana->nombre" class="aspect-video" />
                    @endif
                    <div class="p-5">
                        @if ($cabana->numero)
                            <p class="text-xs uppercase tracking-wide text-winay-terracota font-semibold">{{ __('Cabaña N° :n', ['n' => $cabana->numero]) }}</p>
                        @endif
                        <h2 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ $cabana->nombre }}</h2>
                        @include('cabanas.partials.detalles', ['clase' => 'mt-1 text-sm text-stone-500'])
                        <p class="mt-2 text-sm text-stone-600">{{ Str::limit(strip_tags($cabana->descripcion), 120) }}</p>
                        @if ($cabana->precio_texto)
                            <p class="mt-3 text-sm font-semibold text-winay-terracota">{{ $cabana->precio_texto }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-stone-500">{{ __('Aún no hay cabañas publicadas.') }}</p>
            @endforelse
        </div>
    </section>
</x-winay-layout>
