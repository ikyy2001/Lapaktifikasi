<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    /**
     * Safely drop tables without foreign key constraint violations in MySQL 8.0+.
     */
    protected function cleanUpTables(array $tables = []): void
    {
        Schema::disableForeignKeyConstraints();

        $fks = [
            ['tbl_stok_akun', 'fk_stok_pembelian'],
            ['tbl_review', 'tbl_review_id_pembelian_foreign'],
            ['tbl_pembayaran', 'tbl_pembayaran_id_pembelian_foreign'],
            ['voucher_klaims', 'voucher_klaims_id_pembelian_foreign'],
            ['tbl_pembelian_log', 'tbl_pembelian_log_id_pembelian_foreign'],
        ];

        foreach ($fks as [$table, $fk]) {
            try {
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk}`");
            } catch (\Throwable $e) {
                // Ignore if table or foreign key does not exist
            }
        }

        // Drop child tables first to satisfy MySQL 8.0+ drop table restrictions
        $preDrop = ['tbl_review', 'tbl_pembayaran', 'voucher_klaims', 'tbl_stok_akun', 'tbl_pembelian_log'];
        foreach ($preDrop as $table) {
            try {
                Schema::dropIfExists($table);
            } catch (\Throwable $e) {}
        }

        foreach ($tables as $table) {
            try {
                Schema::dropIfExists($table);
            } catch (\Throwable $e) {}
        }

        Schema::enableForeignKeyConstraints();
    }
}
