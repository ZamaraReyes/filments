<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites_actors', function (Blueprint $table) {
            $table->id('id_favorite');
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('id'); // TMDB person id
            $table->string('name', 100);
            $table->string('profile_path', 500)->nullable();
            $table->timestamps();

            $table->unique(['id_user', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites_actors');
    }
};
