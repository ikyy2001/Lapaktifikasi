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
        Schema::table('tbl_toko', function (Blueprint $table) {
            $table->decimal('rating_rata_rata', 3, 2)->default(0.00)->after('status');
            $table->integer('jumlah_review')->default(0)->after('rating_rata_rata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_toko', function (Blueprint $table) {
            $table->dropColumn(['rating_rata_rata', 'jumlah_review']);
        });
    }
};
