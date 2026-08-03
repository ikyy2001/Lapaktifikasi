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
        Schema::create('tbl_seller_badge', function (Blueprint $table) {
            $table->id('id_badge');
            $table->string('nama_badge');
            $table->text('deskripsi')->nullable();
            $table->enum('kriteria_tipe', ['rating_minimal', 'kecepatan_restock', 'response_time', 'lama_bergabung', 'volume_transaksi']);
            $table->decimal('kriteria_nilai', 15, 2);
            $table->string('icon_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_seller_badge');
    }
};
