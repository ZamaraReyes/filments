<?php

namespace Tests\Feature;

use App\Services\OmdbService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class OmdbServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.omdb.api_key' => 'secret-key']);
    }

    public function test_a_timeout_is_not_cached_and_the_key_is_not_logged(): void
    {
        Http::fake(fn () => throw new ConnectionException('cURL error 28 for https://omdbapi.com?i=tt1&apikey=secret-key'));
        Log::spy();

        $this->assertSame([], app(OmdbService::class)->find('tt1'));

        Log::shouldHaveReceived('warning')->withArgs(
            fn ($message, $context) => ! str_contains($context['error'], 'secret-key')
        );
        $this->assertNull(Cache::get('omdb:tt1'));
    }

    public function test_a_successful_response_is_cached(): void
    {
        Http::fake(['*' => Http::response(['Director' => 'David Fincher'])]);

        $this->assertSame('David Fincher', app(OmdbService::class)->find('tt1')['Director']);
        $this->assertSame('David Fincher', Cache::get('omdb:tt1')['Director']);
    }
}
