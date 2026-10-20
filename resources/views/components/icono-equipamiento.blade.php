@props(['equipamiento'])

@php
    $trazos = [
        'cocina' => '<path d="M2 12h20"/><path d="M20 12v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8"/><path d="m4 8 16-4"/><path d="m8.86 6.78-.45-1.81a2 2 0 0 1 1.45-2.43l1.94-.48a2 2 0 0 1 2.43 1.46l.45 1.8"/>',
        'estufa' => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
        'agua-caliente' => '<path d="m4 4 2.5 2.5"/><path d="M13.5 6.5a4.95 4.95 0 0 0-7 7"/><path d="M15 5 5 15"/><path d="M14 17v.01"/><path d="M10 16v.01"/><path d="M13 13v.01"/><path d="M16 10v.01"/><path d="M11 20v.01"/><path d="M17 14v.01"/><path d="M20 11v.01"/>',
        'wifi' => '<path d="M12 20h.01"/><path d="M2 8.82a15 15 0 0 1 20 0"/><path d="M5 12.86a10 10 0 0 1 14 0"/><path d="M8.5 16.43a5 5 0 0 1 7 0"/>',
        'estacionamiento' => '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>',
        'cama' => '<path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/>',
        'ropa-cama' => '<path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>',
        'calefaccion' => '<path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0z"/>',
        'velador' => '<path d="M8 2h8l4 10H4L8 2z"/><path d="M12 12v6"/><path d="M8 22v-2c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2H8z"/>',
        'tv' => '<rect width="20" height="15" x="2" y="7" rx="2"/><path d="m17 2-5 5-5-5"/>',
        'check' => '<path d="M20 6 9 17l-5-5"/>',
    ];

    // Los equipamientos existentes se crearon todos con icono "check":
    // si no hay uno específico, se deduce del nombre.
    $palabras = [
        'ropa' => 'ropa-cama', 'sabana' => 'ropa-cama', 'toalla' => 'ropa-cama',
        'cama' => 'cama',
        'wifi' => 'wifi', 'internet' => 'wifi',
        'cocina' => 'cocina',
        'estufa' => 'estufa', 'lena' => 'estufa', 'chimenea' => 'estufa',
        'agua' => 'agua-caliente', 'ducha' => 'agua-caliente',
        'estacionamiento' => 'estacionamiento', 'parking' => 'estacionamiento',
        'calefacc' => 'calefaccion', 'calefactor' => 'calefaccion',
        'velador' => 'velador', 'lampara' => 'velador',
        'tv' => 'tv', 'televis' => 'tv',
    ];

    $clave = $equipamiento->icono;

    if (! isset($trazos[$clave]) || $clave === 'check') {
        $nombre = Illuminate\Support\Str::lower(Illuminate\Support\Str::ascii($equipamiento->nombre));
        $clave = collect($palabras)->first(fn ($icono, $palabra) => preg_match('/\b'.$palabra.'/', $nombre)) ?? 'check';
    }
@endphp

<svg {{ $attributes->merge(['class' => 'size-5 shrink-0']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $trazos[$clave] !!}</svg>
