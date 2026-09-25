<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DislikeMovie extends Model
{
    use HasFactory;

    protected $table = 'dislike_movies';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tmdb_id',
        'title',
        'poster_path',
        'vote_average',
    ];
}
