<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OmdbService
{
    /**
     * Look a title up by IMDb id (director, writer, awards...). Returns [] when
     * the key is not configured or OMDb is unreachable; views fall back to "N/A".
     */
    public function find(?string $imdbId): array
    {
        $apiKey = config('services.omdb.api_key');

        if (! $imdbId || ! $apiKey) {
            return [];
        }

        $key = 'omdb:'.$imdbId;

        if (is_array($cached = Cache::get($key))) {
            return $cached;
        }

        // OMDb solo aporta datos secundarios: timeout corto y sin reintentar los
        // timeouts, para que un OMDb lento no deje la ficha colgada 16 s o más.
        try {
            $response = Http::acceptJson()->timeout(4)
                ->retry(2, 200, fn ($e) => $e instanceof RequestException && $e->response->serverError(), throw: false)
                ->get('https://omdbapi.com', ['i' => $imdbId, 'apikey' => $apiKey]);
        } catch (ConnectionException $e) {
            // el mensaje de cURL incluye la URL con la clave: no llevarla al log
            Log::warning('OMDb unreachable', [
                'imdb_id' => $imdbId,
                'error' => str_replace($apiKey, '***', $e->getMessage()),
            ]);

            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        // solo se cachea una respuesta buena; un fallo puntual no deja la
        // película sin datos durante 24 h
        $data = $response->json() ?? [];
        Cache::put($key, $data, 86400);

        return $data;
    }
}
