<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@polmed.ac.id',
            'role' => 'super_admin',
            'unit' => 'Pusat TIK',
            'password' => Hash::make('admin123'),
        ]);

        // 2. Admin (Bagian Pengadaan)
        User::create([
            'name' => 'Admin Pengadaan',
            'email' => 'admin@polmed.ac.id',
            'role' => 'admin',
            'unit' => 'Unit Pengadaan',
            'password' => Hash::make('admin123'),
        ]);

        // 3. User Biasa (Unit/Jurusan)
        User::create([
            'name' => 'Kaprodi TI',
            'email' => 'manajemeninformatika@polmed.ac.id',
            'role' => 'user',
            'unit' => 'Manajemen Informatika',
            'password' => Hash::make('12345678'),
        ]);
    }
}