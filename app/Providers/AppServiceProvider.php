<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureRateLimiting();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject("Email verification")
                ->line("We're happy you signed up for Filments. To start exploring Filments, please confirm your email address.")
                ->action("Verify Now", $url)
                ->line("This verification link will expire in 60 minutes.");
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // favoritos y dislikes: 60 acciones por minuto y usuario
        RateLimiter::for('favorites', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // registro y recuperación de contraseña: 6 intentos por minuto, IP y ruta
        RateLimiter::for('auth-sensitive', function (Request $request) {
            return Limit::perMinute(6)->by($request->ip().'|'.$request->path());
        });
    }
}
