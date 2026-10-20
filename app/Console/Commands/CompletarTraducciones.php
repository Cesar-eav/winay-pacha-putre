<?php

namespace App\Console\Commands;

use App\Models\Cabana;
use App\Models\Especie;
use App\Models\Fiesta;
use App\Models\LugarEntorno;
use App\Models\Tema;
use App\Services\DeepLTranslator;
use Illuminate\Console\Command;

class CompletarTraducciones extends Command
{
    protected $signature = 'traducciones:completar {--dry-run : Solo lista lo que se traduciría}';

    protected $description = 'Traduce con DeepL los campos EN/FR vacíos del contenido existente (no pisa traducciones ya cargadas).';

    public function handle(DeepLTranslator $translator): int
    {
        $dry = $this->option('dry-run');
        $traducidas = 0;
        $fallidas = 0;

        foreach ([Tema::class, Cabana::class, LugarEntorno::class, Especie::class, Fiesta::class] as $modelo) {
            foreach ($modelo::all() as $registro) {
                $cambios = false;

                foreach ($registro->getTranslatableAttributes() as $campo) {
                    $traducciones = $registro->getTranslations($campo);
                    $es = (string) ($traducciones['es'] ?? '');

                    if (trim($es) === '') {
                        continue;
                    }

                    foreach (['en', 'fr'] as $locale) {
                        if (trim((string) ($traducciones[$locale] ?? '')) !== '') {
                            continue;
                        }

                        $this->line(($dry ? '[dry] ' : '').class_basename($registro)." #{$registro->getKey()}: $campo → $locale");

                        if ($dry) {
                            continue;
                        }

                        $traduccion = $translator->translate($es, $locale);

                        if ($traduccion === null) {
                            $this->warn('  Falló la traducción (ver log).');
                            $fallidas++;

                            continue;
                        }

                        $registro->setTranslation($campo, $locale, $traduccion);
                        $cambios = true;
                        $traducidas++;
                    }
                }

                if ($cambios) {
                    $registro->save();
                }
            }
        }

        $this->info("Traducciones completadas: $traducidas. Fallidas: $fallidas");

        return $fallidas ? self::FAILURE : self::SUCCESS;
    }
}
