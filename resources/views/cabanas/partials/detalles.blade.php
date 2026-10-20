@php
    $detalles = array_filter([
        $cabana->capacidad ? __('Hasta :n personas', ['n' => $cabana->capacidad]) : null,
        $cabana->cantidad_banos
            ? trans_choice($cabana->bano_privado ? ':n baño privado|:n baños privados' : ':n baño compartido|:n baños compartidos', $cabana->cantidad_banos, ['n' => $cabana->cantidad_banos])
            : null,
    ]);
@endphp

@if ($detalles)
    <p class="{{ $clase ?? 'mt-1 text-stone-500' }}">{{ implode(' · ', $detalles) }}</p>
@endif
