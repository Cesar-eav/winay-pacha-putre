<x-winay-layout>
    <x-slot:titulo>Nosotros</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 lg:grid-cols-2 items-center">
        <x-galeria-lightbox :imagenes="$pagina->imagenes" titulo="Wiñay Pacha Putre" />

        <div>
            <h1 class="text-3xl font-bold text-winay-tierra">Nosotros</h1>

            <h2 class="mt-6 font-semibold text-winay-tierra">Nuestra historia</h2>
            <div class="mt-2 text-stone-600 space-y-3">
                {!! $pagina->historia ?: '<p>Contenido pendiente de definir con el cliente.</p>' !!}
            </div>

            <h2 class="mt-6 font-semibold text-winay-tierra">Nuestro mensaje</h2>
            <div class="mt-2 text-stone-600 space-y-3">
                {!! $pagina->mensaje ?: '<p>Contenido pendiente de definir con el cliente.</p>' !!}
            </div>
        </div>
    </section>
</x-winay-layout>
