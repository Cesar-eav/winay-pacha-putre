<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Nombres base (sin prefijo de idioma) de las rutas públicas registradas en routes/web.php.
     */
    private const RUTAS_PUBLICAS = [
        'inicio', 'cultura', 'putre', 'cabanas.index', 'cabanas.show',
        'habitaciones.index', 'habitaciones.show',
        'entorno', 'entorno.show', 'nosotros', 'contacto', 'reserva',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $localePredeterminado = array_key_first(config('winay.locales'));
        $routeName = $request->route()?->getName();

        foreach (config('winay.locales') as $codigo => $label) {
            $prefijo = $codigo === $localePredeterminado ? '' : "{$codigo}.";

            if ($routeName !== null && str_starts_with($routeName, $prefijo)
                && in_array(substr($routeName, strlen($prefijo)), self::RUTAS_PUBLICAS, true)) {
                App::setLocale($codigo);

                return $next($request);
            }
        }

        // Admin y auth van siempre en español. Las peticiones internas de Livewire de los
        // formularios públicos recuperan su idioma del snapshot del componente (SupportLocales).
        App::setLocale($localePredeterminado);

        return $next($request);
    }
}
