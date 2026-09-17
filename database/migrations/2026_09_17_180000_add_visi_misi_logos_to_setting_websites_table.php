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
                if (!Schema::hasColumn('setting_websites', 'visi_logo_path')) {
                    $table->string('visi_logo_path')->nullable()->after('auth_hero_path');
                }
                if (!Schema::hasColumn('setting_websites', 'misi_logo_path')) {
                    $table->string('misi_logo_path')->nullable()->after('visi_logo_path');
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
                if (Schema::hasColumn('setting_websites', 'visi_logo_path')) {
                    $table->dropColumn('visi_logo_path');
                }
                if (Schema::hasColumn('setting_websites', 'misi_logo_path')) {
                    $table->dropColumn('misi_logo_path');
                }
            });
        }
    }
};
