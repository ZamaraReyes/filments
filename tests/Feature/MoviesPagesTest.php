<?php

namespace Tests\Feature;

use App\Models\FavoriteActor;
use App\Models\FavoriteMovie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MoviesPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config([
            'services.tmdb.api_key' => 'test-key',
            // nunca hacer una llamada real a OMDb aunque el .env local tenga una clave
            'services.omdb.api_key' => null,
        ]);

        Http::fake([
            '*/genre/movie/list*' => Http::response(['genres' => [
                ['id' => 28, 'name' => 'Action'],
            ]]),
            '*/movie/550*' => Http::response([
                'id' => 550,
                'imdb_id' => 'tt0137523',
                'title' => 'Fight Club',
                'overview' => 'An insomniac office worker...',
                'release_date' => '1999-10-15',
                'runtime' => 139,
                'vote_average' => 8.4,
                'backdrop_path' => '/bd.jpg',
                'poster_path' => '/fc.jpg',
                'genres' => [['id' => 28, 'name' => 'Action']],
            ]),
            '*/person/287*' => Http::response([
                'id' => 287,
                'name' => 'Brad Pitt',
                'birthday' => '1963-12-18',
                'deathday' => null,
                'place_of_birth' => 'Shawnee, Oklahoma, USA',
                'biography' => 'An American actor.',
                'homepage' => null,
                'popularity' => 40.5,
                'profile_path' => '/bp.jpg',
            ]),
            '*/genre/28/movies*' => Http::response(['results' => [[
                'id' => 550,
                'title' => 'Fight Club',
                'genre_ids' => [28],
                'backdrop_path' => '/bd.jpg',
                'poster_path' => '/fc.jpg',
                'vote_average' => 8.4,
            ]]]),
            // resto de endpoints (credits, similar, videos, combined_credits...): vacíos
            '*' => Http::response(['results' => [], 'cast' => [], 'genres' => []]),
        ]);
    }

    private function user(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    public function test_movie_page_renders_for_guests(): void
    {
        $this->get(route('movies.show', 550))
            ->assertOk()
            ->assertSee('Fight Club')
            ->assertSee('Login to add favorites');
    }

    public function test_movie_page_offers_to_remove_an_already_favorited_movie(): void
    {
        $user = $this->user();
        FavoriteMovie::create(['user_id' => $user->id, 'tmdb_id' => 550, 'title' => 'Fight Club']);

        $this->actingAs($user)->get(route('movies.show', 550))
            ->assertOk()
            ->assertSee('Remove from favorites');
    }

    public function test_movie_page_offers_to_add_a_movie_that_is_not_favorited_yet(): void
    {
        $user = $this->user();

        $this->actingAs($user)->get(route('movies.show', 550))
            ->assertOk()
            ->assertSee('Add to favorites');
    }

    public function test_movie_page_fetches_the_trailer_by_tmdb_id_even_without_imdb_id(): void
    {
        // fake propio: el comodín '*' del setUp ganaría a estos stubs
        Http::swap(new \Illuminate\Http\Client\Factory);
        Http::fake([
            '*/movie/999/videos*' => Http::response(['results' => [['key' => 'abc123', 'name' => 'Trailer']]]),
            '*/movie/999?*' => Http::response([
                'id' => 999,
                'imdb_id' => null,
                'title' => 'Upcoming Movie',
                'genres' => [],
            ]),
            '*' => Http::response(['results' => [], 'cast' => [], 'genres' => []]),
        ]);

        $this->get(route('movies.show', 999))
            ->assertOk()
            ->assertSee('youtube.com/embed/abc123', false);

        Http::assertNotSent(fn ($request) => str_contains($request->url(), '/movie//videos'));
    }

    public function test_movie_page_returns_404_when_tmdb_does_not_know_the_movie(): void
    {
        Http::swap(new \Illuminate\Http\Client\Factory);
        Http::fake([
            '*/movie/12345678?*' => Http::response(['success' => false, 'status_code' => 34], 404),
            '*' => Http::response(['results' => [], 'cast' => [], 'genres' => []]),
        ]);

        $this->get(route('movies.show', 12345678))->assertNotFound();
    }

    public function test_actor_page_renders_for_guests(): void
    {
        $this->get(route('actors.show', 287))
            ->assertOk()
            ->assertSee('Brad Pitt');
    }

    public function test_actor_page_offers_to_dislike_an_already_favorited_actor(): void
    {
        $user = $this->user();
        FavoriteActor::create(['user_id' => $user->id, 'tmdb_id' => 287, 'name' => 'Brad Pitt']);

        $this->actingAs($user)->get(route('actors.show', 287))
            ->assertOk()
            ->assertSee('Dislike');
    }

    public function test_genre_page_renders_with_its_movies(): void
    {
        $this->get(route('genres.showGenre', 28))
            ->assertOk()
            ->assertSee('Action')
            ->assertSee('Fight Club');
    }
}
