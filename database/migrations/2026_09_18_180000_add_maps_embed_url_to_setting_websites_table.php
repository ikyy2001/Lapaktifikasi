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
                if (!Schema::hasColumn('setting_websites', 'maps_embed_url')) {
                    $table->text('maps_embed_url')->nullable()->after('address');
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
                if (Schema::hasColumn('setting_websites', 'maps_embed_url')) {
                    $table->dropColumn('maps_embed_url');
                }
            });
        }
    }
};
