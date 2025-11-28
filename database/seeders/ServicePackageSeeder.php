<?php

namespace Database\Seeders;

use App\Models\ServicePackage;
use Illuminate\Database\Seeder;

class ServicePackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Reguler',
                'visit_count' => 1,
                'validity_weeks' => 0, // Single visit, no expiry
                'description' => 'Paket sekali kunjungan untuk layanan kesehatan',
                'is_active' => true,
            ],
            [
                'name' => 'Eksekutif',
                'visit_count' => 4,
                'validity_weeks' => 3,
                'description' => 'Paket 4x kunjungan dalam 3 minggu',
                'is_active' => true,
            ],
            [
                'name' => 'VIP',
                'visit_count' => 6,
                'validity_weeks' => 4,
                'description' => 'Paket 6x kunjungan dalam 4 minggu',
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'visit_count' => 8,
                'validity_weeks' => 6,
                'description' => 'Paket 8x kunjungan dalam 6 minggu',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            ServicePackage::create($package);
        }
    }
}
