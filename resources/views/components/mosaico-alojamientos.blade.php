@props(['items', 'etiqueta', 'titulo', 'verTodosUrl', 'verTodosTexto', 'encabezado' => 'h2'])

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ $etiqueta }}</p>
    <{{ $encabezado }} class="text-2xl sm:text-3xl font-display font-medium tracking-[-0.01em] text-winay-tierra">{{ $titulo }}</{{ $encabezado }}>

    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-0">
        @foreach ($items as $item)
            <a href="{{ $item->url }}"
               class="group relative block aspect-2/1 overflow-hidden border border-winay-arena hover:border-winay-terracota hover:shadow-xl transition">
                <img src="{{ $item->portada_url ?? asset('images/placeholder/cabana-ejemplo.jpg') }}"
                     alt="{{ $item->portada_alt }}"
                     loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 ease-out">

                <span class="absolute inset-0 bg-linear-to-t from-black/80 via-black/10 to-transparent group-hover:from-black/90 group-hover:via-black/40 transition duration-300"></span>

                <span class="absolute inset-0 flex items-center justify-center">
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-white bg-winay-terracota px-5 py-2.5 rounded-full shadow-lg opacity-0 scale-75 group-hover:opacity-100 group-hover:scale-100 transition duration-300 ease-out">
                        {{ __('Leer más') }}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3.5">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>

                <span class="absolute inset-0 flex items-center justify-center p-4 text-center group-hover:opacity-0 transition duration-300">
                    <span class="font-display text-4xl font-medium tracking-[-0.01em] text-white drop-shadow">{{ $item->nombre }}</span>
                </span>
            </a>
        @endforeach

        <a href="{{ $verTodosUrl }}"
           class="group relative block aspect-2/1 overflow-hidden border border-winay-arena hover:border-winay-terracota transition">
            <x-imagen-placeholder label="" color="andino" class="w-full h-full" />
            <span class="absolute inset-0 flex items-center justify-center bg-linear-to-t from-black/60 to-transparent p-4">
                <span class="text-white font-semibold">{{ $verTodosTexto }}</span>
            </span>
        </a>
    </div>
</section>
