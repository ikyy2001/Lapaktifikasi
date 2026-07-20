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
        Schema::create('tbl_mutasi_saldo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_toko');
            $table->enum('tipe', ['kredit_penjualan', 'potong_withdraw', 'penyesuaian_admin']);
            $table->bigInteger('nominal');
            $table->unsignedBigInteger('saldo_akhir');
            $table->string('keterangan', 255)->nullable();
            $table->unsignedBigInteger('id_beli_produk')->nullable();
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('id_toko')->references('id_toko')->on('tbl_toko')->onDelete('cascade');
            $table->foreign('id_beli_produk')->references('id')->on('tbl_beli_produk')->onDelete('set null');
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_mutasi_saldo');
    }
};
