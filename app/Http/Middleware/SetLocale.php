<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $soportados = array_keys(config('winay.locales'));
        $localePredeterminado = array_key_first(config('winay.locales'));
        $segmento = $request->segment(1);

        if (in_array($segmento, $soportados, true) && $segmento !== $localePredeterminado) {
            $locale = $segmento;
            session(['locale' => $locale]);
        } else {
            $locale = session('locale', $localePredeterminado);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
