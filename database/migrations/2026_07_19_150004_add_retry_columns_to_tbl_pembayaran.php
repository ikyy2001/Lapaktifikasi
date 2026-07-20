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
            $table->integer('wa_retry_count')->default(0);
            $table->timestamp('wa_last_retry_at')->nullable();
            $table->unsignedBigInteger('wa_last_retry_by')->nullable();

            $table->foreign('wa_last_retry_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pembayaran', function (Blueprint $table) {
            $table->dropForeign(['wa_last_retry_by']);
            $table->dropColumn(['wa_retry_count', 'wa_last_retry_at', 'wa_last_retry_by']);
        });
    }
};
