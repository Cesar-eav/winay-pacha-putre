<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($titulo) ? $titulo.' — Admin' : 'Admin' }} — Wiñay Pacha Putre</title>

    @livewireScriptConfig
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-winay-arena text-stone-800 antialiased">

    <div x-data="{ open: false }" class="lg:flex min-h-screen">

        <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-winay-tierra text-winay-arena shrink-0">
            <a href="{{ route('admin.temas') }}" class="px-6 py-5 font-semibold text-white border-b border-white/10">
                Wiñay Pacha Putre
            </a>

            <nav class="flex-1 px-3 py-4 space-y-1">
                <p class="px-3 pt-2 pb-1 text-xs uppercase tracking-wide text-winay-arena/50">Catálogo</p>
                <a href="{{ route('admin.temas') }}"
                   class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.temas*') ? 'bg-white/10 text-white' : 'text-winay-arena/80 hover:bg-white/5' }}">
                    Temas
                </a>
                <a href="{{ route('admin.cabanas') }}"
                   class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.cabanas*') ? 'bg-white/10 text-white' : 'text-winay-arena/80 hover:bg-white/5' }}">
                    Cabañas
                </a>
            </nav>

            <a href="{{ route('inicio') }}" class="px-6 py-4 text-xs text-winay-arena/60 border-t border-white/10 hover:text-white">
                ← Ver sitio público
            </a>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="lg:hidden bg-winay-tierra text-white flex items-center justify-between px-4 h-14">
                <span class="font-semibold">Wiñay Pacha Putre — Admin</span>
                <button @click="open = ! open" class="p-2" aria-label="Abrir menú">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': ! open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </header>

            <div x-show="open" x-cloak class="lg:hidden bg-winay-tierra text-winay-arena px-4 py-3 space-y-1">
                <a href="{{ route('admin.temas') }}" class="block px-2 py-2 rounded-md text-sm {{ request()->routeIs('admin.temas*') ? 'text-white bg-white/10' : '' }}">Temas</a>
                <a href="{{ route('admin.cabanas') }}" class="block px-2 py-2 rounded-md text-sm {{ request()->routeIs('admin.cabanas*') ? 'text-white bg-white/10' : '' }}">Cabañas</a>
                <a href="{{ route('inicio') }}" class="block px-2 py-2 rounded-md text-sm text-winay-arena/70">← Ver sitio público</a>
            </div>

            <header class="bg-white border-b border-winay-arena hidden lg:flex items-center justify-end px-6 h-14">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center text-sm text-stone-600 hover:text-winay-terracota">
                            {{ Auth::user()->name }}
                            <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>
</html>
