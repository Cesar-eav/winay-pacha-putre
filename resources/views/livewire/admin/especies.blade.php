<div>
    @include('livewire.admin.partials.banner-exito')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-winay-tierra">Qué Visitar — Flora y Fauna</h1>
        <button type="button" wire:click="nuevo" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra">
            + Nueva especie
        </button>
    </div>

    @if ($mostrarFormulario)
        <div class="mb-8 bg-white rounded-2xl border border-stone-200 p-6">
            <h2 class="font-semibold text-winay-tierra mb-4">{{ $editandoId ? 'Editar especie' : 'Nueva especie' }}</h2>

            <form wire:submit="guardar" class="space-y-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Nombre común</label>
                        <input type="text" wire:model="nombreComun" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                        @error('nombreComun') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Nombre científico (opcional)</label>
                        <input type="text" wire:model="nombreCientifico" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota italic">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700">Tipo</label>
                    <select wire:model="tipo" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                        <option value="ave">Ave</option>
                        <option value="mamifero">Mamífero</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                @include('livewire.admin.partials.campo-traducible', ['prop' => 'descripcion', 'label' => 'Descripción', 'tipo' => 'textarea'])
                @include('livewire.admin.partials.campo-traducible', ['prop' => 'dondeObservar', 'label' => 'Dónde observarla', 'tipo' => 'textarea'])

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
                    <label class="block text-sm font-medium text-stone-700">Foto</label>

                    <div class="mt-2 flex items-center gap-4">
                        @if ($nuevaFoto)
                            <div class="relative w-24 h-24 rounded-lg overflow-hidden border border-winay-terracota">
                                <img src="{{ $nuevaFoto->temporaryUrl() }}" class="w-full h-full object-cover">
                                <span class="absolute top-1 left-1 bg-winay-terracota text-white text-[10px] px-1.5 py-0.5 rounded">Nueva</span>
                            </div>
                        @elseif ($imagenActual)
                            <div class="relative w-24 h-24 rounded-lg overflow-hidden border border-stone-200">
                                <img src="{{ str_starts_with($imagenActual, 'placeholder/') ? asset('images/'.$imagenActual) : asset('storage/'.$imagenActual) }}" class="w-full h-full object-cover">
                                <button type="button" wire:click="quitarFoto" onclick="return confirm('¿Quitar esta foto?')"
                                        class="absolute bottom-1 right-1 bg-black/60 text-white text-xs px-1.5 py-0.5 rounded hover:text-red-300">
                                    ✕
                                </button>
                            </div>
                        @else
                            <div class="w-24 h-24 rounded-lg border border-dashed border-stone-300 flex items-center justify-center text-xs text-stone-400 text-center px-1">
                                Sin foto
                            </div>
                        @endif

                        <div class="flex-1">
                            <input type="file" wire:model="nuevaFoto" accept="image/*"
                                   class="block w-full text-sm text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-winay-arena file:text-winay-tierra file:text-sm file:font-semibold hover:file:bg-winay-arena/70">
                            <p class="mt-1 text-xs text-stone-500">Formatos aceptados: JPG, PNG, WebP o GIF. Sin foto se usa una ilustración genérica según el tipo.</p>
                            <div wire:loading wire:target="nuevaFoto" class="mt-1 text-xs text-stone-500">Subiendo…</div>
                            @error('nuevaFoto') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

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
                    <th class="text-left px-4 py-3">Nombre común</th>
                    <th class="text-left px-4 py-3">Tipo</th>
                    <th class="text-left px-4 py-3">Orden</th>
                    <th class="text-left px-4 py-3">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($especies as $especie)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ $especie->imagen_url }}" alt="{{ $especie->nombre_comun }}" class="w-10 h-10 rounded-lg object-cover">
                        </td>
                        <td class="px-4 py-3">{{ $especie->nombre_comun }}</td>
                        <td class="px-4 py-3 text-stone-500 capitalize">{{ $especie->tipo }}</td>
                        <td class="px-4 py-3">{{ $especie->orden }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $especie->publicado ? 'bg-winay-andino/10 text-winay-andino' : 'bg-stone-100 text-stone-500' }}">
                                {{ $especie->publicado ? 'Publicado' : 'Borrador' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <button type="button" wire:click="editar({{ $especie->id }})" class="text-winay-terracota hover:underline">Editar</button>
                            <button type="button" wire:click="eliminar({{ $especie->id }})" onclick="return confirm('¿Eliminar esta especie?')" class="text-red-600 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-stone-500">No hay especies todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
