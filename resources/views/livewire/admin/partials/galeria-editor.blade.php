<div>
    <label class="block text-sm font-medium text-stone-700">Fotos</label>

    <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach ($imagenesExistentes as $i => $imagen)
            <div class="relative rounded-lg overflow-hidden border border-stone-200 aspect-square">
                <img src="{{ $imagen->url }}" alt="{{ $imagen->alt }}" class="w-full h-full object-cover">
                <div class="absolute inset-x-0 bottom-0 bg-black/60 flex items-center justify-between px-1 py-1">
                    <button type="button" wire:click="moverFotoExistente({{ $imagen->id }}, 'arriba')" @if ($i === 0) disabled @endif
                            class="text-white text-xs px-1 disabled:opacity-30">↑</button>
                    <button type="button" wire:click="moverFotoExistente({{ $imagen->id }}, 'abajo')" @if ($i === $imagenesExistentes->count() - 1) disabled @endif
                            class="text-white text-xs px-1 disabled:opacity-30">↓</button>
                    <button type="button" wire:click="eliminarFotoExistente({{ $imagen->id }})"
                            onclick="return confirm('¿Eliminar esta foto?')"
                            class="text-white text-xs px-1 hover:text-red-300">✕</button>
                </div>
            </div>
        @endforeach

        @foreach ($nuevasFotos as $i => $foto)
            <div class="relative rounded-lg overflow-hidden border border-winay-terracota aspect-square">
                <img src="{{ $foto->temporaryUrl() }}" class="w-full h-full object-cover">
                <span class="absolute top-1 left-1 bg-winay-terracota text-white text-[10px] px-1.5 py-0.5 rounded">Nueva</span>
                <button type="button" wire:click="eliminarFotoNueva({{ $i }})"
                        class="absolute bottom-1 right-1 bg-black/60 text-white text-xs px-1.5 py-0.5 rounded hover:text-red-300">
                    ✕
                </button>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        <input type="file" wire:model="nuevasFotos" multiple accept="image/*"
               class="block w-full text-sm text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-winay-arena file:text-winay-tierra file:text-sm file:font-semibold hover:file:bg-winay-arena/70">
        <div wire:loading wire:target="nuevasFotos" class="mt-1 text-xs text-stone-500">Subiendo…</div>
        @error('nuevasFotos.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
