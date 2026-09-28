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
                session(['locale' => $codigo]);
                App::setLocale($codigo);

                return $next($request);
            }
        }

        // Ruta fuera del grupo público (admin, auth, endpoint interno de Livewire, etc.):
        // se mantiene el idioma ya elegido en la sesión durante la navegación pública.
        App::setLocale(session('locale', $localePredeterminado));

        return $next($request);
    }
}
