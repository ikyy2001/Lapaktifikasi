<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('setting_websites')) {
            Schema::table('setting_websites', function (Blueprint $table) {
                if (!Schema::hasColumn('setting_websites', 'auth_hero_path')) {
                    $table->string('auth_hero_path')->nullable()->after('favicon_path');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('setting_websites')) {
            Schema::table('setting_websites', function (Blueprint $table) {
                if (Schema::hasColumn('setting_websites', 'auth_hero_path')) {
                    $table->dropColumn('auth_hero_path');
                }
            });
        }
    }
};
