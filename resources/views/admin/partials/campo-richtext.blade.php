<div x-data="{ tab: 'es' }">
    <div class="flex items-center justify-between">
        <label class="block text-sm font-medium text-stone-700">{{ $label }}</label>

        <button type="button" wire:click="traducir('{{ $prop }}')"
                @if (filled(data_get($this, "{$prop}.en")) || filled(data_get($this, "{$prop}.fr")))
                    wire:confirm="Esto reemplaza el texto en inglés y francés por la traducción de DeepL. ¿Continuar?"
                @endif
                wire:loading.attr="disabled" wire:target="traducir('{{ $prop }}')"
                class="text-xs font-semibold text-winay-terracota hover:text-winay-tierra disabled:opacity-50">
            <span wire:loading.remove wire:target="traducir('{{ $prop }}')">Traducir con DeepL →</span>
            <span wire:loading wire:target="traducir('{{ $prop }}')">Traduciendo…</span>
        </button>
    </div>

    <div class="mt-1 flex gap-1 border-b border-stone-200">
        @foreach (['es' => 'Español', 'en' => 'English', 'fr' => 'Français'] as $locale => $nombreLocale)
            <button type="button" @click="tab = '{{ $locale }}'"
                    :class="tab === '{{ $locale }}' ? 'border-winay-terracota text-winay-terracota' : 'border-transparent text-stone-500'"
                    class="px-3 py-1.5 text-xs font-medium border-b-2 -mb-px">
                {{ $nombreLocale }}
                @error("{$prop}.{$locale}")
                    <span class="ml-1 inline-block size-1.5 rounded-full bg-red-600 align-middle" title="Esta pestaña tiene un error"></span>
                @enderror
            </button>
        @endforeach
    </div>

    @foreach (['es', 'en', 'fr'] as $locale)
        <div x-show="tab === '{{ $locale }}'" x-cloak class="mt-2">
            <div wire:ignore x-data="richtextEditor(@entangle($prop.'.'.$locale))" class="bg-white rounded-lg">
                <div x-ref="editor"></div>
            </div>
            @error("{$prop}.{$locale}") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    @endforeach

    @if ($errorTraduccion[$prop] ?? false)
        <p class="mt-1 text-xs text-amber-600">{{ $errorTraduccion[$prop] }}</p>
    @endif
</div>
