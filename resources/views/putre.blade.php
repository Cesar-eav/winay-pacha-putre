<x-winay-layout>
    <x-slot:titulo>Putre</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-winay-tierra">Putre</h1>
        <p class="mt-2 text-stone-600 max-w-2xl">
            Qué hacer en Putre y cómo vivirlo como un local. (Placeholder — pendiente de contenido definitivo del cliente.)
        </p>

        <div class="mt-10">
            <h2 class="text-xl font-semibold text-winay-tierra mb-6">Qué hacer</h2>
            <div class="grid gap-6 sm:grid-cols-2">
                @forelse ($actividades as $tema)
                    <div class="rounded-2xl border border-winay-arena overflow-hidden">
                        <x-galeria-lightbox :imagenes="$tema->imagenes" :titulo="$tema->titulo" />
                        <div class="p-5">
                            <h3 class="font-semibold text-winay-tierra">{{ $tema->titulo }}</h3>
                            <p class="mt-2 text-sm text-stone-600">{{ Str::limit(strip_tags($tema->cuerpo), 160) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-stone-500">Aún no hay actividades publicadas.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-xl font-semibold text-winay-tierra mb-6">Vive Putre como un local</h2>
            <div class="grid gap-6 sm:grid-cols-2">
                @forelse ($viveLocal as $tema)
                    <div class="rounded-2xl border border-winay-arena overflow-hidden">
                        <x-galeria-lightbox :imagenes="$tema->imagenes" :titulo="$tema->titulo" />
                        <div class="p-5">
                            <h3 class="font-semibold text-winay-tierra">{{ $tema->titulo }}</h3>
                            <p class="mt-2 text-sm text-stone-600">{{ Str::limit(strip_tags($tema->cuerpo), 160) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-stone-500">Aún no hay contenido publicado.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-winay-layout>
