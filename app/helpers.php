<?php

if (! function_exists('lroute')) {
    function lroute(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $localePredeterminado = array_key_first(config('winay.locales'));

        return route($locale === $localePredeterminado ? $name : "{$locale}.{$name}", $parameters);
    }
}
