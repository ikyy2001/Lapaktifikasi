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
        Schema::create('tbl_customer_tier_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_tier_lama')->nullable();
            $table->unsignedBigInteger('id_tier_baru');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_customer')->references('id')->on('tbl_customer')->onDelete('cascade');
            $table->foreign('id_tier_lama')->references('id_tier')->on('tbl_customer_tier')->onDelete('set null');
            $table->foreign('id_tier_baru')->references('id_tier')->on('tbl_customer_tier')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_customer_tier_log');
    }
};
