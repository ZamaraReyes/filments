<?php

namespace Tests\Feature;

use App\Models\FavoriteGenre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.tmdb.api_key' => 'test-key']);

        Http::fake([
            '*/genre/movie/list*' => Http::response(['genres' => [
                ['id' => 28, 'name' => 'Action'],
                ['id' => 18, 'name' => 'Drama'],
            ]]),
            '*' => Http::response(['results' => [], 'genres' => []]),
        ]);
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_view_a_users_detail_page(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create(['name' => 'Target User']);

        $this->actingAs($admin)->get(route('users.show', $target->id))
            ->assertOk()
            ->assertSee('Target User');
    }

    public function test_admin_can_edit_a_users_name_email_and_genres(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);

        $this->actingAs($admin)->patch(route('components.alert-change', $target->id), [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'genres' => [28],
        ])->assertRedirect();

        $target->refresh();
        $this->assertSame('New Name', $target->name);
        $this->assertSame('new@example.com', $target->email);
        $this->assertSame([28], FavoriteGenre::where('user_id', $target->id)->pluck('genre_id')->all());
    }

    public function test_editing_a_user_does_not_require_a_password(): void
    {
        // Regresión: el formulario de edición (alert-change.blade.php) nunca ha
        // enviado un campo de contraseña, así que exigirla como "required"
        // hacía que esta acción fallara siempre.
        $admin = $this->admin();
        $target = User::factory()->create();
        $originalPassword = $target->password;

        $this->actingAs($admin)->patch(route('components.alert-change', $target->id), [
            'name' => 'Still No Password',
            'email' => $target->email,
            'genres' => [28],
        ])->assertSessionDoesntHaveErrors();

        $this->assertSame($originalPassword, $target->fresh()->password);
    }

    public function test_admin_can_delete_another_user_but_not_themselves(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create();

        $this->actingAs($admin)->delete(route('components.delete', $admin->id))
            ->assertRedirect()
            ->assertSessionHas('status', 'No puedes eliminar tu propia cuenta');
        $this->assertNotNull($admin->fresh());

        $this->actingAs($admin)->delete(route('components.delete', $target->id))
            ->assertRedirect(route('profile.management'));
        $this->assertNull(User::find($target->id));
    }

    public function test_non_admins_cannot_manage_users(): void
    {
        $regular = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($regular)->get(route('users.show', $target->id))->assertForbidden();
        $this->actingAs($regular)->patch(route('components.alert-change', $target->id), [])->assertForbidden();
        $this->actingAs($regular)->delete(route('components.delete', $target->id))->assertForbidden();

        $this->assertNotNull(User::find($target->id));
    }
}
