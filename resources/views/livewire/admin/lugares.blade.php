<div>
    @include('livewire.admin.partials.banner-exito')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-winay-tierra">Qué Visitar — Lugares</h1>
        <button type="button" wire:click="nuevo" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra">
            + Nuevo lugar
        </button>
    </div>

    @if ($mostrarFormulario)
        <div class="mb-8 bg-white rounded-2xl border border-stone-200 p-6">
            <h2 class="font-semibold text-winay-tierra mb-4">{{ $editandoId ? 'Editar lugar' : 'Nuevo lugar' }}</h2>

            <form wire:submit="guardar" class="space-y-5">
                <div class="grid gap-4 sm:grid-cols-2">
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

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Icono (opcional)</label>
                        <input type="text" wire:model="icono" placeholder="Ej: montania, agua-termal" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Ubicación (texto libre)</label>
                        <input type="text" wire:model="ubicacionTexto" placeholder="Ej: 40 km al este de Putre" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    </div>
                </div>

                @include('livewire.admin.partials.campo-traducible', ['prop' => 'descripcion', 'label' => 'Descripción', 'tipo' => 'textarea'])

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

                @include('livewire.admin.partials.galeria-editor')

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
                    <th class="text-left px-4 py-3">Nombre</th>
                    <th class="text-left px-4 py-3">Ubicación</th>
                    <th class="text-left px-4 py-3">Orden</th>
                    <th class="text-left px-4 py-3">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($lugares as $lugar)
                    <tr>
                        <td class="px-4 py-3">{{ $lugar->nombre }}</td>
                        <td class="px-4 py-3 text-stone-500">{{ $lugar->ubicacion_texto }}</td>
                        <td class="px-4 py-3">{{ $lugar->orden }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $lugar->publicado ? 'bg-winay-andino/10 text-winay-andino' : 'bg-stone-100 text-stone-500' }}">
                                {{ $lugar->publicado ? 'Publicado' : 'Borrador' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <button type="button" wire:click="editar({{ $lugar->id }})" class="text-winay-terracota hover:underline">Editar</button>
                            <button type="button" wire:click="eliminar({{ $lugar->id }})" onclick="return confirm('¿Eliminar este lugar?')" class="text-red-600 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-stone-500">No hay lugares todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
