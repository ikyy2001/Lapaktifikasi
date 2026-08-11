<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimoni;

class TestimoniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Andi Firmansyah',
                'role' => 'Siswa · Bogor',
                'rating' => 5,
                'comment' => 'Beli Source Code Laravel buat tugas akhir di Lapaktifikasi. Setelah bayar via QRIS, file ZIP langsung bisa di-download detik itu juga. Mantap!',
                'is_active' => true,
            ],
            [
                'name' => 'Hanifan Nurfauzi',
                'role' => 'Guru SMK PENUS · Bogor',
                'rating' => 5,
                'comment' => 'Sangat bangga ada platform kolaborasi seperti Lapaktifikasi di SMK Plus Pelita Nusantara. Sekarang siswa dan staff bisa memajang & menjual karya digital secara profesional.',
                'is_active' => true,
            ],
            [
                'name' => 'Iqbal Haris Juna',
                'role' => 'Pelanggan Aktif · Jakarta',
                'rating' => 5,
                'comment' => 'Proses checkout cepat, invoice masuk ke WhatsApp, dan riwayat belanja tersimpan rapi. Tim CS juga sangat ramah dan responsif!',
                'is_active' => true,
            ],
            [
                'name' => 'Rangga Wijaya',
                'role' => 'Freelancer · Bandung',
                'rating' => 5,
                'comment' => 'Sering order akun premium dan ebook tutorial di sini. Harga ramah di kantong dan pengiriman garansi seratus persen aman!',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            Testimoni::create($item);
        }
    }
}
