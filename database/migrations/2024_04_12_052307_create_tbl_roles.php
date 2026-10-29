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
        Schema::create('tbl_roles', function (Blueprint $table) {
            $table->id();
            $table->string('role', 20);
        });

        // Otomatis isi default roles agar tidak perlu seeder manual saat migrate
        DB::table('tbl_roles')->insertOrIgnore([
            ['id' => 1, 'role' => 'admin'],
            ['id' => 2, 'role' => 'costumer'],
            ['id' => 3, 'role' => 'seller'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_roles');
    }
};
