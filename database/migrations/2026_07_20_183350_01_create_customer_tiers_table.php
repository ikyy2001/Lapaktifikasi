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
        Schema::create('tbl_customer_tier', function (Blueprint $table) {
            $table->id('id_tier');
            $table->string('nama_tier');
            $table->integer('urutan');
            $table->decimal('minimal_belanja', 15, 2)->default(0);
            $table->string('warna_tema')->nullable();
            $table->string('icon_path')->nullable();
            $table->decimal('benefit_cashback_persen', 5, 2)->default(0);
            $table->json('benefit_deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_customer_tier');
    }
};
