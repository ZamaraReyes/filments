<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('genres_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('id_genre');
            $table->string('name');
            $table->timestamps();

            $table->unique(['id_user', 'id_genre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genres_movies');
    }
};
