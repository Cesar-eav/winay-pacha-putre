@php
    $rutaActual = request()->route();
    $nombreBase = preg_replace('/^(en|fr)\./', '', $rutaActual->getName());
    $params = $rutaActual->parameters();
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    @foreach (config('winay.locales') as $codigo => $label)
        <a href="{{ lroute($nombreBase, $params, $codigo) }}"
           lang="{{ $codigo }}"
           title="{{ $label }}"
           class="text-xs font-semibold {{ app()->getLocale() === $codigo ? 'text-winay-terracota' : 'text-stone-400 hover:text-winay-terracota' }}">
            {{ strtoupper($codigo) }}
        </a>
    @endforeach
</div>
