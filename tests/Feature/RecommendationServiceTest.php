<?php

namespace Tests\Feature;

use App\Services\RecommendationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecommendationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.tmdb.api_key' => 'test-key']);
    }

    private function movie(int $id, array $genres, array $overrides = []): array
    {
        return $overrides + [
            'id' => $id,
            'title' => "Movie $id",
            'genre_ids' => $genres,
            'backdrop_path' => '/b.jpg',
            'poster_path' => '/p.jpg',
            'vote_count' => 500,
            'vote_average' => 7.5,
            'release_date' => '2024-01-01',
        ];
    }

    public function test_keeps_movies_with_at_least_one_favorite_genre(): void
    {
        Http::fake([
            '*/discover/movie*' => Http::response(['results' => [
                $this->movie(1, [28, 35]),   // acción + comedia: sirve, aunque comedia no sea favorita
                $this->movie(2, [18]),       // solo drama: fuera
            ]]),
        ]);

        $ids = array_column(app(RecommendationService::class)->forPreferences([28], [], []), 'id');

        $this->assertSame([1], $ids);
    }

    public function test_filters_low_quality_excluded_and_duplicates_and_sorts_newest_first(): void
    {
        Http::fake([
            '*/discover/movie*' => Http::response(['results' => [
                $this->movie(1, [28], ['release_date' => '2020-01-01']),
                $this->movie(2, [28], ['vote_count' => 10]),          // pocos votos
                $this->movie(3, [28], ['poster_path' => null]),       // sin póster
                $this->movie(4, [28]),                                // favorita/dislike
                $this->movie(5, [28], ['release_date' => '2025-06-01']),
                $this->movie(5, [28], ['release_date' => '2025-06-01']), // duplicada
            ]]),
        ]);

        $ids = array_column(app(RecommendationService::class)->forPreferences([28], [], [4]), 'id');

        $this->assertSame([5, 1], $ids);
    }

    public function test_actor_movies_are_used_and_not_filtered_by_genre_when_user_has_no_favorite_genres(): void
    {
        Http::fake([
            '*/person/77/combined_credits*' => Http::response(['cast' => [
                $this->movie(10, [99], ['media_type' => 'movie']),
                $this->movie(11, [99], ['media_type' => 'tv']),       // series: fuera
            ]]),
        ]);

        $ids = array_column(app(RecommendationService::class)->forPreferences([], [77], []), 'id');

        $this->assertSame([10], $ids);
        Http::assertNotSent(fn ($request) => str_contains($request->url(), '/discover/movie'));
    }

    public function test_result_is_cached_for_the_same_preferences(): void
    {
        Http::fake(['*/discover/movie*' => Http::response(['results' => [$this->movie(1, [28])]])]);

        $service = app(RecommendationService::class);
        $service->forPreferences([28], [], []);
        $service->forPreferences([28], [], []);

        Http::assertSentCount(3); // 3 páginas, una sola vez
    }
}
