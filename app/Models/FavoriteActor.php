<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteActor extends Model
{
    use HasFactory;

    protected $table = 'favorites_actors';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tmdb_id',
        'name',
        'profile_path',
    ];
}
