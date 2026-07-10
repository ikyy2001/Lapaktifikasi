<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed tbl_produk_zip
        DB::table('tbl_produk_zip')->insert([
            [
                'id' => 1,
                'nama' => 'Laravel 9 CRUD & Autentikasi',
                'deskripsi' => 'Program CRUD beserta autentikasi sederhana dengan Laravel 9 dan MySQL. Di khususkan untuk mahasiswa.',
                'harga' => 350000,
                'status' => 'tersedia',
                'file' => 'laravel9-crud-dan-autentikasi.zip',
                'created_at' => '2024-07-31 13:19:47',
                'updated_at' => '2024-07-31 14:23:00',
            ],
            [
                'id' => 2,
                'nama' => 'Web Film',
                'deskripsi' => 'Website film di bangun dengan PHP dan MySQL.',
                'harga' => 3000000,
                'status' => 'tersedia',
                'file' => 'web-film.zip',
                'created_at' => '2024-07-31 13:36:38',
                'updated_at' => '2024-07-31 13:36:38',
            ],
            [
                'id' => 3,
                'nama' => 'Website Makanan',
                'deskripsi' => 'Web makanan di bangun dengan JavasScript.',
                'harga' => 1000000,
                'status' => 'tersedia',
                'file' => 'web-makanan.zip',
                'created_at' => '2024-07-31 14:22:40',
                'updated_at' => '2024-07-31 14:22:48',
            ],
        ]);

        // 2. Seed tbl_beli_produk
        DB::table('tbl_beli_produk')->insert([
            [
                'id' => 1,
                'qty' => 1,
                'status' => 'success',
                'order_id' => '603974',
                'produk_id' => 1,
                'user_id' => 2,
                'tanggal_transaksi' => '2024-07-31',
            ],
            [
                'id' => 2,
                'qty' => 1,
                'status' => 'success',
                'order_id' => '833673',
                'produk_id' => 2,
                'user_id' => 3,
                'tanggal_transaksi' => '2024-07-31',
            ],
            [
                'id' => 3,
                'qty' => 1,
                'status' => 'success',
                'order_id' => '808176',
                'produk_id' => 3,
                'user_id' => 3,
                'tanggal_transaksi' => '2024-07-31',
            ],
        ]);

        // 3. Seed tbl_pembayaran_zip
        DB::table('tbl_pembayaran_zip')->insert([
            [
                'id' => 1,
                'total' => 350000,
                'metode' => 'credit_card',
                'order_id' => '603974',
            ],
            [
                'id' => 2,
                'total' => 3000000,
                'metode' => 'credit_card',
                'order_id' => '833673',
            ],
            [
                'id' => 3,
                'total' => 1000000,
                'metode' => 'credit_card',
                'order_id' => '808176',
            ],
        ]);

        // 4. Seed tbl_produk_terjual
        DB::table('tbl_produk_terjual')->insert([
            [
                'id' => 1,
                'jumlah_terjual' => 1,
                'produk_id' => 1,
            ],
            [
                'id' => 2,
                'jumlah_terjual' => 1,
                'produk_id' => 2,
            ],
            [
                'id' => 3,
                'jumlah_terjual' => 1,
                'produk_id' => 3,
            ],
        ]);

        // 5. Seed tbl_screenshots_produk
        DB::table('tbl_screenshots_produk')->insert([
            [
                'id' => 1,
                'folder' => 'extract_screenshots-laravel9-crud-dan-autentikasi_1722432392',
                'produk_id' => 1,
            ],
            [
                'id' => 2,
                'folder' => 'extract_screenshots-web-film_1722435185',
                'produk_id' => 2,
            ],
            [
                'id' => 3,
                'folder' => 'extract_web-makanan_1722435904',
                'produk_id' => 3,
            ],
        ]);
    }
}
