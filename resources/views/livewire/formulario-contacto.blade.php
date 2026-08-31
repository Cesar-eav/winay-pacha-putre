<div>
    @if ($enviado)
        <div class="rounded-2xl bg-winay-andino/10 border border-winay-andino/30 p-6 text-winay-andino">
            <p class="font-semibold">¡Gracias por escribirnos!</p>
            <p class="text-sm mt-1">Recibimos tu mensaje y te responderemos a la brevedad.</p>
            <button type="button" wire:click="$set('enviado', false)" class="mt-4 text-sm underline">
                Enviar otro mensaje
            </button>
        </div>
    @else
        <form wire:submit="guardar" class="space-y-4">
            <div>
                <label for="nombre" class="block text-sm font-medium text-stone-700">Nombre</label>
                <input type="text" id="nombre" wire:model="nombre"
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="correo" class="block text-sm font-medium text-stone-700">Correo</label>
                <input type="email" id="correo" wire:model="correo"
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                @error('correo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="telefono" class="block text-sm font-medium text-stone-700">Teléfono (opcional)</label>
                <input type="text" id="telefono" wire:model="telefono"
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                @error('telefono') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="mensaje" class="block text-sm font-medium text-stone-700">Mensaje</label>
                <textarea id="mensaje" wire:model="mensaje" rows="4"
                          class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota"></textarea>
                @error('mensaje') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition"
                    wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="guardar">Enviar mensaje</span>
                <span wire:loading wire:target="guardar">Enviando…</span>
            </button>
        </form>
    @endif
</div>
