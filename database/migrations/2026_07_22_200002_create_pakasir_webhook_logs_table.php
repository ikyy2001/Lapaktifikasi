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
        Schema::create('pakasir_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable()->index();
            $table->decimal('amount', 14, 2)->nullable();
            $table->string('status')->nullable();
            $table->json('payload');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakasir_webhook_logs');
    }
};
