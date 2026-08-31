@props([
    'label' => 'Imagen pendiente',
    'color' => 'tierra',
])

@php
    $fill = match ($color) {
        'terracota' => '#b5502f',
        'andino' => '#2f4a5c',
        'arena' => '#f4ede2',
        default => '#6b4423',
    };
    $textColor = $color === 'arena' ? '#6b4423' : '#f4ede2';
@endphp

<svg {{ $attributes->merge(['class' => 'w-full h-full']) }} viewBox="0 0 800 500" preserveAspectRatio="xMidYMid slice" role="img" aria-label="{{ $label }}">
    <rect width="800" height="500" fill="{{ $fill }}" />
    <text x="400" y="250" text-anchor="middle" dominant-baseline="middle" fill="{{ $textColor }}" font-family="sans-serif" font-size="28" opacity="0.85">
        {{ $label }}
    </text>
</svg>
