<?php

namespace App\Facades;

use App\Services\OmdbService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array find(?string $imdbId)
 *
 * @see \App\Services\OmdbService
 */
class Omdb extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OmdbService::class;
    }
}
