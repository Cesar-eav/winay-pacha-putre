<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($titulo) ? $titulo.' — Wiñaypacha Putre' : 'Wiñaypacha Putre' }}</title>

    @php
        $rutaActual = request()->route();
        $nombreBaseHreflang = preg_replace('/^(en|fr)\./', '', $rutaActual->getName());
        $paramsHreflang = $rutaActual->parameters();
    @endphp
    @foreach (config('winay.locales') as $codigoHreflang => $labelHreflang)
        <link rel="alternate" hreflang="{{ $codigoHreflang }}" href="{{ lroute($nombreBaseHreflang, $paramsHreflang, $codigoHreflang) }}">
    @endforeach

    @livewireStyles
    @livewireScriptConfig
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-texto text-stone-800 antialiased">

    <header x-data="{ open: false }" class="bg-white border-b border-winay-arena sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-4">
            <div class="flex justify-between items-center h-20">
                <a href="{{ lroute('inicio') }}" class="flex items-center">
                    <img src="{{ asset('images/logo_12500x3655.png') }}" alt="Wiñaypacha Putre" class="h-10 sm:h-12 w-auto">
                </a>

                <nav class="hidden lg:flex items-center gap-6">
                    @php
                        $nombreRutaActual = preg_replace('/^(en|fr)\./', '', request()->route()->getName());
                        $navLinks = [
                            'inicio' => [__('Inicio'), lroute('inicio')],
                            'cultura' => [__('Cultura Aymara'), lroute('cultura')],
                            'putre' => [__('Putre'), lroute('putre')],
                            'cabanas.index' => [__('Cabañas'), lroute('cabanas.index')],
                            'habitaciones.index' => [__('Habitaciones'), lroute('habitaciones.index')],
                            'entorno' => [__('Qué Visitar'), lroute('entorno')],
                            'nosotros' => [__('Nosotros'), lroute('nosotros')],
                            'contacto' => [__('Contacto'), lroute('contacto')],
                        ];
                    @endphp

                    @foreach ($navLinks as $routeName => [$label, $url])
                        <a href="{{ $url }}"
                           class="text-sm xl:text-sm font-medium {{ str_starts_with($nombreRutaActual, $routeName) ? 'text-winay-terracota' : 'text-stone-600 hover:text-winay-terracota' }}">
                            {{ $label }}
                        </a>
                    @endforeach

                    <a href="{{ lroute('reserva') }}"
                       class="inline-flex items-center px-4 py-2 rounded-full text-sm xl:text-base font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                        {{ __('Reservar') }}
                    </a>

                    <x-language-switcher />
                </nav>

                <button @click="open = ! open" class="lg:hidden p-2 text-stone-600" aria-label="{{ __('Abrir menú') }}">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': ! open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-cloak class="lg:hidden border-t border-winay-arena">
            <div class="px-4 py-3 space-y-1">
                @foreach ($navLinks as $routeName => [$label, $url])
                    <a href="{{ $url }}"
                       class="block px-2 py-2 rounded-md text-base font-medium {{ str_starts_with($nombreRutaActual, $routeName) ? 'text-winay-terracota bg-winay-arena' : 'text-stone-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ lroute('reserva') }}"
                   class="block mt-2 px-2 py-2 rounded-md text-base font-semibold text-white bg-winay-terracota text-center">
                    {{ __('Reservar') }}
                </a>
                <x-language-switcher class="justify-center pt-3 mt-3 border-t border-winay-arena" />
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    @include('layouts.footer')

    @php
        $numeroWhatsapp = preg_replace('/\D/', '', \App\Models\Configuracion::get('contacto_whatsapp', '+56 9 1234 5678'));
    @endphp
    <a href="https://wa.me/{{ $numeroWhatsapp }}" target="_blank" rel="noopener"
       aria-label="{{ __('Escríbenos por WhatsApp') }}" title="{{ __('Escríbenos por WhatsApp') }}"
       class="fixed bottom-5 right-5 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-105 hover:bg-[#1ebe5b]">
        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

</body>
</html>
