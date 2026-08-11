<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SettingWebsite;

class SettingWebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingWebsite::create([
            'site_name' => 'Lapaktifikasi',
            'site_description' => 'Platform Jasa Digital Terbaik',
            'logo_path' => null,
            'favicon_path' => null,
            'contact_email' => 'admin@lapaktifikasi.com',
            'contact_phone' => '081234567890',
            'address' => 'Indonesia',
        ]);
    }
}
