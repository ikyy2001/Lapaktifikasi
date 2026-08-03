<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellerBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'nama_badge' => 'Seller Terpercaya',
                'deskripsi' => 'Toko dengan rating tinggi secara konsisten yang memberikan layanan luar biasa.',
                'kriteria_tipe' => 'rating_minimal',
                'kriteria_nilai' => 4.8,
                'icon_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_badge' => 'Respon Cepat',
                'deskripsi' => 'Toko yang sangat cepat membalas pesan dan mengurus pesanan pembeli.',
                'kriteria_tipe' => 'response_time',
                'kriteria_nilai' => 15, // 15 menit
                'icon_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_badge' => 'Transaksi Tinggi',
                'deskripsi' => 'Toko dengan volume transaksi yang sangat tinggi dan dipercaya banyak orang.',
                'kriteria_tipe' => 'volume_transaksi',
                'kriteria_nilai' => 1000, // 1000 transaksi sukses
                'icon_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_badge' => 'Stok Selalu Ready',
                'deskripsi' => 'Toko yang jarang kehabisan stok dan terus melakukan update produk.',
                'kriteria_tipe' => 'kecepatan_restock',
                'kriteria_nilai' => 24, // restock dalam 24 jam
                'icon_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tbl_seller_badge')->insert($badges);
    }
}
