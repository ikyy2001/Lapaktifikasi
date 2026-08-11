<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'nama_tier' => 'standart',
                'urutan' => 1,
                'minimal_belanja' => 0,
                'warna_tema' => '#cd7f32',
                'icon_path' => null,
                'benefit_cashback_persen' => 0,
                'benefit_deskripsi' => json_encode(['Akses ke produk reguler']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tier' => 'Gold',
                'urutan' => 2,
                'minimal_belanja' => 500000,
                'warna_tema' => '#ffd700',
                'icon_path' => null,
                'benefit_cashback_persen' => 1.5,
                'benefit_deskripsi' => json_encode(['Cashback 1.5%', 'Prioritas support reguler']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tier' => 'Diamond',
                'urutan' => 3,
                'minimal_belanja' => 2000000,
                'warna_tema' => '#b9f2ff',
                'icon_path' => null,
                'benefit_cashback_persen' => 3,
                'benefit_deskripsi' => json_encode(['Cashback 3%', 'Prioritas support tinggi', 'Voucher eksklusif mingguan']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tier' => 'Sultan',
                'urutan' => 4,
                'minimal_belanja' => 5000000,
                'warna_tema' => '#ffeb3b',
                'icon_path' => null,
                'benefit_cashback_persen' => 5,
                'benefit_deskripsi' => json_encode(['Cashback 5%', 'Support VIP 24/7', 'Akses fitur early access', 'Gratis biaya admin (s&k berlaku)']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tbl_customer_tier')->delete();
        DB::table('tbl_customer_tier')->insert($tiers);    }
}
