@php($tipo ??= 'input')
@php($generaSlug ??= false)

<div x-data="{ tab: 'es' }">
    <div class="flex items-center justify-between">
        <label class="block text-sm font-medium text-stone-700">{{ $label }}</label>

        <button type="button" wire:click="traducir('{{ $prop }}')" wire:loading.attr="disabled" wire:target="traducir('{{ $prop }}')"
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
            </button>
        @endforeach
    </div>

    @foreach (['es', 'en', 'fr'] as $locale)
        <div x-show="tab === '{{ $locale }}'" class="mt-2">
            @if ($tipo === 'textarea')
                <textarea wire:model="{{ $prop }}.{{ $locale }}" rows="4"
                          class="block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota"></textarea>
            @else
                <input type="text"
                       @if ($generaSlug && $locale === 'es')
                           wire:model.live.debounce.500ms="{{ $prop }}.{{ $locale }}"
                       @else
                           wire:model="{{ $prop }}.{{ $locale }}"
                       @endif
                       class="block w-full rounded-lg border-stone-300 focus:border-winay-terracota focus:ring-winay-terracota">
            @endif
            @error("{$prop}.{$locale}") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    @endforeach

    @if ($errorTraduccion ?? false)
        <p class="mt-1 text-xs text-amber-600">{{ $errorTraduccion }}</p>
    @endif
</div>
