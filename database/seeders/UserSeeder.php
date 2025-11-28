<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Occupation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Superadmin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@risingcare.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Admin Staff
        User::create([
            'name' => 'Admin Staff',
            'email' => 'admin@risingcare.com',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'role' => 'admin_staff',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Petugas
        User::create([
            'name' => 'Petugas 1',
            'email' => 'petugas@risingcare.com',
            'phone' => '081234567892',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Customer
        $occupation = Occupation::where('name', 'Mahasiswa')->first();
        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@risingcare.com',
            'phone' => '081234567893',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'occupation_id' => $occupation->id,
            'membership_id' => $occupation->membership_id,
            'loyalty_points' => 500,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
