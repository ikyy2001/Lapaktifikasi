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
        Schema::create('tbl_review', function (Blueprint $table) {
            $table->bigIncrements('id_review');
            $table->unsignedBigInteger('id_pembelian')->unique();
            $table->unsignedBigInteger('id_toko');
            $table->unsignedBigInteger('id_customer');
            $table->tinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->foreign('id_pembelian')
                  ->references('id_pembelian')
                  ->on('tbl_pembelian')
                  ->onDelete('cascade');

            $table->foreign('id_toko')
                  ->references('id_toko')
                  ->on('tbl_toko')
                  ->onDelete('cascade');

            $table->foreign('id_customer')
                  ->references('id')
                  ->on('tbl_customer')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_review');
    }
};
