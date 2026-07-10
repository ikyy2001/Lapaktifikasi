<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Models\StokAkun;
use App\Enums\StokStatus;

class PremiumAccountSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Spotify product
        $spotify = Produk::create([
            'nama_produk' => 'Spotify',
            'deskripsi' => 'Aplikasi streaming musik premium tanpa iklan.',
            'gambar' => 'spotify-logo.png',
            'status' => 'aktif',
        ]);

        // 2. 3 Tipe Layanan
        $tipeLayananList = [
            'Private' => 'Akun khusus untuk 1 pengguna saja.',
            'Sharing' => 'Akun bersama yang dibagi dengan beberapa pengguna.',
            'Family' => 'Paket keluarga dengan slot terpisah.'
        ];

        // Prices map: [Tipe][Varian]
        $prices = [
            'Private' => [
                '1 Bulan' => 35000,
                '3 Bulan' => 95000,
            ],
            'Sharing' => [
                '1 Bulan' => 15000,
                '3 Bulan' => 40000,
            ],
            'Family' => [
                '1 Bulan' => 25000,
                '3 Bulan' => 70000,
            ],
        ];

        foreach ($tipeLayananList as $namaTipe => $deskripsiTipe) {
            $tipe = TipeLayanan::create([
                'id_produk' => $spotify->id_produk,
                'nama_tipe' => $namaTipe,
                'status' => 'aktif',
            ]);

            // 3. Each tipe has 2 varian
            $varianList = [
                [
                    'nama_varian' => '1 Bulan',
                    'durasi_hari' => 30,
                    'harga' => $prices[$namaTipe]['1 Bulan'],
                    'deskripsi' => "Paket durasi 30 hari untuk tipe {$namaTipe}.",
                ],
                [
                    'nama_varian' => '3 Bulan',
                    'durasi_hari' => 90,
                    'harga' => $prices[$namaTipe]['3 Bulan'],
                    'deskripsi' => "Paket durasi 90 hari untuk tipe {$namaTipe}.",
                ]
            ];

            foreach ($varianList as $varianData) {
                $varian = VarianLayanan::create([
                    'id_tipe' => $tipe->id_tipe,
                    'nama_varian' => $varianData['nama_varian'],
                    'durasi_hari' => $varianData['durasi_hari'],
                    'harga' => $varianData['harga'],
                    'deskripsi' => $varianData['deskripsi'],
                    'status' => 'aktif',
                ]);

                // 4. Fill each varian with 5-10 dummy stok_akun
                $stokCount = rand(5, 10);
                for ($i = 1; $i <= $stokCount; $i++) {
                    $slug = strtolower(str_replace(' ', '', $namaTipe)) . '-' . str_replace(' ', '', $varianData['nama_varian']) . '-' . $i;
                    StokAkun::create([
                        'id_varian' => $varian->id_varian,
                        'email_username' => "user-{$slug}@example.com",
                        'password_encrypted' => "pass-{$slug}-12345", // Automatically encrypted via Model cast!
                        'catatan' => "Catatan akun dummy premium {$namaTipe} {$varianData['nama_varian']} slot #{$i}",
                        'status' => StokStatus::TERSEDIA,
                    ]);
                }
            }
        }
    }
}
