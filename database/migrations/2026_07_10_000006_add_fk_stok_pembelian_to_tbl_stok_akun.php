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
        Schema::table('tbl_stok_akun', function (Blueprint $table) {
            $table->foreign('id_pembelian', 'fk_stok_pembelian')->references('id_pembelian')->on('tbl_pembelian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_stok_akun', function (Blueprint $table) {
            $table->dropForeign('fk_stok_pembelian');
        });
    }
};
