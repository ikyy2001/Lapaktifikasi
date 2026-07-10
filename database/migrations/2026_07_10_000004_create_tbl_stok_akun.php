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
        Schema::create('tbl_stok_akun', function (Blueprint $table) {
            $table->bigIncrements('id_stok');
            $table->integer('id_varian')->unsigned();
            $table->string('email_username', 150);
            $table->text('password_encrypted');
            $table->text('catatan')->nullable();
            $table->enum('status', ['tersedia', 'reserved', 'terjual'])->default('tersedia');
            $table->bigInteger('id_pembelian')->unsigned()->nullable();
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('reserved_expired_at')->nullable();
            $table->timestamp('tanggal_terjual')->nullable();
            $table->timestamps();

            $table->foreign('id_varian')->references('id_varian')->on('tbl_varian_layanan')->onDelete('cascade');
            $table->index(['id_varian', 'status'], 'idx_stok_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_stok_akun');
    }
};
