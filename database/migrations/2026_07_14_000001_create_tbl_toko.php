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
        Schema::create('tbl_toko', function (Blueprint $table) {
            $table->bigIncrements('id_toko');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_toko', 150);
            $table->string('no_telp', 20);
            $table->string('akun_telegram', 100);
            $table->string('telegram_chat_id', 50)->nullable();
            $table->text('informasi_toko')->nullable();
            $table->string('logo_toko', 255)->nullable();
            $table->decimal('komisi_override', 5, 2)->nullable();
            $table->unsignedBigInteger('saldo')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_toko');
    }
};
