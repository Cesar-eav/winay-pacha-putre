@props(['items', 'etiqueta', 'titulo', 'url', 'enlaceTexto', 'encabezado' => 'h2'])

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ $etiqueta }}</p>
            <{{ $encabezado }} class="text-2xl sm:text-3xl font-display font-medium tracking-[-0.01em] text-winay-tierra text-balance">{{ $titulo }}</{{ $encabezado }}>
        </div>
        <a href="{{ $url }}" class="shrink-0 text-sm font-semibold text-winay-terracota hover:underline">{{ $enlaceTexto }} →</a>
    </div>

    <div class="mt-8 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3">
        @foreach ($items as $cabana)
            <a href="{{ $cabana->url }}"
               class="group relative block aspect-4/5 overflow-hidden rounded-2xl bg-winay-tierra focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-winay-terracota">
                <img src="{{ $cabana->portada_url ?? asset('images/placeholder/cabana-ejemplo.jpg') }}"
                     alt="{{ $cabana->portada_alt }}"
                     loading="lazy" decoding="async"
                     class="absolute inset-0 size-full object-cover transition duration-700 ease-out group-hover:scale-105 group-focus-visible:scale-105">

                <span class="absolute inset-0 bg-linear-to-t from-black/85 via-black/35 to-transparent sm:from-black/75 sm:via-black/15"></span>

                <span class="absolute top-4 right-4 flex size-10 items-center justify-center rounded-full bg-white/90 text-winay-tierra opacity-0 -translate-y-1 transition duration-300 group-hover:opacity-100 group-hover:translate-y-0 group-focus-visible:opacity-100 group-focus-visible:translate-y-0" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" /></svg>
                </span>

                <span class="absolute inset-x-0 bottom-0 p-3 sm:p-6 text-white">
                    @if ($cabana->numero)
                        <span class="flex items-center gap-2 text-[0.65rem] font-semibold uppercase tracking-[0.15em] text-white/80 sm:text-xs sm:tracking-[0.2em]">
                            <span class="h-px w-4 sm:w-6 bg-winay-terracota"></span>
                            N° {{ str_pad($cabana->numero, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    @endif
                    <span class="mt-1 sm:mt-2 block nombre-alojamiento text-2xl sm:text-4xl lg:text-5xl">{{ $cabana->nombre }}</span>

                    @if ($extracto = Str::limit(Str::squish(strip_tags(preg_replace('#<(br|/p|/li|/div|/h\d)#i', ' <$1', $cabana->descripcion))), 70))
                        <span class="mt-1.5 line-clamp-3 text-xs leading-snug text-white/85 sm:hidden">{{ $extracto }}</span>
                    @endif

                    <span class="hidden sm:grid transition-all duration-300 ease-out sm:grid-rows-[0fr] sm:opacity-0 sm:group-hover:grid-rows-[1fr] sm:group-hover:opacity-100 sm:group-focus-visible:grid-rows-[1fr] sm:group-focus-visible:opacity-100">
                        <span class="overflow-hidden">
                            @include('cabanas.partials.detalles', ['clase' => 'pt-3 text-sm text-white/85'])
                            @if ($cabana->precio_texto)
                                <span class="mt-1 block text-sm font-semibold text-white">{{ $cabana->precio_texto }}</span>
                            @endif
                        </span>
                    </span>
                </span>
            </a>
        @endforeach
    </div>
</section>
