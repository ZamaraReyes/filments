<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The table already exists in databases created before this migration
     * (App\Models\DislikeMovie writes to it), so only create it when missing.
     */
    public function up(): void
    {
        if (! Schema::hasTable('dislike_movies')) {
            Schema::create('dislike_movies', function (Blueprint $table) {
                $table->id('id_favorite');
                $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
                $table->unsignedInteger('id'); // TMDB movie id
                $table->string('title', 100);
                $table->string('poster_path', 500)->nullable();
                $table->decimal('vote_average', 4, 2)->nullable();
                $table->timestamps();

                $table->unique(['id_user', 'id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dislike_movies');
    }
};
