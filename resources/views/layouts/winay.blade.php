<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
<body class="bg-white text-stone-800 antialiased">

    <header x-data="{ open: false }" class="bg-white border-b border-winay-arena sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
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
                            'entorno' => [__('Qué Visitar'), lroute('entorno')],
                            'nosotros' => [__('Nosotros'), lroute('nosotros')],
                            'contacto' => [__('Contacto'), lroute('contacto')],
                        ];
                    @endphp

                    @foreach ($navLinks as $routeName => [$label, $url])
                        <a href="{{ $url }}"
                           class="text-sm font-medium {{ str_starts_with($nombreRutaActual, $routeName) ? 'text-winay-terracota' : 'text-stone-600 hover:text-winay-terracota' }}">
                            {{ $label }}
                        </a>
                    @endforeach

                    <a href="{{ lroute('reserva') }}"
                       class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                        {{ __('Reserva ahora') }}
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
                       class="block px-2 py-2 rounded-md text-sm font-medium {{ str_starts_with($nombreRutaActual, $routeName) ? 'text-winay-terracota bg-winay-arena' : 'text-stone-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ lroute('reserva') }}"
                   class="block mt-2 px-2 py-2 rounded-md text-sm font-semibold text-white bg-winay-terracota text-center">
                    {{ __('Reserva ahora') }}
                </a>
                <x-language-switcher class="justify-center pt-3 mt-3 border-t border-winay-arena" />
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    @include('layouts.footer')

</body>
</html>
