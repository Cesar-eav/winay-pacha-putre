<?php

namespace App\Livewire\Concerns;

use App\Services\DeepLTranslator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait TranslatesWithDeepl
{
    /** @var array<string, string> Aviso de DeepL por campo, para mostrarlo solo bajo el botón pulsado. */
    public array $errorTraduccion = [];

    /** @var array<string, array<string, array{es: string, traduccion: string}>> Lo que puso el botón "Traducir", para no confundirlo con una edición a mano al guardar. */
    public array $traduccionesAuto = [];

    public function traducir(string $propiedad): void
    {
        unset($this->errorTraduccion[$propiedad]);

        $texto = data_get($this, "{$propiedad}.es", '');

        if (trim($texto) === '') {
            return;
        }

        $translator = app(DeepLTranslator::class);
        $fallidos = [];

        foreach (['en' => 'inglés', 'fr' => 'francés'] as $locale => $idioma) {
            $traduccion = $translator->translate($texto, $locale);

            if ($traduccion === null) {
                $fallidos[] = $idioma;

                continue;
            }

            data_set($this, "{$propiedad}.{$locale}", $traduccion);
            $this->traduccionesAuto[$propiedad][$locale] = ['es' => $texto, 'traduccion' => $traduccion];
        }

        if (count($fallidos) === 2) {
            $this->errorTraduccion[$propiedad] = 'No se pudo traducir automáticamente (revisa la DEEPL_API_KEY). Puedes completar EN/FR a mano.';
        } elseif ($fallidos) {
            $this->errorTraduccion[$propiedad] = "No se pudo traducir al {$fallidos[0]}. Vuelve a intentarlo o complétalo a mano.";
        }
    }

    /**
     * Al guardar: traduce los idiomas vacíos, y también los que quedaron desactualizados
     * porque el ES cambió y ese idioma no se editó a mano en esta sesión.
     */
    protected function completarTraducciones(array $propiedades, ?Model $guardado): void
    {
        $translator = app(DeepLTranslator::class);
        $fallos = false;

        foreach ($propiedades as $propiedad) {
            $form = $this->{$propiedad};
            $es = (string) ($form['es'] ?? '');

            if (trim($es) === '') {
                continue;
            }

            $guardadas = $guardado?->getTranslations(Str::snake($propiedad)) ?? [];
            $auto = $this->traduccionesAuto[$propiedad] ?? [];

            foreach (['en', 'fr'] as $locale) {
                $actual = (string) ($form[$locale] ?? '');

                // Si el texto vino del botón "Traducir", se compara contra el ES que se tradujo entonces.
                [$esBase, $sinTocar] = $actual === ($auto[$locale]['traduccion'] ?? null)
                    ? [$auto[$locale]['es'], true]
                    : [$guardadas['es'] ?? null, $actual === (string) ($guardadas[$locale] ?? '')];

                if (trim($actual) !== '' && ! ($es !== $esBase && $sinTocar)) {
                    continue;
                }

                $traduccion = $translator->translate($es, $locale);

                if ($traduccion === null) {
                    $fallos = true;

                    continue;
                }

                $this->{$propiedad}[$locale] = $traduccion;
            }
        }

        if ($fallos) {
            session()->flash('aviso', 'DeepL no respondió: algunas traducciones EN/FR quedaron sin actualizar. Puedes completarlas a mano o volver a guardar más tarde.');
        }
    }
}
