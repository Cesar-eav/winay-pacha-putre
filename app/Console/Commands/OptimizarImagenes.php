<?php

namespace App\Console\Commands;

use App\Models\Especie;
use App\Models\Imagen;
use App\Services\ProcesadorImagen;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OptimizarImagenes extends Command
{
    protected $signature = 'imagenes:optimizar {--dry-run : Solo lista lo que se procesaría}';

    protected $description = 'Genera variantes WebP (thumbs/medium/full) para imágenes ya subidas y actualiza sus rutas.';

    public function handle(): int
    {
        $disco = Storage::disk('public');
        $dry = $this->option('dry-run');
        $procesadas = 0;
        $omitidas = 0;

        $tareas = [
            [Imagen::query(), 'path'],
            [Especie::query()->whereNotNull('imagen'), 'imagen'],
        ];

        foreach ($tareas as [$query, $columna]) {
            foreach ($query->get() as $registro) {
                $path = $registro->{$columna};

                if (! $path || str_starts_with($path, 'placeholder/') || ! $disco->exists($path)) {
                    continue;
                }

                $ruta = ProcesadorImagen::rutaVariante($path, 'thumbs');

                if (str_ends_with($path, '.webp') && $disco->exists($ruta)) {
                    continue;
                }

                $this->line(($dry ? '[dry] ' : '').get_class($registro)." #{$registro->getKey()}: $path");

                if ($dry) {
                    continue;
                }

                try {
                    $nuevo = ProcesadorImagen::guardar($disco->get($path), dirname($path));
                } catch (ValidationException) {
                    $this->warn("  Omitida (archivo ilegible o corrupto): $path");
                    $omitidas++;

                    continue;
                }

                $registro->forceFill([$columna => $nuevo])->save();
                ProcesadorImagen::eliminar($path);
                $procesadas++;
            }
        }

        $this->info("Imágenes procesadas: $procesadas. Omitidas: $omitidas");

        return self::SUCCESS;
    }
}
