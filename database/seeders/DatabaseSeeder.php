<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CustomerModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        DB::table('tbl_roles')->insert([
            ['id' => 1, 'role' => 'admin'],
            ['id' => 2, 'role' => 'costumer'], // matching original spelling 'costumer'
        ]);

        // 2. Seed default Users (Admin and Customers from original SQL dump)
        $admin = User::create([
            'id' => 1,
            'name' => 'Galih Anggoro Prasetya',
            'email' => 'g4lihanggoro@gmail.com',
            'password' => Hash::make('admin12345'), // Default password
            'profile_picture' => 'avatar-admin.png',
            'role_id' => 1,
        ]);

        $customer1 = User::create([
            'id' => 2,
            'name' => 'Makoto Makimura',
            'email' => 'makoto@mail.com',
            'password' => Hash::make('password123'),
            'profile_picture' => 'avatar-4.png',
            'role_id' => 2,
        ]);

        CustomerModel::create([
            'user_id' => $customer1->id,
            'nomor_telepon' => '084567892345',
        ]);

        $customer2 = User::create([
            'id' => 3,
            'name' => 'Kazuma Kiryu',
            'email' => 'kazuma@mail.com',
            'password' => Hash::make('password123'),
            'profile_picture' => 'avatar-5.png',
            'role_id' => 2,
        ]);

        CustomerModel::create([
            'user_id' => $customer2->id,
            'nomor_telepon' => '086723546789',
        ]);

        // 3. Call Legacy and Premium Seeders
        $this->call(LegacyTablesSeeder::class);
        $this->call(PremiumAccountSeeder::class);
    }
}
