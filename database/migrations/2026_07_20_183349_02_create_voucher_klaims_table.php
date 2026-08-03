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
        Schema::dropIfExists('tbl_voucher_klaim');
        Schema::create('tbl_voucher_klaim', function (Blueprint $table) {
            $table->id('id_klaim');
            $table->unsignedBigInteger('id_voucher');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_pembelian')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_voucher')->references('id_voucher')->on('tbl_voucher')->onDelete('cascade');
            $table->foreign('id_customer')->references('id')->on('tbl_customer')->onDelete('cascade');
            $table->foreign('id_pembelian')->references('id_pembelian')->on('tbl_pembelian')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_voucher_klaim');
    }
};
