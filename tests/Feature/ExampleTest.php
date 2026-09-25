<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_loads_for_guests(): void
    {
        Cache::flush();
        config(['services.tmdb.api_key' => 'test-key']);
        Http::fake(['*' => Http::response(['results' => [], 'genres' => []])]);

        $this->get('/')->assertStatus(200);
    }
}
