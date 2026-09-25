<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * users.type = 1 marks an administrator. The column already exists in
     * databases created before this migration, so only add it when missing.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedTinyInteger('type')->default(0)->after('password');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
