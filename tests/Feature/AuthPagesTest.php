<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

/**
 * Cubre las páginas GET de autenticación (login, registro, recuperar
 * contraseña). Ninguna otra prueba las ejercitaba, así que un fallo como el
 * de "Trait AuthenticatesUsers not found" (al quitar laravel/ui, que sigue
 * proveyendo estos traits pese al namespace Illuminate\Foundation\Auth\*)
 * podía colarse sin que la suite lo detectara.
 */
class AuthPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.tmdb.api_key' => 'test-key']);

        Http::fake([
            '*/genre/movie/list*' => Http::response(['genres' => [['id' => 28, 'name' => 'Action']]]),
            '*/movie/upcoming*' => Http::response(['results' => [[
                'id' => 550, 'title' => 'Fight Club', 'backdrop_path' => '/bd.jpg',
            ]]]),
            '*' => Http::response(['results' => [], 'genres' => []]),
        ]);
    }

    public function test_login_page_renders(): void
    {
        $this->get(route('auth.login'))->assertOk();
    }

    public function test_register_page_renders(): void
    {
        $this->get(route('auth.register'))->assertOk();
    }

    public function test_forget_password_page_renders(): void
    {
        $this->get(route('ForgetPasswordGet'))->assertOk();
    }

    public function test_reset_password_page_renders(): void
    {
        $token = Password::broker()->createToken(\App\Models\User::factory()->create());

        $this->get(route('ResetPasswordGet', $token))->assertOk();
    }
}
