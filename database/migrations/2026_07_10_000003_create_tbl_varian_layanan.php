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
        Schema::create('tbl_varian_layanan', function (Blueprint $table) {
            $table->increments('id_varian');
            $table->integer('id_tipe')->unsigned();
            $table->string('nama_varian', 50);
            $table->integer('durasi_hari')->unsigned();
            $table->decimal('harga', 12, 2);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->foreign('id_tipe')->references('id_tipe')->on('tbl_tipe_layanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_varian_layanan');
    }
};
