<div>
    @include('livewire.admin.partials.banner-exito')

    <h1 class="text-2xl font-bold text-winay-tierra mb-6">Nosotros</h1>

    <form wire:submit="guardar" class="space-y-8">
        <div class="bg-white rounded-2xl border border-stone-200 p-6 space-y-5">
            <h2 class="font-semibold text-winay-tierra">Nuestra historia</h2>

            @include('livewire.admin.partials.campo-traducible', ['prop' => 'tituloHistoria', 'label' => 'Título', 'tipo' => 'input'])
            @include('livewire.admin.partials.campo-richtext', ['prop' => 'historia', 'label' => 'Texto'])
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 p-6 space-y-5">
            <h2 class="font-semibold text-winay-tierra">Nuestro mensaje</h2>

            @include('livewire.admin.partials.campo-traducible', ['prop' => 'tituloMensaje', 'label' => 'Título', 'tipo' => 'input'])
            @include('livewire.admin.partials.campo-richtext', ['prop' => 'mensaje', 'label' => 'Texto'])
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 p-6">
            <h2 class="font-semibold text-winay-tierra mb-4">Fotos</h2>
            @include('livewire.admin.partials.galeria-editor')
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra"
                    wire:loading.attr="disabled" wire:target="guardar">
                Guardar
            </button>
        </div>
    </form>
</div>
