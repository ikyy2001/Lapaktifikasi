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
        Schema::table('tbl_customer', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tier_saat_ini')->nullable();
            $table->decimal('total_belanja_akumulasi', 15, 2)->default(0);
            $table->string('kode_referral')->unique()->nullable();
            $table->unsignedBigInteger('direferensikan_oleh')->nullable();
            $table->integer('jumlah_referral_sukses')->default(0);

            $table->foreign('id_tier_saat_ini')->references('id_tier')->on('tbl_customer_tier')->onDelete('set null');
            $table->foreign('direferensikan_oleh')->references('id')->on('tbl_customer')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_customer', function (Blueprint $table) {
            $table->dropForeign(['id_tier_saat_ini']);
            $table->dropForeign(['direferensikan_oleh']);
            $table->dropColumn([
                'id_tier_saat_ini',
                'total_belanja_akumulasi',
                'kode_referral',
                'direferensikan_oleh',
                'jumlah_referral_sukses'
            ]);
        });
    }
};
