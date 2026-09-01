<?php

namespace App\Livewire\Concerns;

use App\Services\DeepLTranslator;

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
}
