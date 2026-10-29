<div>
    @include('admin.partials.banner-exito')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-winay-tierra">Carrusel del inicio</h1>
        <button type="button" wire:click="nuevo" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra">
            + Nueva imagen
        </button>
    </div>

    <details class="mb-6 bg-winay-andino/5 border border-winay-andino/20 rounded-2xl p-5 text-sm text-stone-700" @if ($mostrarFormulario || $imagenes->isEmpty()) open @endif>
        <summary class="font-semibold text-winay-andino cursor-pointer">Recomendaciones para que las fotos se vean bien</summary>
        <ul class="mt-3 space-y-2 list-disc pl-5 marker:text-winay-andino">
            <li><strong>Tamaño:</strong> foto horizontal de al menos <strong>2000 px de ancho</strong>. Lo ideal es <strong>2400 × 1350 px</strong> (formato 16:9). No se aceptan fotos de menos de {{ \App\Livewire\Admin\Carrusel::ANCHO_MINIMO }} px de ancho porque se verían borrosas.</li>
            <li><strong>Recorte:</strong> el carrusel ocupa todo el ancho de la pantalla. En computador se ve como una franja panorámica (se recorta arriba y abajo) y en el celular solo se ve la parte central (se recortan los costados).</li>
            <li><strong>Composición:</strong> deja lo importante (las cabañas, un cerro, un animal) cerca del centro. Evita fotos con texto, logos o personas pegadas a los bordes.</li>
            <li><strong>Frase:</strong> aparece abajo a la izquierda sobre un degradado oscuro. Funciona mejor si en esa zona hay cielo, cerro o pasto, no un detalle importante. Mantenla corta (máximo 120 caracteres).</li>
            <li><strong>Encuadre:</strong> si se corta lo importante, prueba "Mostrar la parte de arriba" o "de abajo" y mira la vista previa antes de guardar.</li>
            <li><strong>Formato:</strong> JPG, PNG, WebP o HEIC (iPhone), hasta 25 MB. El sitio la achica y optimiza solo.</li>
            <li><strong>Cantidad:</strong> entre 3 y 6 fotos. Cambian solas cada 5 segundos.</li>
        </ul>
    </details>

    @if ($mostrarFormulario)
        @php
            $vistaPrevia = $nuevaFoto && ! $this->esHeic() ? $nuevaFoto->temporaryUrl() : ($nuevaFoto ? null : $imagenActual);
            $clasesEncuadre = collect(\App\Models\ImagenCarrusel::ENCUADRES)->map(fn ($e) => $e[1]);
        @endphp

        <div class="mb-8 bg-white rounded-2xl border border-stone-200 p-6">
            <h2 class="font-semibold text-winay-tierra mb-4">{{ $editandoId ? 'Editar imagen' : 'Nueva imagen' }}</h2>

            <form wire:submit="guardar" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-stone-700">Foto</label>
                    <input type="file" wire:model="nuevaFoto" accept="image/*,.heic,.heif"
                           class="mt-2 block w-full text-sm text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-winay-arena file:text-winay-tierra file:text-sm file:font-semibold hover:file:bg-winay-arena/70">
                    <p class="mt-1 text-xs text-stone-500">
                        Horizontal, mínimo 2000 px de ancho (ideal 2400 × 1350). {{ $editandoId ? 'Si no eliges otra, se mantiene la foto actual.' : '' }}
                    </p>
                    <div wire:loading wire:target="nuevaFoto" class="mt-1 text-xs text-stone-500">Subiendo…</div>
                    @error('nuevaFoto') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ clases: @js($clasesEncuadre) }">
                    <p class="text-sm font-medium text-stone-700">Vista previa</p>

                    @if ($vistaPrevia)
                        <div class="mt-2 flex flex-wrap items-end gap-4">
                            <figure class="flex-1 min-w-60">
                                <div class="relative aspect-[2.3/1] rounded-lg overflow-hidden bg-winay-andino">
                                    <img src="{{ $vistaPrevia }}" alt="" class="absolute inset-0 size-full object-cover" :class="clases[$wire.encuadre]">
                                    <div class="absolute inset-x-0 bottom-0 h-1/2 bg-linear-to-t from-black/60 to-transparent"></div>
                                    <p class="absolute left-3 bottom-3 right-1/3 text-sm font-display text-white leading-snug" x-text="$wire.frase.es"></p>
                                </div>
                                <figcaption class="mt-1 text-xs text-stone-500">Computador</figcaption>
                            </figure>
                            <figure class="w-28 shrink-0">
                                <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-winay-andino">
                                    <img src="{{ $vistaPrevia }}" alt="" class="absolute inset-0 size-full object-cover" :class="clases[$wire.encuadre]">
                                    <div class="absolute inset-x-0 bottom-0 h-1/2 bg-linear-to-t from-black/60 to-transparent"></div>
                                    <p class="absolute left-2 bottom-2 right-2 text-[10px] font-display text-white leading-snug" x-text="$wire.frase.es"></p>
                                </div>
                                <figcaption class="mt-1 text-xs text-stone-500">Celular</figcaption>
                            </figure>
                        </div>
                    @else
                        <p class="mt-2 rounded-lg border border-dashed border-stone-300 px-4 py-6 text-center text-xs text-stone-400">
                            {{ $nuevaFoto ? 'Las fotos HEIC se pueden ver recién después de guardar.' : 'Sube una foto para ver cómo quedará.' }}
                        </p>
                    @endif
                </div>

                @include('admin.partials.campo-traducible', ['prop' => 'frase', 'label' => 'Frase sobre la foto (opcional)'])

                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Encuadre</label>
                        <select wire:model.live="encuadre" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                            @foreach (\App\Models\ImagenCarrusel::ENCUADRES as $valor => [$etiqueta])
                                <option value="{{ $valor }}">{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                        @error('encuadre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Orden</label>
                        <input type="number" wire:model="orden" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 text-sm text-stone-700">
                            <input type="checkbox" wire:model="publicado" class="rounded border-stone-300 text-winay-terracota focus:ring-winay-terracota">
                            Publicado
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra disabled:opacity-70 disabled:cursor-wait"
                            wire:loading.attr="disabled" wire:target="guardar,nuevaFoto">
                        <x-spinner wire:loading wire:target="guardar" />
                        <span wire:loading.remove wire:target="guardar">Guardar</span>
                        <span wire:loading wire:target="guardar">Guardando…</span>
                    </button>
                    <button type="button" wire:click="cancelar" class="text-sm text-stone-500 hover:text-stone-700">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-winay-arena text-stone-600">
                <tr>
                    <th class="px-4 py-3"></th>
                    <th class="text-left px-4 py-3">Frase</th>
                    <th class="text-left px-4 py-3">Orden</th>
                    <th class="text-left px-4 py-3">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($imagenes as $imagen)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ $imagen->thumb_url }}" alt="" class="w-24 aspect-video rounded-lg object-cover {{ $imagen->clase_encuadre }}">
                        </td>
                        <td class="px-4 py-3 {{ filled($imagen->getTranslation('frase', 'es', false)) ? '' : 'text-stone-400' }}">
                            {{ $imagen->getTranslation('frase', 'es', false) ?: 'Sin frase' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button type="button" wire:click="moverOrden({{ $imagen->id }}, 'arriba')" @if ($loop->first) disabled @endif
                                        class="text-stone-400 hover:text-winay-terracota disabled:opacity-30 disabled:hover:text-stone-400" title="Subir">▲</button>
                                <button type="button" wire:click="moverOrden({{ $imagen->id }}, 'abajo')" @if ($loop->last) disabled @endif
                                        class="text-stone-400 hover:text-winay-terracota disabled:opacity-30 disabled:hover:text-stone-400" title="Bajar">▼</button>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <button type="button" wire:click="alternarPublicado({{ $imagen->id }})"
                                    class="px-2 py-0.5 rounded-full text-xs {{ $imagen->publicado ? 'bg-winay-andino/10 text-winay-andino hover:bg-winay-andino/20' : 'bg-stone-100 text-stone-500 hover:bg-stone-200' }}">
                                {{ $imagen->publicado ? 'Publicado' : 'Borrador' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <button type="button" wire:click="editar({{ $imagen->id }})" wire:loading.attr="disabled" wire:target="editar({{ $imagen->id }})" class="inline-flex items-center gap-1 text-winay-terracota hover:underline disabled:cursor-wait"><x-spinner wire:loading wire:target="editar({{ $imagen->id }})"/>Editar</button>
                            <button type="button" wire:click="eliminar({{ $imagen->id }})" onclick="return confirm('¿Eliminar esta imagen del carrusel?')" class="text-red-600 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-stone-500">No hay imágenes en el carrusel. Mientras no haya ninguna publicada, el inicio se muestra sin carrusel.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
