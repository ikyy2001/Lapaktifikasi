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
        Schema::dropIfExists('tbl_produk');
        Schema::enableForeignKeyConstraints();

        Schema::create('tbl_produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->string('nama_produk', 100);
            $table->text('deskripsi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_produk');
    }
};
