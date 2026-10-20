<?php

namespace App\Livewire\Concerns;

use App\Services\DeepLTranslator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait TranslatesWithDeepl
{
    public ?string $errorTraduccion = null;

    public function traducir(string $propiedad): void
    {
        $this->errorTraduccion = null;

        $texto = data_get($this, "{$propiedad}.es", '');

        if (trim($texto) === '') {
            return;
        }

        $translator = app(DeepLTranslator::class);

        $en = $translator->translate($texto, 'en');
        $fr = $translator->translate($texto, 'fr');

        if ($en === null && $fr === null) {
            $this->errorTraduccion = 'No se pudo traducir automáticamente (revisá la DEEPL_API_KEY). Podés completar EN/FR a mano.';

            return;
        }

        if ($en !== null) {
            data_set($this, "{$propiedad}.en", $en);
        }

        if ($fr !== null) {
            data_set($this, "{$propiedad}.fr", $fr);
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
            $esCambio = $es !== ($guardadas['es'] ?? null);

            foreach (['en', 'fr'] as $locale) {
                $actual = (string) ($form[$locale] ?? '');
                $sinTocar = $actual === (string) ($guardadas[$locale] ?? '');

                if (trim($actual) !== '' && ! ($esCambio && $sinTocar)) {
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
            session()->flash('aviso', 'DeepL no respondió: algunas traducciones EN/FR quedaron sin actualizar. Podés completarlas a mano o volver a guardar más tarde.');
        }
    }
}
