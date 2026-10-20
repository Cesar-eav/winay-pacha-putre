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
            $response = Http::asForm()
                ->withHeaders(['Authorization' => "DeepL-Auth-Key {$apiKey}"])
                ->post($url, array_filter([
                    'text' => $texto,
                    'source_lang' => 'ES',
                    'target_lang' => strtoupper($destino),
                    'tag_handling' => $texto !== strip_tags($texto) ? 'html' : null,
                ]));

            if (! $response->successful()) {
                Log::warning('DeepL: respuesta no exitosa', ['status' => $response->status()]);

                return null;
            }

            // Con tag_handling=html DeepL escapa los apóstrofes, que Quill guarda literales
            // y que los extractos con strip_tags() mostrarían como "&#x27;".
            return str_replace(['&#x27;', '&#39;'], "'", (string) $response->json('translations.0.text'));
        } catch (\Throwable $e) {
            Log::warning('DeepL: error al traducir', ['message' => $e->getMessage()]);

            return null;
        }
    }
}
