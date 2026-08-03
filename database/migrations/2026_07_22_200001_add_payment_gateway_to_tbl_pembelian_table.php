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
            $table->enum('payment_gateway', ['midtrans', 'pakasir'])->default('midtrans')->after('status');
            $table->string('gateway_reference')->nullable()->after('payment_gateway');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pembelian', function (Blueprint $table) {
            $table->dropColumn(['payment_gateway', 'gateway_reference']);
        });
    }
};
