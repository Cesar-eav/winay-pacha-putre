<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class ProcesadorImagen
{
    public const VARIANTES = [
        'thumbs' => ['ancho' => 480, 'calidad' => 75],
        'medium' => ['ancho' => 1200, 'calidad' => 78],
    ];

    private const ANCHO_FULL = 2000;

    private const CALIDAD_FULL = 80;

    public static function guardar(UploadedFile|string $origen, string $carpeta, string $campoError = 'nuevasFotos'): string
    {
        $manager = new ImageManager(new Driver);

        try {
            $binario = $origen instanceof UploadedFile ? $origen->get() : $origen;
            $base = $manager->decodeBinary($binario);
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                $campoError => 'No se pudo procesar una de las imágenes. Expórtala como JPG o PNG e inténtalo de nuevo.',
            ]);
        }

        $nombre = Str::random(40).'.webp';
        $disco = Storage::disk('public');

        $disco->put("$carpeta/$nombre", self::codificar($base, self::ANCHO_FULL, self::CALIDAD_FULL));

        foreach (self::VARIANTES as $variante => $cfg) {
            $disco->put("$carpeta/$variante/$nombre", self::codificar($base, $cfg['ancho'], $cfg['calidad']));
        }

        return "$carpeta/$nombre";
    }

    public static function eliminar(?string $path): void
    {
        if (! $path || str_starts_with($path, 'placeholder/')) {
            return;
        }

        $disco = Storage::disk('public');
        $disco->delete($path);

        foreach (array_keys(self::VARIANTES) as $variante) {
            $disco->delete(self::rutaVariante($path, $variante));
        }
    }

    public static function rutaVariante(string $path, string $variante): string
    {
        return dirname($path).'/'.$variante.'/'.basename($path);
    }

    public static function urlVariante(string $path, string $variante): string
    {
        if (str_starts_with($path, 'placeholder/')) {
            return asset('images/'.$path);
        }

        $ruta = self::rutaVariante($path, $variante);

        return Storage::disk('public')->exists($ruta)
            ? asset('storage/'.$ruta)
            : asset('storage/'.$path);
    }

    private static function codificar($imagen, int $ancho, int $calidad): string
    {
        return (string) (clone $imagen)
            ->scaleDown(width: $ancho)
            ->encodeUsingFormat(Format::WEBP, quality: $calidad);
    }
}
