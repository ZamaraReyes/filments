<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    private const BASE_URL = 'https://api.themoviedb.org/3';

    /** Seconds a successful response is cached (TMDB data is public and changes slowly). */
    private const DEFAULT_TTL = 600;
    private const GENRES_TTL = 86400;

    /**
     * GET a TMDB endpoint and return the decoded JSON.
     *
     * Retries connection errors and 5xx once, caches successful responses and
     * returns [] (after logging) when TMDB is unreachable, so callers can fall
     * back with `?? []` instead of the page failing with a 500.
     *
     * @param  array<string, mixed>|string  $query  e.g. ['language' => 'en-US'] or 'language=en-US&page=1'
     */
    public function get(string $path, array|string $query = [], ?int $ttl = null): array
    {
        if (is_string($query)) {
            parse_str($query, $query);
        }

        $query = ['api_key' => config('services.tmdb.api_key')] + $query;
        $ttl ??= str_starts_with($path, '/genre/movie/list') ? self::GENRES_TTL : self::DEFAULT_TTL;
        $key = 'tmdb:'.md5($path.'?'.http_build_query($query));

        if (is_array($cached = Cache::get($key))) {
            return $cached;
        }

        try {
            $response = Http::baseUrl(self::BASE_URL)
                ->acceptJson()
                ->timeout(8)
                ->retry(2, 200, fn ($e) => $e instanceof ConnectionException
                    || ($e instanceof RequestException && $e->response->serverError()), throw: false)
                ->when(config('services.tmdb.token'), fn ($http, $token) => $http->withToken($token))
                ->get($path, $query);
        } catch (ConnectionException $e) {
            Log::warning('TMDB unreachable', ['path' => $path, 'error' => $e->getMessage()]);

            return [];
        }

        $data = $response->json() ?? [];

        if ($response->successful()) {
            Cache::put($key, $data, $ttl);
        } else {
            Log::warning('TMDB request failed', ['path' => $path, 'status' => $response->status()]);
        }

        return $data;
    }

    /**
     * Fetch several endpoints at once (concurrently) and return the decoded JSON of each,
     * in the same order/keys as `$requests`. Cached responses are reused, only the misses
     * hit TMDB. A failed request yields [] like `get()`.
     *
     * @param  array<int|string, array{0: string, 1?: array<string, mixed>}>  $requests  [key => [path, query]]
     * @return array<int|string, array>
     */
    public function getMany(array $requests, ?int $ttl = null): array
    {
        $results = [];
        $pending = [];

        foreach ($requests as $name => [$path, $query]) {
            $query = ['api_key' => config('services.tmdb.api_key')] + ($query ?? []);
            $key = 'tmdb:'.md5($path.'?'.http_build_query($query));

            if (is_array($cached = Cache::get($key))) {
                $results[$name] = $cached;
            } else {
                $pending[$name] = [$path, $query, $key];
            }
        }

        if ($pending) {
            try {
                $responses = Http::pool(fn ($pool) => collect($pending)->map(
                    fn ($request, $name) => $pool->as((string) $name)
                        ->baseUrl(self::BASE_URL)
                        ->acceptJson()
                        ->timeout(8)
                        ->when(config('services.tmdb.token'), fn ($http, $token) => $http->withToken($token))
                        ->get($request[0], $request[1])
                )->all());
            } catch (ConnectionException $e) {
                Log::warning('TMDB unreachable', ['error' => $e->getMessage()]);
                $responses = [];
            }

            foreach ($pending as $name => [$path, , $key]) {
                $response = $responses[(string) $name] ?? null;

                if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                    $results[$name] = $response->json() ?? [];
                    Cache::put($key, $results[$name], $ttl ?? self::DEFAULT_TTL);
                } else {
                    Log::warning('TMDB request failed', ['path' => $path]);
                    $results[$name] = [];
                }
            }
        }

        // keep the caller's order
        return array_replace(array_flip(array_keys($requests)), $results);
    }

    /**
     * Movie genres as [id => name].
     */
    public function genres(): Collection
    {
        return collect($this->get('/genre/movie/list', ['language' => 'en-US'])['genres'] ?? [])
            ->pluck('name', 'id');
    }
}
