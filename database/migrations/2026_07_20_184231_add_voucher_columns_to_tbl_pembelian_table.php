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
        Schema::table('tbl_pembelian', function (Blueprint $table) {
            $table->unsignedBigInteger('id_voucher_dipakai')->nullable()->after('harga_saat_beli');
            $table->decimal('nominal_diskon', 15, 2)->default(0)->after('id_voucher_dipakai');

            $table->foreign('id_voucher_dipakai')->references('id_voucher')->on('tbl_voucher')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pembelian', function (Blueprint $table) {
            $table->dropForeign(['id_voucher_dipakai']);
            $table->dropColumn(['id_voucher_dipakai', 'nominal_diskon']);
        });
    }
};
