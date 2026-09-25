<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Databases created before Laravel 10 still have `password_resets`.
     * Fresh installs already get `password_reset_tokens` from the create migration.
     */
    public function up(): void
    {
        if (Schema::hasTable('password_resets') && ! Schema::hasTable('password_reset_tokens')) {
            Schema::rename('password_resets', 'password_reset_tokens');
        }
    }

    public function down(): void
    {
        //
    }
};
