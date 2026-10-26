<?php

namespace App\Console\Commands;

use App\Models\Cabana;
use App\Models\Equipamiento;
use App\Services\DeepLTranslator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ActualizarAlojamientos extends Command
{
    protected $signature = 'alojamientos:actualizar {--dry-run : Solo lista los cambios, sin guardar ni traducir}';

    protected $description = 'Carga las fichas reales de cabañas y habitaciones (descripción, capacidad, número, equipamiento) enviadas por el cliente.';

    private const EQUIPAMIENTOS = [
        ['nombre' => 'Comedor', 'ambito' => 'cabana', 'icono' => 'comedor'],
        ['nombre' => 'Frigobar', 'ambito' => 'cabana', 'icono' => 'frigobar'],
        ['nombre' => 'Microondas', 'ambito' => 'cabana', 'icono' => 'microondas'],
        ['nombre' => 'Hervidor', 'ambito' => 'cabana', 'icono' => 'hervidor'],
        ['nombre' => 'TV 34"', 'ambito' => 'cabana', 'icono' => 'tv'],
        ['nombre' => 'Lavaplatos', 'ambito' => 'cabana', 'icono' => 'lavaplatos'],
        ['nombre' => 'Sofá', 'ambito' => 'cabana', 'icono' => 'sofa'],
        ['nombre' => 'Calefactor eléctrico', 'ambito' => 'habitacion', 'icono' => 'calefaccion'],
        ['nombre' => 'Mueble para ropa', 'ambito' => 'habitacion', 'icono' => 'ropero'],
        ['nombre' => 'Ropero', 'ambito' => 'habitacion', 'icono' => 'ropero'],
    ];

    private const GENERICOS_CONSERVADOS = ['Wifi', 'Estacionamiento', 'Agua caliente', 'Ropa de cama'];

    private const BASE_CABANA = [
        'Cocina equipada', 'Comedor', 'Frigobar', 'Microondas', 'Hervidor', 'TV 34"', 'Lavaplatos',
        'Calefactor eléctrico', 'Velador',
    ];

    private const BASE_HABITACION = ['Ropero', 'TV 34"', 'Comedor', 'Hervidor', 'Frigobar', 'Velador'];

    private const COCINA = '<p>La cocina cuenta con artículos básicos para cocinar y preparar el desayuno.</p>';

    private const DESAYUNO = '<p>Comedor con hervidor y tazas para prepararse un desayuno.</p>';

    public function handle(DeepLTranslator $translator): int
    {
        $dry = $this->option('dry-run');

        $this->equipamientos($dry);

        foreach ($this->alojamientos() as $slug => $datos) {
            $cabana = Cabana::with('equipamientos')->where('slug', $slug)->first();

            if (! $cabana) {
                $this->warn("No existe el alojamiento '$slug', se omite.");

                continue;
            }

            $this->actualizar($cabana, $datos, $dry, $translator);
        }

        $this->info($dry ? 'Dry-run: no se guardó nada.' : 'Alojamientos actualizados.');

        return self::SUCCESS;
    }

    private function equipamientos(bool $dry): void
    {
        if (! Equipamiento::where('nombre', 'Calefactor eléctrico')->exists()
            && Equipamiento::where('nombre', 'Calefacción')->exists()) {
            $this->line(($dry ? '[dry] ' : '').'Equipamiento: Calefacción → Calefactor eléctrico');

            if (! $dry) {
                Equipamiento::where('nombre', 'Calefacción')->update(['nombre' => 'Calefactor eléctrico']);
            }
        }

        foreach (self::EQUIPAMIENTOS as $orden => $item) {
            $existente = Equipamiento::where('nombre', $item['nombre'])->first();

            if ($existente && $existente->icono === $item['icono']) {
                continue;
            }

            $this->line(($dry ? '[dry] ' : '').'Equipamiento: '.($existente ? 'ícono de ' : 'nuevo ').$item['nombre']);

            if (! $dry) {
                Equipamiento::updateOrCreate(
                    ['nombre' => $item['nombre']],
                    ['ambito' => $item['ambito'], 'icono' => $item['icono'], 'orden' => $existente->orden ?? 10 + $orden],
                );
            }
        }
    }

    private function actualizar(Cabana $cabana, array $datos, bool $dry, DeepLTranslator $translator): void
    {
        $descripcion = $datos['descripcion'] ?? null;
        $equipamiento = $datos['equipamiento'] ?? null;
        unset($datos['descripcion'], $datos['equipamiento']);

        $cabana->fill($datos);
        $cambios = array_keys($cabana->getDirty());

        $nuevaDescripcion = $descripcion !== null && $descripcion !== $cabana->getTranslation('descripcion', 'es', false);

        if ($nuevaDescripcion) {
            $cambios[] = 'descripcion';
        }

        $equipamientoIds = null;

        if ($equipamiento !== null) {
            $conservados = $cabana->equipamientos->whereIn('nombre', self::GENERICOS_CONSERVADOS)->pluck('nombre')->all();
            $nombres = array_unique([...$equipamiento, ...$conservados]);
            $faltantes = array_diff($nombres, Equipamiento::whereIn('nombre', $nombres)->pluck('nombre')->all());
            $equipamientoIds = Equipamiento::whereIn('nombre', $nombres)->pluck('id')->sort()->values()->all();

            if ($faltantes && ! $dry) {
                $this->warn("  {$cabana->slug}: equipamiento inexistente: ".implode(', ', $faltantes));
            }

            if ($faltantes || $equipamientoIds !== $cabana->equipamientos->pluck('id')->sort()->values()->all()) {
                $cambios[] = 'equipamiento ('.implode(', ', $nombres).')';
            }
        }

        if (! $cambios) {
            $this->line("{$cabana->slug}: sin cambios");

            return;
        }

        $this->line(($dry ? '[dry] ' : '')."{$cabana->slug}: ".implode(', ', $cambios));

        if ($dry) {
            return;
        }

        if ($nuevaDescripcion) {
            $cabana->setTranslation('descripcion', 'es', $descripcion);

            foreach (['en', 'fr'] as $locale) {
                $traduccion = $translator->translate($descripcion, $locale);

                if ($traduccion === null) {
                    $this->warn("  Falló la traducción a $locale (ver log); queda vacía para `traducciones:completar`.");
                    $cabana->forgetTranslation('descripcion', $locale);

                    continue;
                }

                $cabana->setTranslation('descripcion', $locale, $traduccion);
            }
        }

        DB::transaction(function () use ($cabana, $equipamientoIds) {
            $cabana->save();

            if ($equipamientoIds !== null) {
                $cabana->equipamientos()->sync($equipamientoIds);
            }
        });
    }

    private function alojamientos(): array
    {
        $publicado = ['publicado' => true, 'cantidad_banos' => 1, 'bano_privado' => true];

        return [
            'cabana-wilamasi' => [
                ...$publicado,
                'numero' => 1,
                'capacidad' => 4,
                'orden' => 0,
                'descripcion' => '<p><strong>Habitación 1</strong>: 1 cama king.</p>'
                    .'<p><strong>Habitación 2</strong>: 2 camas de 1,5 plazas.</p>'
                    .'<p>Cada habitación tiene veladores y un pequeño mueble para guardar sus cosas.</p>'
                    .self::COCINA,
                'equipamiento' => [...self::BASE_CABANA, 'Mueble para ropa'],
            ],
            'cabana-anata' => [
                ...$publicado,
                'numero' => 3,
                'capacidad' => 4,
                'orden' => 1,
                'descripcion' => '<p><strong>Habitación 1</strong>: 2 camas de 1,5 plazas.</p>'
                    .'<p><strong>Habitación 2</strong>: 2 camas de 1,5 plazas.</p>'
                    .'<p>Cada habitación tiene veladores y un pequeño mueble para guardar sus cosas.</p>'
                    .self::COCINA,
                'equipamiento' => [...self::BASE_CABANA, 'Mueble para ropa'],
            ],
            'vicuna' => [
                ...$publicado,
                'numero' => 8,
                'capacidad' => 3,
                'orden' => 2,
                'descripcion' => '<p>Una habitación con 1 cama de 2 plazas y 1 cama de 1,5 plazas, con veladores.</p>'
                    .'<p>Living-comedor con sofá.</p>'
                    .self::COCINA,
                'equipamiento' => [...self::BASE_CABANA, 'Sofá'],
            ],
            'vizcacha' => [
                ...$publicado,
                'numero' => 9,
                'capacidad' => 5,
                'orden' => 3,
                'descripcion' => '<p>Nuestra cabaña más grande.</p>'
                    .'<p><strong>Habitación 1</strong>: 1 cama de 2 plazas y mueble para la ropa.</p>'
                    .'<p><strong>Habitación 2</strong>: 1 cama de 1,5 plazas y 1 cama nido.</p>'
                    .'<p>Ambas habitaciones tienen veladores.</p>'
                    .self::COCINA,
                'equipamiento' => [...self::BASE_CABANA, 'Mueble para ropa'],
            ],
            'warmi' => ['publicado' => false, 'orden' => 10],
            'cabana-suriplaza' => ['publicado' => false, 'orden' => 11],
            'lago-chungara' => [
                ...$publicado,
                'numero' => 5,
                'capacidad' => 4,
                'orden' => 0,
                'descripcion' => '<p>2 camas de 1,5 plazas y 1 cama nido.</p>'.self::DESAYUNO,
                'equipamiento' => self::BASE_HABITACION,
            ],
            'salar-de-surire' => [
                ...$publicado,
                'numero' => 6,
                'capacidad' => 3,
                'orden' => 1,
                'descripcion' => '<p>1 cama king y 1 cama de 1,5 plazas.</p>'.self::DESAYUNO,
                'equipamiento' => self::BASE_HABITACION,
            ],
        ];
    }
}
