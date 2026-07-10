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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tbl_pembayaran');
        Schema::enableForeignKeyConstraints();

        Schema::create('tbl_pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id_pembayaran');
            $table->bigInteger('id_pembelian')->unsigned();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->decimal('jumlah_dibayar', 12, 2);
            $table->string('midtrans_transaction_id', 100)->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();

            $table->foreign('id_pembelian')->references('id_pembelian')->on('tbl_pembelian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pembayaran');
    }
};
