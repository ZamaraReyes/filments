<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Naming conventions: user_id / genre_id for foreign keys, tmdb_id for the TMDB id
     * and a plain `id` primary key (it used to be `id_favorite`, with the TMDB id in `id`).
     * Each step is skipped when already applied, so it works on old and fresh databases.
     */
    private array $tmdbTables = ['favorites_movies', 'favorites_actors', 'dislike_movies'];

    public function up(): void
    {
        foreach ($this->tmdbTables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            if (Schema::hasColumn($table, 'id_favorite')) {
                // the TMDB id must leave `id` before the primary key can take that name
                $this->rename($table, 'id', 'tmdb_id');
                $this->rename($table, 'id_favorite', 'id');
            }

            $this->rename($table, 'id_user', 'user_id');
        }

        if (Schema::hasTable('genres_movies')) {
            $this->rename('genres_movies', 'id_user', 'user_id');
            $this->rename('genres_movies', 'id_genre', 'genre_id');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('genres_movies')) {
            $this->rename('genres_movies', 'genre_id', 'id_genre');
            $this->rename('genres_movies', 'user_id', 'id_user');
        }

        foreach ($this->tmdbTables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $this->rename($table, 'user_id', 'id_user');

            if (Schema::hasColumn($table, 'tmdb_id')) {
                $this->rename($table, 'id', 'id_favorite');
                $this->rename($table, 'tmdb_id', 'id');
            }
        }
    }

    private function rename(string $table, string $from, string $to): void
    {
        if (Schema::hasColumn($table, $from) && ! Schema::hasColumn($table, $to)) {
            Schema::table($table, fn (Blueprint $t) => $t->renameColumn($from, $to));
        }
    }
};
