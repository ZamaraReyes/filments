<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
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

        return Cache::remember('omdb:'.$imdbId, 86400, function () use ($imdbId, $apiKey) {
            try {
                $response = Http::acceptJson()->timeout(8)
                    ->retry(2, 200, throw: false)
                    ->get('https://omdbapi.com', ['i' => $imdbId, 'apikey' => $apiKey]);
            } catch (ConnectionException $e) {
                Log::warning('OMDb unreachable', ['imdb_id' => $imdbId, 'error' => $e->getMessage()]);

                return [];
            }

            return $response->successful() ? ($response->json() ?? []) : [];
        });
    }
}
