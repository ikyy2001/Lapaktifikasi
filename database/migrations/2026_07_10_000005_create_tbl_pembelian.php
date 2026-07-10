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
        Schema::create('tbl_pembelian', function (Blueprint $table) {
            $table->bigIncrements('id_pembelian');
            $table->string('order_id', 30)->unique();
            $table->bigInteger('id_customer')->unsigned();
            $table->integer('id_varian')->unsigned();
            $table->bigInteger('id_stok')->unsigned()->nullable();
            $table->decimal('harga_saat_beli', 12, 2);
            $table->enum('status', ['pending', 'success', 'expired', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('reserved_until')->nullable();
            $table->timestamps();

            $table->foreign('id_customer')->references('id')->on('tbl_customer');
            $table->foreign('id_varian')->references('id_varian')->on('tbl_varian_layanan');
            $table->foreign('id_stok')->references('id_stok')->on('tbl_stok_akun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pembelian');
    }
};
