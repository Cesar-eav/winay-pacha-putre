<footer class="bg-winay-tierra text-winay-arena mt-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-8 sm:grid-cols-3">
        <div>
            <div class="inline-block bg-white rounded-xl p-2 mb-3">
                <img src="{{ asset('images/logo_12500x3655.png') }}" alt="Wiñaypacha Putre" class="h-10 w-auto">
            </div>
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
        &copy; {{ now()->year }} Wiñaypacha Putre
    </div>
</footer>
