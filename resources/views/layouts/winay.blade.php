<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($titulo) ? $titulo.' — Wiñay Pacha Putre' : 'Wiñay Pacha Putre' }}</title>

    @livewireScriptConfig
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-winay-arena text-stone-800 antialiased">

    <header x-data="{ open: false }" class="bg-white border-b border-winay-arena sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('inicio') }}" class="font-semibold text-lg text-winay-tierra">
                    Wiñay Pacha Putre
                </a>

                <nav class="hidden lg:flex items-center gap-6">
                    @php
                        $navLinks = [
                            'inicio' => ['Inicio', route('inicio')],
                            'cultura' => ['Cultura Aymara', route('cultura')],
                            'putre' => ['Putre', route('putre')],
                            'cabanas.index' => ['Cabañas', route('cabanas.index')],
                            'entorno' => ['Qué Visitar', route('entorno')],
                            'nosotros' => ['Nosotros', route('nosotros')],
                            'contacto' => ['Contacto', route('contacto')],
                        ];
                    @endphp

                    @foreach ($navLinks as $routeName => [$label, $url])
                        <a href="{{ $url }}"
                           class="text-sm font-medium {{ request()->routeIs($routeName.'*') ? 'text-winay-terracota' : 'text-stone-600 hover:text-winay-terracota' }}">
                            {{ $label }}
                        </a>
                    @endforeach

                    <a href="{{ route('reserva') }}"
                       class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                        Reserva ahora
                    </a>
                </nav>

                <button @click="open = ! open" class="lg:hidden p-2 text-stone-600" aria-label="Abrir menú">
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
                       class="block px-2 py-2 rounded-md text-sm font-medium {{ request()->routeIs($routeName.'*') ? 'text-winay-terracota bg-winay-arena' : 'text-stone-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('reserva') }}"
                   class="block mt-2 px-2 py-2 rounded-md text-sm font-semibold text-white bg-winay-terracota text-center">
                    Reserva ahora
                </a>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-winay-tierra text-winay-arena mt-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-8 sm:grid-cols-3">
            <div>
                <p class="font-semibold text-white mb-2">Wiñay Pacha Putre</p>
                <p class="text-sm text-winay-arena/80">
                    Cabañas en Putre, Región de Arica y Parinacota — difundiendo la cultura, cosmovisión y territorio del pueblo aymara.
                </p>
            </div>

            <div>
                <p class="font-semibold text-white mb-2">Contacto</p>
                <ul class="text-sm text-winay-arena/80 space-y-1">
                    <li>{{ \App\Models\Configuracion::get('contacto_direccion', 'Putre, Región de Arica y Parinacota') }}</li>
                    <li>{{ \App\Models\Configuracion::get('contacto_telefono', '+56 9 0000 0000') }}</li>
                    <li>{{ \App\Models\Configuracion::get('contacto_email', 'contacto@winaypachaputre.cl') }}</li>
                </ul>
            </div>

            <div>
                <p class="font-semibold text-white mb-2">Enlaces</p>
                <ul class="text-sm text-winay-arena/80 space-y-1">
                    <li><a href="{{ route('cabanas.index') }}" class="hover:text-white">Cabañas</a></li>
                    <li><a href="{{ route('entorno') }}" class="hover:text-white">Qué visitar</a></li>
                    <li><a href="{{ route('contacto') }}" class="hover:text-white">Contacto</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-winay-arena/20 py-4 text-center text-xs text-winay-arena/60">
            &copy; {{ now()->year }} Wiñay Pacha Putre
        </div>
    </footer>

</body>
</html>
