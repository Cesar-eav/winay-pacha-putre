@props(['items', 'etiqueta', 'titulo', 'texto', 'url', 'enlaceTexto'])

<section class="bg-winay-arena">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid gap-10 lg:grid-cols-[2fr_3fr] items-center">
        <div>
            <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ $etiqueta }}</p>
            <h2 class="text-2xl sm:text-3xl font-display font-medium tracking-[-0.01em] text-winay-tierra text-balance">{{ $titulo }}</h2>
            <p class="mt-4 text-stone-600">{{ $texto }}</p>
            <a href="{{ $url }}" class="mt-6 inline-block text-sm font-semibold text-winay-terracota hover:underline">{{ $enlaceTexto }} →</a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            @foreach ($items as $cabana)
                <a href="{{ $cabana->url }}"
                   class="group block overflow-hidden rounded-2xl bg-white border border-transparent hover:border-winay-terracota hover:shadow-lg transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-winay-terracota">
                    <span class="block aspect-4/5 overflow-hidden">
                        <img src="{{ $cabana->portada_url ?? asset('images/placeholder/cabana-ejemplo.jpg') }}"
                             alt="{{ $cabana->portada_alt }}"
                             loading="lazy" decoding="async"
                             class="size-full object-cover transition duration-700 ease-out group-hover:scale-105">
                    </span>
                    <span class="block p-5">
                        @if ($cabana->numero)
                            <span class="block text-xs uppercase tracking-wide text-winay-terracota font-semibold">{{ __('Habitación N° :n', ['n' => $cabana->numero]) }}</span>
                        @endif
                        <span class="mt-2 block nombre-alojamiento text-3xl text-winay-tierra">{{ $cabana->nombre }}</span>
                        @include('cabanas.partials.detalles', ['clase' => 'mt-3 text-sm text-stone-500'])
                        @if ($cabana->precio_texto)
                            <span class="mt-1 block text-sm font-semibold text-winay-terracota">{{ $cabana->precio_texto }}</span>
                        @endif
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
