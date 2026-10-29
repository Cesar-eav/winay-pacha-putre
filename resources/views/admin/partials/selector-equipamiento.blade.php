@use('App\Support\CatalogoEquipamiento')

@php
    $seleccionados = collect($equipamientosSeleccionados)->map(fn ($id) => (int) $id);
@endphp

<div x-data="{
        busqueda: '',
        agregando: false,
        version: 0,
        palabras: @js(CatalogoEquipamiento::PALABRAS),
        normalizar(texto) {
            return (texto ?? '').normalize('NFD').replace(/\p{Diacritic}/gu, '').toLowerCase().trim()
        },
        get termino() {
            return this.normalizar(this.busqueda)
        },
        coincide(texto) {
            return this.termino.split(/\s+/).every(parte => texto.includes(parte))
        },
        marcado(id) {
            return this.$wire.equipamientosSeleccionados.map(String).includes(String(id))
        },
        desmarcar(id) {
            this.$wire.equipamientosSeleccionados = this.$wire.equipamientosSeleccionados.filter(i => String(i) !== String(id))
        },
        items(contenedor) {
            this.version
            return contenedor ? [...contenedor.querySelectorAll('[data-texto]')] : []
        },
        get marcadosLista() {
            return this.items(this.$refs.lista)
                .filter(item => this.marcado(item.dataset.id))
                .map(item => ({ id: item.dataset.id, nombre: item.dataset.nombre, icono: item.dataset.icono }))
        },
        visibles(contenedor) {
            return this.items(contenedor).filter(item => this.coincide(item.dataset.texto)).length
        },
        marcadosEn(contenedor) {
            return this.items(contenedor).filter(item => this.marcado(item.dataset.id)).length
        },
        get sinResultados() {
            return this.termino !== '' && this.visibles(this.$refs.lista) === 0
        },
        get iconoNuevo() {
            return this.$wire.nuevoIcono ?? this.sugerirIcono(this.$wire.nuevoEquipamiento)
        },
        sugerirIcono(nombre) {
            let texto = this.normalizar(nombre)
            for (let [palabra, icono] of Object.entries(this.palabras)) {
                let patron = palabra.endsWith('*') ? '\\b' + palabra.slice(0, -1) : '\\b' + palabra + '(s|es)?\\b'
                if (new RegExp(patron).test(texto)) return icono
            }
            return 'check'
        },
        abrirAgregar() {
            this.$wire.nuevoEquipamiento = this.busqueda.trim()
            this.agregando = true
            this.$nextTick(() => this.$refs.nombreNuevo.focus())
        },
     }"
     x-init="$nextTick(() => version++); $wire.$hook('morphed', () => version++)"
     x-on:equipamiento-agregado.window="busqueda = ''; agregando = false">

    <p class="block text-sm font-medium text-stone-700">Equipamiento</p>
    <p class="mt-1 text-sm text-stone-500">
        Toca cada cosa que tiene esta {{ Str::lower($etiquetas['singular']) }}. Lo marcado aparece en su página, con su dibujo.
    </p>

    <div class="mt-3 rounded-xl bg-winay-arena/60 p-3">
        <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">
            Marcado (<span x-text="$wire.equipamientosSeleccionados.length">{{ $seleccionados->count() }}</span>)
        </p>
        <div class="mt-2 flex flex-wrap gap-2">
            <template x-for="item in marcadosLista" :key="item.id">
                <button type="button" x-on:click="desmarcar(item.id)" title="Quitar"
                        class="inline-flex items-center gap-1.5 rounded-full border border-winay-terracota/30 bg-white py-1 pl-2 pr-1.5 text-sm text-winay-tierra hover:border-winay-terracota">
                    <svg class="size-4 shrink-0 text-winay-terracota" aria-hidden="true"><use :href="'#icono-equipamiento-' + item.icono"/></svg>
                    <span x-text="item.nombre"></span>
                    <svg class="size-3.5 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    <span class="sr-only">Quitar</span>
                </button>
            </template>
            <p x-show="$wire.equipamientosSeleccionados.length === 0" class="text-sm text-stone-500" @if ($seleccionados->isNotEmpty()) style="display: none" @endif>
                Todavía no marcaste nada.
            </p>
        </div>
        @if ($avisoEquipamiento)
            <p class="mt-2 text-sm text-winay-andino">{{ $avisoEquipamiento }}</p>
        @endif
    </div>

    <div class="relative mt-4">
        <svg class="pointer-events-none absolute left-3.5 top-1/2 size-5 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="search" x-model="busqueda" x-on:keydown.enter.prevent="sinResultados && abrirAgregar()"
               placeholder="Buscar, por ejemplo: toallas, estufa, wifi"
               aria-label="Buscar equipamiento"
               class="block w-full rounded-full border-stone-300 py-3 pl-11 pr-4 text-base focus:border-winay-terracota focus:ring-winay-terracota">
    </div>

    <div x-show="sinResultados" style="display: none" class="mt-3 rounded-xl border border-dashed border-stone-300 p-4 text-sm text-stone-600">
        No encontramos «<span x-text="busqueda.trim()" class="font-medium text-stone-800"></span>» en la lista.
        <button type="button" x-show="!agregando" x-on:click="abrirAgregar()"
                class="mt-2 flex items-center gap-2 rounded-full bg-winay-terracota px-4 py-2 font-semibold text-white hover:bg-winay-tierra">
            + Agregarlo como equipamiento nuevo
        </button>
    </div>

    <div class="mt-4 space-y-2" x-ref="lista">
        @foreach ($equipamientosPorCategoria as $categoria => $items)
            <section wire:key="categoria-{{ $categoria }}"
                     x-data="{ abierta: @js($items->pluck('id')->intersect($seleccionados)->isNotEmpty()) }"
                     x-show="termino === '' || visibles($root) > 0"
                     class="rounded-xl border border-stone-200">
                <button type="button" x-on:click="abierta = !abierta" :aria-expanded="(abierta || termino !== '').toString()"
                        class="flex w-full items-center gap-3 px-4 py-3 text-left">
                    <span class="min-w-0 flex-1 font-medium text-winay-tierra">{{ CatalogoEquipamiento::categoriaNombre($categoria, 'es') }}</span>
                    <span x-show="marcadosEn($root) > 0"
                          class="rounded-full bg-winay-terracota/10 px-2 py-0.5 text-xs font-semibold text-winay-terracota"
                          x-text="marcadosEn($root) === 1 ? '1 marcado' : marcadosEn($root) + ' marcados'"></span>
                    <span class="hidden text-xs text-stone-500 sm:inline" x-show="termino === ''">{{ $items->count() }} opciones</span>
                    <svg class="size-5 text-stone-400 transition-transform" :class="(abierta || termino !== '') && 'rotate-180'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div x-show="abierta || termino !== ''" @if ($items->pluck('id')->intersect($seleccionados)->isEmpty()) style="display: none" @endif
                     class="grid gap-2 border-t border-stone-100 p-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($items as $item)
                        <div wire:key="equipamiento-{{ $item->id }}" data-id="{{ $item->id }}" data-texto="{{ CatalogoEquipamiento::textoBusqueda($item) }}"
                             data-nombre="{{ $item->getTranslation('nombre', 'es') }}" data-icono="{{ $item->icono }}"
                             x-show="coincide($el.dataset.texto)" class="relative">
                            <label class="flex min-h-14 h-full cursor-pointer select-none items-center gap-3 rounded-xl border border-stone-200 bg-white px-3 py-2 text-sm text-stone-700 hover:border-winay-terracota/50 has-checked:border-winay-terracota has-checked:bg-winay-terracota/10 has-checked:text-winay-tierra has-focus-visible:ring-2 has-focus-visible:ring-winay-terracota">
                                <input type="checkbox" value="{{ $item->id }}" wire:model="equipamientosSeleccionados" class="peer sr-only">
                                <x-icono-equipamiento sprite :equipamiento="$item" class="size-6 text-stone-400 peer-checked:text-winay-terracota" />
                                <span class="min-w-0 flex-1 break-words leading-snug {{ $item->personalizado ? 'pr-6' : '' }}">
                                    {{ $item->getTranslation('nombre', 'es') }}
                                    @if ($item->personalizado)
                                        <span class="block text-xs text-stone-400">agregado por ti</span>
                                    @endif
                                </span>
                                <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-winay-terracota text-white peer-checked:flex" aria-hidden="true">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                </span>
                            </label>
                            @if ($item->personalizado)
                                <button type="button" wire:click="eliminarEquipamiento({{ $item->id }})"
                                        wire:confirm="¿Quitar «{{ $item->getTranslation('nombre', 'es') }}» de la lista? Se quitará de todas las cabañas y habitaciones."
                                        class="absolute right-1.5 top-1.5 rounded-full p-1 text-stone-400 hover:bg-red-50 hover:text-red-600"
                                        title="Quitar de la lista">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    <span class="sr-only">Quitar de la lista</span>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <button type="button" x-show="!agregando" x-on:click="abrirAgregar()" class="mt-3 text-sm font-medium text-winay-terracota hover:underline">
        ¿Falta algo? Agregar equipamiento nuevo
    </button>

    <div x-show="agregando" style="display: none" class="mt-4 rounded-xl border-2 border-winay-terracota/30 bg-winay-arena/40 p-4">
        <p class="font-medium text-winay-tierra">Agregar equipamiento nuevo</p>
        <p class="mt-1 text-sm text-stone-500">Quedará en la lista para todas las cabañas y habitaciones, y se traducirá solo al inglés y francés.</p>

        <div class="mt-3 grid gap-4 sm:grid-cols-2">
            <div>
                <label for="nuevo-equipamiento" class="block text-sm font-medium text-stone-700">¿Qué es?</label>
                <input id="nuevo-equipamiento" type="text" x-ref="nombreNuevo" maxlength="60"
                       wire:model="nuevoEquipamiento"
                       x-on:keydown.enter.prevent="$wire.agregarEquipamiento()"
                       placeholder="Ej: Máquina de café de cápsulas"
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                @error('nuevoEquipamiento') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="nueva-categoria" class="block text-sm font-medium text-stone-700">¿En qué grupo va?</label>
                <select id="nueva-categoria" wire:model="nuevaCategoria" class="mt-1 block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
                    @foreach (array_keys(CatalogoEquipamiento::CATEGORIAS) as $clave)
                        <option value="{{ $clave }}">{{ CatalogoEquipamiento::categoriaNombre($clave, 'es') }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4">
            <p class="text-sm font-medium text-stone-700">Dibujo</p>
            <div class="mt-1 flex flex-wrap items-center gap-3">
                <span class="flex size-12 items-center justify-center rounded-xl border border-winay-terracota bg-white text-winay-terracota">
                    <svg class="size-7 shrink-0" aria-hidden="true"><use :href="'#icono-equipamiento-' + iconoNuevo"/></svg>
                </span>
                <span class="text-sm text-stone-500"
                      x-text="$wire.nuevoIcono === null ? 'Elegido automáticamente según el nombre.' : ($wire.nuevoIcono === 'check' ? 'Sin dibujo (se muestra un visto).' : 'Elegido por ti.')"></span>
                <button type="button" wire:click="$toggle('eligiendoIcono')" class="text-sm font-medium text-winay-terracota hover:underline">
                    {{ $eligiendoIcono ? 'Cerrar dibujos' : 'Elegir otro dibujo' }}
                </button>
            </div>

            @if ($eligiendoIcono)
                <div class="mt-3 rounded-xl border border-stone-200 bg-white p-3">
                    <div class="flex flex-wrap gap-2">
                        <button type="button" x-on:click="$wire.nuevoIcono = null"
                                class="rounded-full border px-3 py-1.5 text-sm"
                                :class="$wire.nuevoIcono === null ? 'border-winay-terracota bg-winay-terracota/10 text-winay-tierra' : 'border-stone-300 text-stone-600 hover:border-winay-terracota'">
                            Automático
                        </button>
                        <button type="button" x-on:click="$wire.nuevoIcono = 'check'"
                                class="rounded-full border px-3 py-1.5 text-sm"
                                :class="$wire.nuevoIcono === 'check' ? 'border-winay-terracota bg-winay-terracota/10 text-winay-tierra' : 'border-stone-300 text-stone-600 hover:border-winay-terracota'">
                            Sin dibujo
                        </button>
                    </div>
                    <div class="mt-3 grid grid-cols-[repeat(auto-fill,minmax(2.75rem,1fr))] gap-1.5">
                        @foreach (array_keys(\App\Support\IconosEquipamiento::TRAZOS) as $icono)
                            @continue($icono === 'check')
                            <button type="button" wire:key="icono-{{ $icono }}" x-on:click="$wire.nuevoIcono = '{{ $icono }}'"
                                    class="flex aspect-square items-center justify-center rounded-lg border"
                                    :class="$wire.nuevoIcono === '{{ $icono }}' ? 'border-winay-terracota bg-winay-terracota/10 text-winay-terracota' : 'border-stone-200 text-stone-500 hover:border-winay-terracota hover:text-winay-terracota'">
                                <x-icono-equipamiento sprite :icono="$icono" class="size-6" />
                                <span class="sr-only">{{ $icono }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-4 flex flex-wrap items-center gap-3">
            <button type="button" wire:click="agregarEquipamiento" wire:loading.attr="disabled" wire:target="agregarEquipamiento"
                    class="inline-flex items-center gap-2 rounded-full bg-winay-terracota px-5 py-2.5 text-sm font-semibold text-white hover:bg-winay-tierra disabled:cursor-wait disabled:opacity-70">
                <x-spinner wire:loading wire:target="agregarEquipamiento" />
                <span wire:loading.remove wire:target="agregarEquipamiento">Agregar y marcar</span>
                <span wire:loading wire:target="agregarEquipamiento">Agregando…</span>
            </button>
            <button type="button" x-on:click="agregando = false" class="text-sm text-stone-500 hover:text-stone-700">
                Cancelar
            </button>
        </div>
    </div>
</div>
