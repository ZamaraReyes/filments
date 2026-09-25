<?php

namespace Tests\Feature;

use App\Models\DislikeMovie;
use App\Models\FavoriteActor;
use App\Models\FavoriteGenre;
use App\Models\FavoriteMovie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FavoritesFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.tmdb.api_key' => 'test-key']);

        Http::fake([
            '*/genre/movie/list*' => Http::response(['genres' => [['id' => 28, 'name' => 'Action'], ['id' => 18, 'name' => 'Drama']]]),
            '*/movie/550*' => Http::response(['id' => 550, 'title' => 'Fight Club', 'poster_path' => '/fc.jpg', 'vote_average' => 8.4]),
            '*/person/287*' => Http::response(['id' => 287, 'name' => 'Brad Pitt', 'profile_path' => '/bp.jpg']),
            '*/discover/movie*' => Http::response(['results' => [[
                'id' => 999, 'title' => 'Recommended One', 'genre_ids' => [28], 'backdrop_path' => '/b.jpg',
                'poster_path' => '/p.jpg', 'vote_count' => 900, 'vote_average' => 7.1, 'release_date' => '2024-05-05',
            ]]]),
        ]);
    }

    private function user(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    public function test_adding_and_removing_a_favorite_movie_uses_user_id_and_tmdb_id(): void
    {
        $user = $this->user();

        $this->actingAs($user)->post(route('components.favorite', 550))->assertRedirect();

        $favorite = FavoriteMovie::firstOrFail();
        $this->assertSame($user->id, $favorite->user_id);
        $this->assertSame(550, $favorite->tmdb_id);
        $this->assertSame('Fight Club', $favorite->title);
        $this->assertSame([550], $user->favoriteMovies->pluck('tmdb_id')->all());

        $this->actingAs($user)->delete(route('components.dislike', 550))->assertRedirect();
        $this->assertSame(0, FavoriteMovie::count());
    }

    public function test_removing_only_affects_the_current_users_favorite(): void
    {
        $owner = $this->user();
        $other = $this->user();
        FavoriteMovie::create(['user_id' => $owner->id, 'tmdb_id' => 550, 'title' => 'Fight Club']);

        $this->actingAs($other)->delete(route('components.dislike', 550));

        $this->assertSame(1, FavoriteMovie::count());
    }

    public function test_favorite_actor_is_stored_with_tmdb_id(): void
    {
        $user = $this->user();

        $this->actingAs($user)->post(route('components.favoritee', 287))->assertRedirect();

        $this->assertSame([287], $user->favoriteActors()->pluck('tmdb_id')->all());
    }

    public function test_recommendations_page_renders_with_the_new_columns(): void
    {
        $user = $this->user();
        FavoriteGenre::create(['user_id' => $user->id, 'genre_id' => 28, 'name' => 'Action']);
        FavoriteMovie::create(['user_id' => $user->id, 'tmdb_id' => 550, 'title' => 'Fight Club']);
        DislikeMovie::create(['user_id' => $user->id, 'tmdb_id' => 1000, 'title' => 'Disliked']);

        $this->actingAs($user)->get(route('profile.my-recomendation'))
            ->assertOk()
            ->assertSee('Recommended One');
    }

    public function test_favorites_pages_render(): void
    {
        $user = $this->user();
        FavoriteMovie::create(['user_id' => $user->id, 'tmdb_id' => 550, 'title' => 'Fight Club', 'poster_path' => '/fc.jpg', 'vote_average' => 8.4]);
        FavoriteActor::create(['user_id' => $user->id, 'tmdb_id' => 287, 'name' => 'Brad Pitt', 'profile_path' => '/bp.jpg']);

        $this->actingAs($user)->get(route('profile.my-movies'))->assertOk()->assertSee('Fight Club');
        $this->actingAs($user)->get(route('profile.my-actors'))->assertOk()->assertSee('Brad Pitt');
    }

    public function test_only_admins_can_open_the_admin_area(): void
    {
        $regular = $this->user();
        $admin = $this->user();
        $admin->forceFill(['is_admin' => true])->save();

        $this->assertFalse($regular->fresh()->isAdmin());
        $this->assertTrue($admin->fresh()->isAdmin());

        $this->actingAs($regular)->get(route('profile.management'))->assertForbidden();
        $this->actingAs($admin)->get(route('profile.management'))->assertOk();
    }

    public function test_is_admin_cannot_be_mass_assigned(): void
    {
        $user = User::create(['name' => 'x', 'email' => 'x@example.com', 'password' => 'secret123', 'is_admin' => true]);

        $this->assertFalse($user->fresh()->isAdmin());
    }
}
