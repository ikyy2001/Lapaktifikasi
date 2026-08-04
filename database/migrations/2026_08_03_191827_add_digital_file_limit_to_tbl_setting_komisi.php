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
        Schema::table('tbl_setting_komisi', function (Blueprint $table) {
            $table->integer('digital_file_limit_mb')->default(250)->after('komisi_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_setting_komisi', function (Blueprint $table) {
            $table->dropColumn('digital_file_limit_mb');
        });
    }
};
