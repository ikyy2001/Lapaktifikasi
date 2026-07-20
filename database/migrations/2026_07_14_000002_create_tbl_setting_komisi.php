<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_setting_komisi', function (Blueprint $table) {
            $table->id();
            $table->decimal('komisi_default', 5, 2)->default(10.00);
            $table->timestamp('updated_at')->nullable();
        });

        DB::table('tbl_setting_komisi')->insert([
            'komisi_default' => 10.00,
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_setting_komisi');
    }
};
