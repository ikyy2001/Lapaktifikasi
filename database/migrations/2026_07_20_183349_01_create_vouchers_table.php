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
        Schema::create('tbl_voucher', function (Blueprint $table) {
            $table->id('id_voucher');
            $table->string('kode')->unique();
            $table->enum('tipe_diskon', ['persen', 'nominal']);
            $table->decimal('nilai_diskon', 15, 2);
            $table->decimal('maksimal_potongan', 15, 2)->nullable();
            $table->decimal('minimal_transaksi', 15, 2)->default(0);
            $table->integer('kuota_total')->nullable();
            $table->integer('kuota_terpakai')->default(0);
            $table->timestamp('berlaku_dari')->nullable();
            $table->timestamp('berlaku_sampai')->nullable();
            $table->enum('scope', ['global', 'toko_spesifik']);
            $table->unsignedBigInteger('id_toko')->nullable();
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('id_toko')->references('id_toko')->on('tbl_toko')->onDelete('cascade');
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_voucher');
    }
};
