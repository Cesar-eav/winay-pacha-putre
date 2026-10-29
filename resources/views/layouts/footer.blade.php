<footer class="bg-winay-tierra text-winay-arena mt-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-8 sm:grid-cols-3">
        <div>
            <div class="inline-block bg-white rounded-xl p-2 mb-3">
                <img src="{{ asset('images/logo_12500x3655.png') }}" alt="Wiñaypacha Putre" class="h-10 w-auto">
            </div>
            <a href="https://www.instagram.com/winaypacha_putre/" target="_blank" rel="noopener"
               class="flex items-center gap-2 text-sm text-winay-arena/80 hover:text-white mb-3">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="3" width="18" height="18" rx="5"/>
                    <circle cx="12" cy="12" r="4"/>
                    <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/>
                </svg>
                @winaypacha_putre
            </a>
            <p class="text-sm text-winay-arena/80">
                {{ __('Cabañas en Putre, Región de Arica y Parinacota — difundiendo la cultura, cosmovisión y territorio del pueblo aymara.') }}
            </p>
        </div>

        <div>
            <p class="font-semibold text-white mb-2">{{ __('Contacto') }}</p>
            <ul class="text-sm text-winay-arena/80 space-y-1">
                <li>{{ \App\Models\Configuracion::get('contacto_direccion', 'Putre, Región de Arica y Parinacota') }}</li>
                <li>{{ \App\Models\Configuracion::get('contacto_telefono', '+56 9 0000 0000') }}</li>
                <li>{{ config('mail.contacto') }}</li>
            </ul>
        </div>

        <div>
            <p class="font-semibold text-white mb-2">{{ __('Enlaces') }}</p>
            <ul class="text-sm text-winay-arena/80 space-y-1">
                <li><a href="{{ lroute('cabanas.index') }}" class="hover:text-white">{{ __('Cabañas') }}</a></li>
                <li><a href="{{ lroute('habitaciones.index') }}" class="hover:text-white">{{ __('Habitaciones') }}</a></li>
                <li><a href="{{ lroute('entorno') }}" class="hover:text-white">{{ __('Qué visitar') }}</a></li>
                <li><a href="{{ lroute('contacto') }}" class="hover:text-white">{{ __('Contacto') }}</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-winay-arena/20 py-4 text-center text-xs text-winay-arena/60">
        &copy; {{ now()->year }} Wiñaypacha Putre
    </div>
</footer>
