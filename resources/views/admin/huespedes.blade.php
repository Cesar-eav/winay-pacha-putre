<div>
    @include('admin.partials.banner-exito')

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-winay-tierra">Fotos de huéspedes</h1>
        <p class="mt-1 text-sm text-stone-500">Se muestran en el inicio, en la sección "Quienes ya vivieron Wiñaypacha", en el orden de esta galería. Si no hay fotos, la sección no aparece.</p>
    </div>

    <details class="mb-6 bg-winay-andino/5 border border-winay-andino/20 rounded-2xl p-5 text-sm text-stone-700">
        <summary class="font-semibold text-winay-andino cursor-pointer">Recomendaciones para que las fotos se vean bien</summary>
        <ul class="mt-3 space-y-2 list-disc pl-5 marker:text-winay-andino">
            <li><strong>Formato vertical:</strong> cada foto se muestra como un rectángulo vertical (3:4). Lo ideal es <strong>1200 × 1600 px</strong>. Las fotos horizontales se recortan a los costados.</li>
            <li><strong>Tamaño mínimo:</strong> {{ \App\Livewire\Admin\Huespedes::ANCHO_MINIMO }} px de ancho; las más chicas se rechazan porque se verían borrosas.</li>
            <li><strong>Composición:</strong> deja a las personas al centro y con algo de espacio sobre la cabeza, para que no queden cortadas.</li>
            <li><strong>Cantidad:</strong> entre 8 y 16 fotos. En computador se ven 4 a la vez y el resto se recorre con las flechas.</li>
            <li><strong>Texto alternativo:</strong> describe brevemente la foto (por ejemplo "Huéspedes en el mirador de Putre"). No se ve en la página, pero lo usan lectores de pantalla y buscadores.</li>
            <li><strong>Permiso:</strong> usa solo fotos de huéspedes que hayan aceptado aparecer en el sitio.</li>
            <li><strong>Formato de archivo:</strong> JPG, PNG, WebP o HEIC (iPhone), hasta 25 MB cada una. El sitio las achica y optimiza solo.</li>
        </ul>
    </details>

    <form wire:submit="guardar" class="bg-white rounded-2xl border border-stone-200 p-6 space-y-5">
        @include('admin.partials.galeria-editor', ['etiqueta' => 'Fotos de huéspedes'])

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra disabled:opacity-70 disabled:cursor-wait"
                    wire:loading.attr="disabled" wire:target="guardar,nuevasFotos">
                <x-spinner wire:loading wire:target="guardar" />
                <span wire:loading.remove wire:target="guardar">Guardar cambios</span>
                <span wire:loading wire:target="guardar">Guardando…</span>
            </button>
            <p class="text-xs text-stone-500">Las fotos nuevas, el orden y las eliminaciones se aplican al guardar.</p>
        </div>
    </form>
</div>
