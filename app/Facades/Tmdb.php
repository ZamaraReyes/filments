<?php

namespace App\Facades;

use App\Services\TmdbService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array get(string $path, array|string $query = [], ?int $ttl = null)
 * @method static array getMany(array $requests, ?int $ttl = null)
 * @method static \Illuminate\Support\Collection genres()
 *
 * @see \App\Services\TmdbService
 */
class Tmdb extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TmdbService::class;
    }
}
