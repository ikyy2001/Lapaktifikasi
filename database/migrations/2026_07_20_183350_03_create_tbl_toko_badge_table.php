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
        Schema::create('tbl_toko_badge', function (Blueprint $table) {
            $table->unsignedBigInteger('id_toko');
            $table->unsignedBigInteger('id_badge');
            $table->timestamp('diperoleh_pada')->useCurrent();

            $table->primary(['id_toko', 'id_badge']);
            $table->foreign('id_toko')->references('id_toko')->on('tbl_toko')->onDelete('cascade');
            $table->foreign('id_badge')->references('id_badge')->on('tbl_seller_badge')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_toko_badge');
    }
};
