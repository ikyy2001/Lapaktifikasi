<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE tbl_seller_badge MODIFY COLUMN kriteria_tipe VARCHAR(100) NOT NULL DEFAULT 'custom_admin'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE tbl_seller_badge MODIFY COLUMN kriteria_tipe ENUM('rating_minimal', 'kecepatan_restock', 'response_time', 'lama_bergabung', 'volume_transaksi', 'custom_admin') NOT NULL DEFAULT 'rating_minimal'");
        }
    }
};
