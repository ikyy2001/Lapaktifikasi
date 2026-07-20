<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!DB::table('tbl_roles')->where('id', 3)->exists()) {
            DB::table('tbl_roles')->insert([
                'id' => 3,
                'role' => 'seller',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tbl_roles')->where('id', 3)->delete();
    }
};
