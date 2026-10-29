{{-- Fuera del componente Livewire para que no viaje en cada respuesta: el selector de equipamiento usa <x-icono-equipamiento sprite>. --}}
<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden">
    @foreach (\App\Support\IconosEquipamiento::TRAZOS as $clave => $trazos)
        <symbol id="icono-equipamiento-{{ $clave }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $trazos !!}</symbol>
    @endforeach
</svg>
