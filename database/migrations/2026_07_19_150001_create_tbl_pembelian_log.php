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
        Schema::create('tbl_pembelian_log', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->unsignedBigInteger('id_pembelian');
            $table->string('status_lama')->nullable();
            $table->string('status_baru');
            $table->string('sumber_perubahan');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_pembelian')
                  ->references('id_pembelian')
                  ->on('tbl_pembelian')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pembelian_log');
    }
};
