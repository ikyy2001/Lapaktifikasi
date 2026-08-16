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
                if (!Schema::hasColumn('setting_websites', 'is_midtrans_active')) {
                    $table->boolean('is_midtrans_active')->default(true)->after('address');
                }
                if (!Schema::hasColumn('setting_websites', 'is_tripay_active')) {
                    $table->boolean('is_tripay_active')->default(true)->after('is_midtrans_active');
                }
                if (!Schema::hasColumn('setting_websites', 'is_pakasir_active')) {
                    $table->boolean('is_pakasir_active')->default(true)->after('is_tripay_active');
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
                if (Schema::hasColumn('setting_websites', 'is_midtrans_active')) {
                    $table->dropColumn('is_midtrans_active');
                }
                if (Schema::hasColumn('setting_websites', 'is_tripay_active')) {
                    $table->dropColumn('is_tripay_active');
                }
                if (Schema::hasColumn('setting_websites', 'is_pakasir_active')) {
                    $table->dropColumn('is_pakasir_active');
                }
            });
        }
    }
};
