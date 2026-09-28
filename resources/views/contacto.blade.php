<x-winay-layout>
    <x-slot:titulo>{{ __('Contacto') }}</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-12 lg:grid-cols-2">
        <div>
            <h1 class="text-3xl font-bold text-winay-tierra">{{ __('Contacto') }}</h1>
            <p class="mt-2 text-stone-600">{{ __('¿Tienes preguntas? Escríbenos y te responderemos a la brevedad.') }}</p>

            <div class="mt-8">
                @livewire('formulario-contacto')
            </div>
        </div>

        <div>
            <h2 class="font-semibold text-winay-tierra">{{ __('Cómo llegar') }}</h2>
            <ul class="mt-2 text-sm text-stone-600 space-y-1">
                <li>{{ $direccion }}</li>
                <li>{{ __('Teléfono') }}: {{ $telefono }}</li>
                <li>WhatsApp: {{ $whatsapp }}</li>
                <li>{{ __('Correo') }}: {{ $email }}</li>
            </ul>

            @if ($servicios->isNotEmpty())
                <h2 class="mt-8 font-semibold text-winay-tierra">{{ __('Servicios en Putre') }}</h2>
                <ul class="mt-2 grid grid-cols-2 gap-2 text-sm text-stone-600">
                    @foreach ($servicios as $servicio)
                        <li>{{ $servicio->nombre }}</li>
                    @endforeach
                </ul>
            @endif

            @if ($temasPublicoObjetivo->isNotEmpty())
                <h2 class="mt-8 font-semibold text-winay-tierra">{{ __('¿Para quién es Wiñaypacha?') }}</h2>
                <div class="mt-2 space-y-4">
                    @foreach ($temasPublicoObjetivo as $tema)
                        <div>
                            <h3 class="text-sm font-semibold text-stone-700">{{ $tema->titulo }}</h3>
                            <p class="text-sm text-stone-600">{{ Str::limit(strip_tags($tema->cuerpo), 160) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-winay-layout>
