<div>
    @if ($enviado)
        <div class="rounded-2xl bg-winay-andino/10 border border-winay-andino/30 p-6 text-winay-andino">
            <p class="flex items-center gap-2 font-semibold">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5" />
                </svg>
                ¡Solicitud recibida!
            </p>
            <p class="text-sm mt-1">
                La revisaremos personalmente y te contactaremos por correo o WhatsApp para confirmar disponibilidad.
            </p>
            <button type="button" wire:click="$set('enviado', false)" class="mt-4 text-sm underline">
                Enviar otra solicitud
            </button>
        </div>
    @else
        <form wire:submit="guardar" class="space-y-8">
            <fieldset class="border-0 p-0 m-0">
                <legend class="text-sm font-semibold text-winay-tierra mb-3 px-0">Tus datos</legend>
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form-field name="nombre" label="Nombre" type="text" required autocomplete="given-name" />
                    <x-form-field name="apellido" label="Apellido" type="text" required autocomplete="family-name" />
                    <x-form-field name="correo" label="Correo" type="email" required autocomplete="email" />
                    <x-form-field name="whatsapp" label="WhatsApp" type="tel" inputmode="tel" required autocomplete="tel" />
                </div>
            </fieldset>

            <fieldset class="border-0 p-0 m-0" x-data="{ llegada: '{{ $fechaLlegada }}' }">
                <legend class="text-sm font-semibold text-winay-tierra mb-3 px-0">Tu estadía</legend>
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form-field name="fechaLlegada" label="Fecha de llegada" type="date" required
                        min="{{ now()->toDateString() }}" x-on:input="llegada = $event.target.value" />
                    <x-form-field name="fechaSalida" label="Fecha de salida" type="date" required
                        x-bind:min="llegada || '{{ now()->toDateString() }}'" />
                    <x-form-field name="numPersonas" label="Número de personas" type="number" min="1" inputmode="numeric" required />

                    <div>
                        <label for="cabanaId" class="block text-sm font-medium text-stone-700">Cabaña de interés (opcional)</label>
                        <select id="cabanaId" wire:model="cabanaId"
                                class="mt-1 block w-full rounded-lg focus:ring-winay-terracota {{ $errors->has('cabanaId') ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-winay-terracota' }}">
                            <option value="">Sin preferencia</option>
                            @foreach ($cabanas as $opcion)
                                <option value="{{ $opcion->id }}">{{ $opcion->nombre }}</option>
                            @endforeach
                        </select>
                        @error('cabanaId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="border-0 p-0 m-0">
                <legend class="text-sm font-semibold text-winay-tierra mb-3 px-0">Cuéntanos más (opcional)</legend>
                <div>
                    <label for="comentarios" class="block text-sm font-medium text-stone-700 sr-only">Comentarios</label>
                    <textarea id="comentarios" wire:model="comentarios" rows="3" maxlength="2000"
                              placeholder="Fechas flexibles, ocasión especial, alguna necesidad particular…"
                              class="block w-full rounded-lg focus:ring-winay-terracota {{ $errors->has('comentarios') ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-winay-terracota' }}"></textarea>
                    @error('comentarios') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </fieldset>

            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition"
                    wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="guardar">Enviar solicitud</span>
                <span wire:loading wire:target="guardar">Enviando…</span>
            </button>
        </form>
    @endif
</div>
