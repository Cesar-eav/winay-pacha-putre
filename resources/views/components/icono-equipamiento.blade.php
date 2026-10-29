@props(['equipamiento' => null, 'icono' => null, 'sprite' => false])

@php
    $trazos = \App\Support\IconosEquipamiento::TRAZOS;
    $clave = $icono ?? $equipamiento?->icono;
    $clave = isset($trazos[$clave]) ? $clave : 'check';
    $tamano = str_contains($attributes->get('class', ''), 'size-') ? '' : 'size-5';
@endphp

@if ($sprite)
    <svg {{ $attributes->class([$tamano, 'shrink-0']) }} aria-hidden="true"><use href="#icono-equipamiento-{{ $clave }}"/></svg>
@else
    <svg {{ $attributes->class([$tamano, 'shrink-0']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $trazos[$clave] !!}</svg>
@endif
