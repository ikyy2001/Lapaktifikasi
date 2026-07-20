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
        Schema::table('tbl_produk_zip', function (Blueprint $table) {
            $table->unsignedBigInteger('id_toko')->nullable()->after('id');
            $table->foreign('id_toko')->references('id_toko')->on('tbl_toko')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_produk_zip', function (Blueprint $table) {
            $table->dropForeign(['id_toko']);
            $table->dropColumn('id_toko');
        });
    }
};
