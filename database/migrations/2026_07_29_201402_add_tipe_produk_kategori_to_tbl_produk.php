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
        Schema::table('tbl_produk', function (Blueprint $table) {
            $table->string('tipe_produk', 50)->nullable()->default('premium')->after('status');
            $table->string('kategori', 100)->nullable()->after('tipe_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_produk', function (Blueprint $table) {
            $table->dropColumn(['tipe_produk', 'kategori']);
        });
    }
};
