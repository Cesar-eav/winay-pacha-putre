<div>
    @include('admin.partials.banner-exito')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-winay-tierra">{{ $etiquetas['plural'] }}</h1>
        <button type="button" wire:click="nuevo" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra">
            + {{ $etiquetas['nueva'] }}
        </button>
    </div>

    @if ($mostrarFormulario)
        <div class="mb-8 bg-white rounded-2xl border border-stone-200 p-6">
            <h2 class="font-semibold text-winay-tierra mb-4">{{ $editandoId ? 'Editar '.Str::lower($etiquetas['singular']) : $etiquetas['nueva'] }}</h2>

            <form wire:submit="guardar" class="space-y-5">
                <div class="grid gap-4 sm:grid-cols-[8rem_1fr_1fr]">
                    <div>
                        <label class="block text-sm font-medium text-stone-700">N° de {{ Str::lower($etiquetas['singular']) }}</label>
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
                        <label class="block text-sm font-medium text-stone-700">Precio en listados (texto libre, opcional)</label>
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

                @include('admin.partials.galeria-editor', ['etiqueta' => 'Galería de imágenes'])

                <div class="flex gap-3 pt-2">
                    <button type="submit" x-data="{ subiendo: false }" x-on:galeria-subiendo.window="subiendo = $event.detail" x-bind:disabled="subiendo"
                            wire:loading.attr="disabled" wire:target="guardar"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra disabled:opacity-70 disabled:cursor-wait">
                        <svg wire:loading wire:target="guardar" class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
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
                            <button type="button" wire:click="eliminar({{ $cabana->id }})" onclick="return confirm('¿Eliminar esta {{ Str::lower($etiquetas['singular']) }}?')" class="text-red-600 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-stone-500">No hay {{ Str::lower($etiquetas['plural']) }} todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8 bg-white rounded-2xl border border-stone-200 p-6">
        <h2 class="font-semibold text-winay-tierra">Precios por noche ({{ Str::lower($etiquetas['plural']) }})</h2>
        <p class="mt-1 text-sm text-stone-500">Se muestran en la ficha de cada {{ Str::lower($etiquetas['singular']) }}, solo hasta su capacidad.</p>

        <form wire:submit="guardarTarifas" class="mt-4 space-y-3">
            @foreach ($tarifas as $i => $monto)
                <div wire:key="tarifa-{{ $i }}" class="flex items-center gap-4">
                    <span class="w-24 text-sm text-stone-700">{{ $i + 1 }} {{ $i === 0 ? 'persona' : 'personas' }}</span>
                    <div class="relative w-40">
                        <span class="absolute inset-y-0 left-3 flex items-center text-sm text-stone-500">$</span>
                        <input type="number" min="0" step="1000" wire:model="tarifas.{{ $i }}" class="block w-full pl-7 rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    </div>
                    @error('tarifas.'.$i) <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @endforeach

            <div class="flex flex-wrap items-center gap-4 pt-2">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra">
                    Guardar precios
                </button>
                <button type="button" wire:click="agregarTarifa" class="text-sm text-winay-terracota hover:underline">+ Agregar fila</button>
                @if ($tarifas)
                    <button type="button" wire:click="quitarTarifa" class="text-sm text-stone-500 hover:text-stone-700">Quitar última</button>
                @endif
                @if (session('tarifas'))
                    <span class="text-sm text-winay-andino">{{ session('tarifas') }}</span>
                @endif
            </div>
        </form>
    </div>
</div>
