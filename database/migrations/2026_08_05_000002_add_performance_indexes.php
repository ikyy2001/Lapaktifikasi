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
        // 1. Index for tbl_beli_produk
        Schema::table('tbl_beli_produk', function (Blueprint $table) {
            $table->index('order_id', 'idx_beli_produk_order_id');
            $table->index('tanggal_transaksi', 'idx_beli_produk_tanggal');
            $table->index(['status', 'tanggal_transaksi'], 'idx_beli_produk_status_tanggal');
        });

        // 2. Index for tbl_pembayaran_zip
        Schema::table('tbl_pembayaran_zip', function (Blueprint $table) {
            $table->index('order_id', 'idx_pembayaran_zip_order_id');
        });

        // 3. Index for tbl_pembelian
        Schema::table('tbl_pembelian', function (Blueprint $table) {
            $table->index('order_id', 'idx_pembelian_order_id');
        });

        // 4. Index for tbl_toko
        Schema::table('tbl_toko', function (Blueprint $table) {
            $table->index('user_id', 'idx_toko_user_id');
        });

        // 5. Index for tbl_customer
        Schema::table('tbl_customer', function (Blueprint $table) {
            $table->index('user_id', 'idx_customer_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_beli_produk', function (Blueprint $table) {
            $table->dropIndex('idx_beli_produk_order_id');
            $table->dropIndex('idx_beli_produk_tanggal');
            $table->dropIndex('idx_beli_produk_status_tanggal');
        });

        Schema::table('tbl_pembayaran_zip', function (Blueprint $table) {
            $table->dropIndex('idx_pembayaran_zip_order_id');
        });

        Schema::table('tbl_pembelian', function (Blueprint $table) {
            $table->dropIndex('idx_pembelian_order_id');
        });

        Schema::table('tbl_toko', function (Blueprint $table) {
            $table->dropIndex('idx_toko_user_id');
        });

        Schema::table('tbl_customer', function (Blueprint $table) {
            $table->dropIndex('idx_customer_user_id');
        });
    }
};
