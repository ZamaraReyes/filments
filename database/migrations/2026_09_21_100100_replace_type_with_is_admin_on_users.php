<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * users.type (1 = administrator) becomes the boolean users.is_admin.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('password');
            });
        }

        if (Schema::hasColumn('users', 'type')) {
            DB::table('users')->where('type', 1)->update(['is_admin' => true]);

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedTinyInteger('type')->default(0)->after('password');
            });
        }

        if (Schema::hasColumn('users', 'is_admin')) {
            DB::table('users')->where('is_admin', true)->update(['type' => 1]);

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }
    }
};
