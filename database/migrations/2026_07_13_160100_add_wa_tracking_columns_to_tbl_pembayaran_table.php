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
        Schema::table('tbl_pembayaran', function (Blueprint $table) {
            $table->timestamp('wa_sent_at')->nullable()->after('tanggal_bayar');
            $table->text('wa_response')->nullable()->after('wa_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pembayaran', function (Blueprint $table) {
            $table->dropColumn(['wa_sent_at', 'wa_response']);
        });
    }
};
