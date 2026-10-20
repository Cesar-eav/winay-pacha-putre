@php
    $items = $this->itemsGaleria();
    $maximo = $this->maximoFotosGaleria();
    $conPrincipal = $this->usaImagenPrincipal();
    $disponibles = max(0, $maximo - count($items));
@endphp

<div x-data="{
        maximo: {{ $maximo }},
        extensiones: ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'heic', 'heif'],
        pesoMaximo: 25 * 1024 * 1024,
        rechazadas: [],
        subiendo: false,
        progreso: '',
        porcentaje: 0,
        rechazar(archivo, motivo) {
            this.rechazadas.push({ nombre: archivo.name, motivo })
        },
        async seleccionar(evento) {
            let archivos = Array.from(evento.target.files)
            evento.target.value = ''
            this.rechazadas = []
            this.$wire.$set('erroresFotos', [], false)

            let disponibles = this.maximo - this.$wire.ordenGaleria.length
            let aceptados = []

            for (let archivo of archivos) {
                let extension = archivo.name.split('.').pop().toLowerCase()

                if (! this.extensiones.includes(extension)) {
                    this.rechazar(archivo, 'Formato no permitido. Usa JPG, PNG, WebP o HEIC.')
                } else if (archivo.size > this.pesoMaximo) {
                    this.rechazar(archivo, `Pesa ${(archivo.size / 1048576).toFixed(1)} MB; el máximo es 25 MB.`)
                } else if (aceptados.length >= disponibles) {
                    this.rechazar(archivo, `Se alcanzó el máximo de ${this.maximo} imágenes.`)
                } else {
                    aceptados.push(archivo)
                }
            }

            if (! aceptados.length) return

            this.subiendo = true
            this.$dispatch('galeria-subiendo', true)

            for (let [i, archivo] of aceptados.entries()) {
                this.progreso = `Subiendo ${i + 1} de ${aceptados.length}: ${archivo.name}`
                this.porcentaje = 0

                await new Promise((listo) => this.$wire.$upload('nuevasFotos', archivo, listo, () => {
                    this.rechazar(archivo, 'El servidor no aceptó el archivo (puede ser demasiado pesado o estar dañado).')
                    listo()
                }, (e) => this.porcentaje = e.detail.progress))
            }

            this.subiendo = false
            this.$dispatch('galeria-subiendo', false)
        },
     }">
    <div class="flex items-baseline justify-between gap-3">
        <label class="block text-sm font-medium text-stone-700">{{ $etiqueta ?? 'Fotos' }}</label>
        <span class="text-xs {{ $disponibles === 0 ? 'font-semibold text-winay-terracota' : 'text-stone-500' }}">
            {{ count($items) }} / {{ $maximo }} imágenes
        </span>
    </div>

    @if (count($items))
        <p class="mt-1 text-xs text-stone-500">
            Arrastra las imágenes para cambiar el orden.
            @if ($conPrincipal) Toca una imagen para dejarla como principal (portada). @endif
        </p>

        <div wire:sort="ordenarGaleria" wire:sort:config="{ delay: 200, delayOnTouchOnly: true }"
             class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach ($items as $item)
                @php $esPrincipal = $conPrincipal && $item['clave'] === $clavePrincipal; @endphp
                <div wire:key="foto-{{ $item['clave'] }}" wire:sort:item="'{{ $item['clave'] }}'" class="cursor-grab active:cursor-grabbing">
                    <div @if ($conPrincipal) wire:click="marcarPrincipal('{{ $item['clave'] }}')" @endif
                         title="{{ $item['nombre'] }}"
                         class="relative rounded-lg overflow-hidden aspect-square select-none [-webkit-touch-callout:none]
                                {{ $esPrincipal ? 'ring-3 ring-winay-terracota ring-offset-2' : ($item['nueva'] ? 'border border-winay-terracota/60' : 'border border-stone-200') }}">
                        @if ($item['url'])
                            <img src="{{ $item['url'] }}" alt="" draggable="false" class="w-full h-full object-cover pointer-events-none">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-stone-100 text-center px-2">
                                <span class="text-xs text-stone-500 break-all">{{ $item['nombre'] }}<br>Sin vista previa</span>
                            </div>
                        @endif

                        @if ($esPrincipal)
                            <span class="absolute top-1 left-1 bg-winay-terracota text-white text-[10px] font-semibold px-1.5 py-0.5 rounded">★ Principal</span>
                        @elseif ($item['nueva'])
                            <span class="absolute top-1 left-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded">Nueva</span>
                        @endif

                        <button type="button" wire:sort:ignore wire:click.stop="eliminarFoto('{{ $item['clave'] }}')"
                                @unless ($item['nueva']) onclick="return confirm('¿Eliminar esta foto?')" @endunless
                                class="absolute top-1 right-1 bg-black/60 text-white text-xs w-6 h-6 rounded-full hover:text-red-300"
                                title="Eliminar">✕</button>
                    </div>
                    <input type="text" wire:sort:ignore wire:model.blur="altsGaleria.{{ $item['clave'] }}" placeholder="Texto alternativo"
                           class="mt-1 w-full text-xs rounded border-stone-200 focus:border-winay-terracota focus:ring-winay-terracota">
                </div>
            @endforeach
        </div>
    @else
        <div class="mt-2 rounded-lg border border-dashed border-stone-300 px-4 py-6 text-center text-sm text-stone-400">
            Aún no hay imágenes.
        </div>
    @endif

    <div class="mt-3">
        @if ($disponibles === 0)
            <div class="rounded-lg bg-amber-50 border border-amber-200 text-amber-800 px-3 py-2 text-sm">
                Llegaste al máximo de {{ $maximo }} imágenes. Elimina alguna para poder subir otras.
            </div>
        @else
            <input type="file" multiple accept="image/*,.heic,.heif" x-on:change="seleccionar($event)" x-bind:disabled="subiendo"
                   class="block w-full text-sm text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-winay-arena file:text-winay-tierra file:text-sm file:font-semibold hover:file:bg-winay-arena/70 disabled:opacity-50">
            <p class="mt-1 text-xs text-stone-500">
                JPG, PNG, WebP o HEIC de iPhone (se convierten automáticamente), hasta 25 MB cada una.
                Puedes subir {{ $disponibles }} {{ $disponibles === 1 ? 'imagen' : 'imágenes' }} más.
            </p>
        @endif

        <div x-show="subiendo" x-cloak class="mt-2 flex items-center gap-1.5 text-xs text-stone-500">
            <svg class="animate-spin h-3.5 w-3.5 text-winay-terracota shrink-0" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span x-text="progreso" class="truncate"></span>
            <span x-text="porcentaje + '%'" class="tabular-nums"></span>
        </div>

        <div class="hidden has-[li]:block mt-3 rounded-lg bg-red-50 border border-red-200 px-3 py-2 text-sm text-red-700">
            <p class="font-semibold">Estas imágenes no se subieron:</p>
            <ul class="mt-1 space-y-0.5">
                @foreach ($erroresFotos as $error)
                    <li><span class="font-medium break-all">{{ $error['nombre'] }}</span> — {{ $error['motivo'] }}</li>
                @endforeach
                <template x-for="(rechazo, i) in rechazadas" :key="i">
                    <li><span class="font-medium break-all" x-text="rechazo.nombre"></span> — <span x-text="rechazo.motivo"></span></li>
                </template>
            </ul>
        </div>

        @error('galeria') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
