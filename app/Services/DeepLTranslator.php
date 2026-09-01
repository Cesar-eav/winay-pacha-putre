<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepLTranslator
{
    public function translate(string $texto, string $destino): ?string
    {
        $texto = trim($texto);

        if ($texto === '') {
            return '';
        }

        $apiKey = config('winay.deepl_api_key');

        if (! $apiKey) {
            Log::warning('DeepL: DEEPL_API_KEY no configurada, traducción omitida.');

            return null;
        }

        $url = str_ends_with($apiKey, ':fx')
            ? 'https://api-free.deepl.com/v2/translate'
            : 'https://api.deepl.com/v2/translate';

        try {
            $response = Http::asForm()->post($url, [
                'auth_key' => $apiKey,
                'text' => $texto,
                'target_lang' => strtoupper($destino),
            ]);

            if (! $response->successful()) {
                Log::warning('DeepL: respuesta no exitosa', ['status' => $response->status()]);

                return null;
            }

            return $response->json('translations.0.text');
        } catch (\Throwable $e) {
            Log::warning('DeepL: error al traducir', ['message' => $e->getMessage()]);

            return null;
        }
    }
}
