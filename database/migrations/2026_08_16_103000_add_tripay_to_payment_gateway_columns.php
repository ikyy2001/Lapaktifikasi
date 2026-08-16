<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            if (Schema::hasColumn('tbl_pembelian', 'payment_gateway')) {
                DB::statement("ALTER TABLE tbl_pembelian MODIFY COLUMN payment_gateway VARCHAR(50) NOT NULL DEFAULT 'midtrans'");
            }

            if (Schema::hasColumn('tbl_pembayaran', 'payment_gateway')) {
                DB::statement("ALTER TABLE tbl_pembayaran MODIFY COLUMN payment_gateway VARCHAR(50) NOT NULL DEFAULT 'midtrans'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            if (Schema::hasColumn('tbl_pembelian', 'payment_gateway')) {
                DB::statement("ALTER TABLE tbl_pembelian MODIFY COLUMN payment_gateway ENUM('midtrans', 'pakasir') NOT NULL DEFAULT 'midtrans'");
            }

            if (Schema::hasColumn('tbl_pembayaran', 'payment_gateway')) {
                DB::statement("ALTER TABLE tbl_pembayaran MODIFY COLUMN payment_gateway ENUM('midtrans', 'pakasir') NOT NULL DEFAULT 'midtrans'");
            }
        }
    }
};
