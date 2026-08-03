<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Toko;
use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Models\StokAkun;
use App\Enums\StokStatus;

class DummySellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            // 1. Create User Seller
            $user = User::create([
                'name' => 'Seller Dummy ' . $i,
                'email' => 'seller' . $i . '@mail.com',
                'password' => Hash::make('password123'),
                'profile_picture' => 'avatar-' . rand(1, 5) . '.png',
                'role_id' => 3, // Seller
            ]);

            // 2. Create Toko
            $toko = Toko::create([
                'user_id' => $user->id,
                'nama_toko' => 'Toko Premium ' . $i,
                'no_telp' => '0812345678' . $i,
                'akun_telegram' => '@seller' . $i,
                'telegram_chat_id' => '12345' . $i,
                'informasi_toko' => 'Toko resmi seller ' . $i . ' melayani pembelian akun premium',
                'logo_toko' => 'default_store.png',
                'saldo' => 0,
                'status' => 'aktif',
            ]);

            // 3. Create Produk
            $produk = Produk::create([
                'nama_produk' => 'Akun Netflix Premium ' . $i,
                'deskripsi' => 'Akun netflix premium bergaransi dari seller ' . $i,
                'gambar' => 'default_produk.png',
                'status' => 'aktif',
                'tipe_produk' => 'premium',
                'kategori' => 'Streaming',
                'id_toko' => $toko->id_toko,
            ]);

            // 4. Create Tipe Layanan
            $tipeLayanan = TipeLayanan::create([
                'id_produk' => $produk->id_produk,
                'nama_tipe' => 'Sharing Account',
                'status' => 'aktif',
            ]);

            // 5. Create Varian Layanan
            $varianLayanan = VarianLayanan::create([
                'id_tipe' => $tipeLayanan->id_tipe,
                'nama_varian' => '1 Bulan (1 Profil)',
                'durasi_hari' => 30,
                'harga' => 35000,
                'deskripsi' => 'Akses 1 profil untuk 1 device, anti screen limit.',
                'status' => 'aktif',
            ]);

            // 6. Create Stok Akun
            for ($j = 1; $j <= 5; $j++) {
                StokAkun::create([
                    'id_varian' => $varianLayanan->id_varian,
                    'email_username' => 'netflix_' . $i . '_' . $j . '@premium.com',
                    'password_encrypted' => 'rahasia' . $j,
                    'catatan' => 'Profil ke-' . $j,
                    'status' => StokStatus::TERSEDIA,
                ]);
            }
        }
    }
}
