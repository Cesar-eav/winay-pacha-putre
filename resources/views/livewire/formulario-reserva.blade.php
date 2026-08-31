<div>
    @if ($enviado)
        <div class="rounded-2xl bg-winay-andino/10 border border-winay-andino/30 p-6 text-winay-andino">
            <p class="font-semibold">¡Solicitud recibida!</p>
            <p class="text-sm mt-1">
                Este formulario no confirma la reserva de forma automática — el anfitrión revisará tu
                solicitud y se pondrá en contacto por correo o WhatsApp para confirmar disponibilidad.
            </p>
            <button type="button" wire:click="$set('enviado', false)" class="mt-4 text-sm underline">
                Enviar otra solicitud
            </button>
        </div>
    @else
        <form wire:submit="guardar" class="space-y-4">
            <p class="text-sm text-stone-500">
                Esta es una solicitud de reserva, no una reserva confirmada. El anfitrión la confirma
                manualmente por correo o WhatsApp.
            </p>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-stone-700">Nombre</label>
                    <input type="text" id="nombre" wire:model="nombre"
                           class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="apellido" class="block text-sm font-medium text-stone-700">Apellido</label>
                    <input type="text" id="apellido" wire:model="apellido"
                           class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    @error('apellido') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="correo" class="block text-sm font-medium text-stone-700">Correo</label>
                    <input type="email" id="correo" wire:model="correo"
                           class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    @error('correo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-stone-700">WhatsApp</label>
                    <input type="text" id="whatsapp" wire:model="whatsapp"
                           class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    @error('whatsapp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="fechaLlegada" class="block text-sm font-medium text-stone-700">Fecha de llegada</label>
                    <input type="date" id="fechaLlegada" wire:model="fechaLlegada"
                           class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    @error('fechaLlegada') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="fechaSalida" class="block text-sm font-medium text-stone-700">Fecha de salida</label>
                    <input type="date" id="fechaSalida" wire:model="fechaSalida"
                           class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    @error('fechaSalida') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="numPersonas" class="block text-sm font-medium text-stone-700">Número de personas</label>
                    <input type="number" min="1" id="numPersonas" wire:model="numPersonas"
                           class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    @error('numPersonas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cabanaId" class="block text-sm font-medium text-stone-700">Cabaña de interés (opcional)</label>
                    <select id="cabanaId" wire:model="cabanaId"
                            class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                        <option value="">Sin preferencia</option>
                        @foreach ($cabanas as $opcion)
                            <option value="{{ $opcion->id }}">{{ $opcion->nombre }}</option>
                        @endforeach
                    </select>
                    @error('cabanaId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="comentarios" class="block text-sm font-medium text-stone-700">Comentarios (opcional)</label>
                <textarea id="comentarios" wire:model="comentarios" rows="3"
                          class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota"></textarea>
                @error('comentarios') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition"
                    wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="guardar">Enviar solicitud</span>
                <span wire:loading wire:target="guardar">Enviando…</span>
            </button>
        </form>
    @endif
</div>
