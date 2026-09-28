<x-winay-layout>
    <x-slot:titulo>{{ __('Nosotros') }}</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 lg:grid-cols-2 items-center">
        <img src="{{ asset('images/nosotros/nosotros.jpg') }}" alt="{{ __('Familia de Wiñaypacha Putre en el Altiplano') }}"
             class="w-full rounded-2xl shadow-sm object-cover aspect-4/5">

        <div>
            <h1 class="text-3xl font-bold text-winay-tierra">{{ __('Nosotros') }}</h1>

            <div class="mt-6 text-stone-600 space-y-4">
                <p>{{ __('Somos una familia Aymara que hace más de 30 años hizo de Putre nuestro hogar. Aquí formamos nuestra familia y criamos a nuestros hijos, rodeados de las montañas, paisajes y tradiciones de nuestro querido Altiplano.') }}</p>

                <p>{{ __('Después de años de esfuerzo y perseverancia, hoy hacemos realidad un sueño con WIÑAYPACHA: un lugar creado desde el amor por nuestras raíces, donde buscamos compartir la belleza de nuestra tierra y mantener vivas nuestras tradiciones.') }}</p>

                <p>{{ __('Más que un alojamiento, queremos entregar una atención cercana y familiar, donde cada visitante se sienta bienvenido y pueda conocer, conectar y vivir la esencia del Altiplano chileno.') }}</p>
            </div>
        </div>
    </section>
</x-winay-layout>
