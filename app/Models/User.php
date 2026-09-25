<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * `is_admin` is deliberately not fillable so nobody can promote themselves.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function favoriteMovies(): HasMany
    {
        return $this->hasMany(FavoriteMovie::class, 'user_id');
    }

    public function favoriteActors(): HasMany
    {
        return $this->hasMany(FavoriteActor::class, 'user_id');
    }

    public function dislikedMovies(): HasMany
    {
        return $this->hasMany(DislikeMovie::class, 'user_id');
    }

    public function genres(): HasMany
    {
        return $this->hasMany(FavoriteGenre::class, 'user_id');
    }
}
