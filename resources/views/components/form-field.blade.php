@props(['name', 'label'])

@php $hasError = $errors->has($name); @endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-stone-700">{{ $label }}</label>
    <input id="{{ $name }}" wire:model="{{ $name }}"
        @if ($hasError) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        {{ $attributes->merge([
            'class' => 'mt-1 block w-full rounded-lg focus:ring-winay-terracota '
                . ($hasError ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-winay-terracota'),
        ]) }}>
    @error($name)
        <p id="{{ $name }}-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
