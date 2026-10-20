<div>
    @include('admin.partials.banner-exito')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-winay-tierra">Cabañas</h1>
        <button type="button" wire:click="nuevo" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra">
            + Nueva cabaña
        </button>
    </div>

    @if ($mostrarFormulario)
        <div class="mb-8 bg-white rounded-2xl border border-stone-200 p-6">
            <h2 class="font-semibold text-winay-tierra mb-4">{{ $editandoId ? 'Editar cabaña' : 'Nueva cabaña' }}</h2>

            <form wire:submit="guardar" class="space-y-5">
                <div class="grid gap-4 sm:grid-cols-[8rem_1fr_1fr]">
                    <div>
                        <label class="block text-sm font-medium text-stone-700">N° de cabaña</label>
                        <input type="number" min="1" wire:model="numero" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                        @error('numero') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Nombre</label>
                        <input type="text" wire:model.live.debounce.500ms="nombre" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                        @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Slug</label>
                        <input type="text" wire:model="slug" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                        @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Capacidad (personas, opcional)</label>
                        <input type="number" min="1" wire:model="capacidad" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                        @error('capacidad') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Cantidad de baños</label>
                        <input type="number" min="0" wire:model="cantidadBanos" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                        @error('cantidadBanos') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="inline-flex items-center gap-2 text-sm text-stone-700">
                            <input type="checkbox" wire:model="banoPrivado" class="rounded border-stone-300 text-winay-terracota focus:ring-winay-terracota">
                            Baño privado
                        </label>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Precio desde (texto libre, opcional)</label>
                        <input type="text" wire:model="precioDesde" placeholder="Consultar" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    </div>
                </div>

                @include('admin.partials.campo-richtext', ['prop' => 'descripcion', 'label' => 'Descripción'])

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-2">Equipamiento</label>
                    @foreach ($equipamientosPorAmbito as $ambito => $items)
                        <p class="text-xs uppercase tracking-wide text-stone-500 mt-3 mb-1">
                            {{ $ambito === 'cabana' ? 'Cabaña' : 'Habitación' }}
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach ($items as $item)
                                <label class="inline-flex items-center gap-2 text-sm text-stone-700">
                                    <input type="checkbox" value="{{ $item->id }}" wire:model="equipamientosSeleccionados"
                                           class="rounded border-stone-300 text-winay-terracota focus:ring-winay-terracota">
                                    {{ $item->nombre }}
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
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

                <div>
                    <label class="block text-sm font-medium text-stone-700">Imagen principal</label>

                    <div class="mt-2 flex items-center gap-4">
                        @if ($nuevaImagenPrincipal)
                            <div class="relative w-32 aspect-video rounded-lg overflow-hidden border border-winay-terracota">
                                @if ($this->fotoEsPrevisualizable($nuevaImagenPrincipal))
                                    <img src="{{ $nuevaImagenPrincipal->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif ($previsualizacionPrincipalHeic)
                                    <img src="{{ $previsualizacionPrincipalHeic }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-stone-100 text-[10px] text-stone-500 text-center px-1">Formato no compatible</div>
                                @endif
                                <span class="absolute top-1 left-1 bg-winay-terracota text-white text-[10px] px-1.5 py-0.5 rounded">Nueva</span>
                                <button type="button" wire:click="descartarImagenPrincipalNueva"
                                        class="absolute bottom-1 right-1 bg-black/60 text-white text-xs px-1.5 py-0.5 rounded hover:text-red-300">
                                    ✕
                                </button>
                            </div>
                        @elseif ($imagenPrincipalActual)
                            <div class="relative w-32 aspect-video rounded-lg overflow-hidden border border-stone-200">
                                <img src="{{ \App\Services\ProcesadorImagen::urlVariante($imagenPrincipalActual, 'thumbs') }}" class="w-full h-full object-cover">
                                <button type="button" wire:click="quitarImagenPrincipalActual" onclick="return confirm('¿Quitar la imagen principal?')"
                                        class="absolute bottom-1 right-1 bg-black/60 text-white text-xs px-1.5 py-0.5 rounded hover:text-red-300">
                                    ✕
                                </button>
                            </div>
                        @else
                            <div class="w-32 aspect-video rounded-lg border border-dashed border-stone-300 flex items-center justify-center text-xs text-stone-400">
                                Sin imagen
                            </div>
                        @endif

                        <div class="flex-1">
                            <input type="file" wire:model="nuevaImagenPrincipal" accept="image/*"
                                   class="block w-full text-sm text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-winay-arena file:text-winay-tierra file:text-sm file:font-semibold hover:file:bg-winay-arena/70">
                            <p class="mt-1 text-xs text-stone-500">Portada de la cabaña. JPG, PNG, WebP o HEIC de iPhone.</p>
                            <div wire:loading wire:target="nuevaImagenPrincipal" class="mt-1 text-xs text-stone-500">Subiendo…</div>
                            @error('nuevaImagenPrincipal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                @include('admin.partials.galeria-editor', ['etiqueta' => 'Galería de imágenes'])

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra"
                            wire:loading.attr="disabled" wire:target="guardar">
                        Guardar
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
                    <th class="text-left px-4 py-3">N°</th>
                    <th class="text-left px-4 py-3">Nombre</th>
                    <th class="text-left px-4 py-3">Capacidad</th>
                    <th class="text-left px-4 py-3">Baños</th>
                    <th class="text-left px-4 py-3">Orden</th>
                    <th class="text-left px-4 py-3">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($cabanas as $cabana)
                    <tr>
                        <td class="px-4 py-3">
                            @if ($cabana->imagen_principal_thumb_url)
                                <img src="{{ $cabana->imagen_principal_thumb_url }}" alt="{{ $cabana->nombre }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg border border-dashed border-stone-300"></div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-stone-500">{{ $cabana->numero ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $cabana->nombre }}</td>
                        <td class="px-4 py-3">{{ $cabana->capacidad ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $cabana->cantidad_banos }} {{ $cabana->bano_privado ? '(privado)' : '(compartido)' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button type="button" wire:click="moverOrden({{ $cabana->id }}, 'arriba')" @if ($loop->first) disabled @endif
                                        class="text-stone-400 hover:text-winay-terracota disabled:opacity-30 disabled:hover:text-stone-400" title="Subir">▲</button>
                                <button type="button" wire:click="moverOrden({{ $cabana->id }}, 'abajo')" @if ($loop->last) disabled @endif
                                        class="text-stone-400 hover:text-winay-terracota disabled:opacity-30 disabled:hover:text-stone-400" title="Bajar">▼</button>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <button type="button" wire:click="alternarPublicado({{ $cabana->id }})"
                                    class="px-2 py-0.5 rounded-full text-xs {{ $cabana->publicado ? 'bg-winay-andino/10 text-winay-andino hover:bg-winay-andino/20' : 'bg-stone-100 text-stone-500 hover:bg-stone-200' }}">
                                {{ $cabana->publicado ? 'Publicado' : 'Borrador' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <button type="button" wire:click="editar({{ $cabana->id }})" class="text-winay-terracota hover:underline">Editar</button>
                            <button type="button" wire:click="eliminar({{ $cabana->id }})" onclick="return confirm('¿Eliminar esta cabaña?')" class="text-red-600 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-stone-500">No hay cabañas todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
