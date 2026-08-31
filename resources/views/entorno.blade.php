<x-winay-layout>
    <x-slot:titulo>Qué Visitar</x-slot:titulo>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ tab: 'lugares' }">
        <h1 class="text-3xl font-bold text-winay-tierra">Qué Visitar</h1>
        <p class="mt-2 text-stone-600 max-w-2xl">Lugares del entorno y flora y fauna del altiplano.</p>

        <div class="mt-8 inline-flex rounded-full border border-winay-arena p-1">
            <button type="button" @click="tab = 'lugares'"
                    :class="tab === 'lugares' ? 'bg-winay-terracota text-white' : 'text-stone-600'"
                    class="px-4 py-1.5 rounded-full text-sm font-medium transition">
                Lugares
            </button>
            <button type="button" @click="tab = 'especies'"
                    :class="tab === 'especies' ? 'bg-winay-terracota text-white' : 'text-stone-600'"
                    class="px-4 py-1.5 rounded-full text-sm font-medium transition">
                Flora y Fauna
            </button>
        </div>

        <div x-show="tab === 'lugares'" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($lugares as $lugar)
                <div class="rounded-2xl border border-winay-arena overflow-hidden">
                    <x-galeria-lightbox :imagenes="$lugar->imagenes" :titulo="$lugar->nombre" />
                    <div class="p-5">
                        <h3 class="font-semibold text-winay-tierra">{{ $lugar->nombre }}</h3>
                        <p class="mt-1 text-xs text-stone-500">{{ $lugar->ubicacion_texto }}</p>
                        <p class="mt-2 text-sm text-stone-600">{{ Str::limit(strip_tags($lugar->descripcion), 140) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-stone-500">Aún no hay lugares publicados.</p>
            @endforelse
        </div>

        <div x-show="tab === 'especies'" x-cloak class="mt-10 space-y-12">
            @forelse ($especiesPorTipo as $tipo => $especies)
                <div>
                    <h2 class="text-xl font-semibold text-winay-tierra mb-6 capitalize">{{ $tipo }}s</h2>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($especies as $especie)
                            <div class="rounded-2xl border border-winay-arena overflow-hidden">
                                <img src="{{ asset('images/'.$especie->imagen) }}" alt="{{ $especie->nombre_comun }}" class="w-full aspect-video object-cover">
                                <div class="p-5">
                                    <h3 class="font-semibold text-winay-tierra">{{ $especie->nombre_comun }}</h3>
                                    <p class="text-xs italic text-stone-500">{{ $especie->nombre_cientifico }}</p>
                                    <p class="mt-2 text-sm text-stone-600">{{ Str::limit(strip_tags($especie->descripcion), 140) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-stone-500">Aún no hay especies publicadas.</p>
            @endforelse
        </div>
    </section>
</x-winay-layout>
