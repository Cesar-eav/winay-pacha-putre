@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-winay-terracota focus:ring-winay-terracota rounded-md shadow-sm']) }}>
