@php
    $rutaActual = request()->route();
    $nombreBase = preg_replace('/^(en|fr)\./', '', $rutaActual->getName());
    $params = $rutaActual->parameters();
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    @foreach (config('winay.locales') as $codigo => $label)
        @php $activo = app()->getLocale() === $codigo; @endphp
        <a href="{{ lroute($nombreBase, $params, $codigo) }}"
           lang="{{ $codigo }}"
           title="{{ $label }}"
           aria-label="{{ $label }}"
           @if ($activo) aria-current="true" @endif
           class="block rounded-sm transition {{ $activo ? 'opacity-100 ring-2 ring-winay-terracota ring-offset-1' : 'opacity-60 hover:opacity-100' }}">
            <svg viewBox="0 0 30 20" class="block h-4 w-6 rounded-sm" aria-hidden="true">
                @switch($codigo)
                    @case('es')
                        <rect width="30" height="20" fill="#aa151b"/>
                        <rect y="5" width="30" height="10" fill="#f1bf00"/>
                        @break
                    @case('en')
                        <rect width="30" height="20" fill="#012169"/>
                        <path d="M0 0l30 20M30 0L0 20" stroke="#fff" stroke-width="4"/>
                        <path d="M0 0l30 20M30 0L0 20" stroke="#c8102e" stroke-width="1.5"/>
                        <path d="M15 0v20M0 10h30" stroke="#fff" stroke-width="6.5"/>
                        <path d="M15 0v20M0 10h30" stroke="#c8102e" stroke-width="4"/>
                        @break
                    @case('fr')
                        <rect width="10" height="20" fill="#0055a4"/>
                        <rect x="10" width="10" height="20" fill="#fff"/>
                        <rect x="20" width="10" height="20" fill="#ef4135"/>
                        @break
                @endswitch
            </svg>
        </a>
    @endforeach
</div>
