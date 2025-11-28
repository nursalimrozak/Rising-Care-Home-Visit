<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $memberships = [
            [
                'name' => 'Gold',
                'level' => 1,
                'discount_percentage' => 15.00,
                'description' => 'Membership premium dengan diskon 15%',
                'color' => '#FFD700',
                'is_active' => true,
            ],
            [
                'name' => 'Silver',
                'level' => 2,
                'discount_percentage' => 10.00,
                'description' => 'Membership menengah dengan diskon 10%',
                'color' => '#C0C0C0',
                'is_active' => true,
            ],
            [
                'name' => 'Bronze',
                'level' => 3,
                'discount_percentage' => 5.00,
                'description' => 'Membership dasar dengan diskon 5%',
                'color' => '#CD7F32',
                'is_active' => true,
            ],
        ];

        foreach ($memberships as $membership) {
            Membership::create($membership);
        }
    }
}
