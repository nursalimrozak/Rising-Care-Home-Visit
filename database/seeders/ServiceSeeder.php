<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Models\Membership;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Perawatan Umum', 'description' => 'Layanan perawatan kesehatan umum', 'icon' => 'medical-bag'],
            ['name' => 'Fisioterapi', 'description' => 'Layanan terapi fisik dan rehabilitasi', 'icon' => 'therapy'],
            ['name' => 'Perawatan Luka', 'description' => 'Perawatan dan penanganan luka', 'icon' => 'bandage'],
        ];

        foreach ($categories as $catData) {
            $category = ServiceCategory::create($catData);

            // Create services for each category
            $this->createServicesForCategory($category);
        }
    }

    private function createServicesForCategory($category)
    {
        $memberships = Membership::all();

        $services = [
            [
                'name' => 'Pemeriksaan Kesehatan Rutin',
                'description' => 'Pemeriksaan kesehatan menyeluruh termasuk tekanan darah, gula darah, dan konsultasi',
                'service_type' => 'both',
                'duration_minutes' => 60,
                'base_price' => 150000,
            ],
            [
                'name' => 'Perawatan Pasca Operasi',
                'description' => 'Perawatan dan monitoring pasien pasca operasi di rumah',
                'service_type' => 'home_visit',
                'duration_minutes' => 90,
                'base_price' => 250000,
            ],
        ];

        foreach ($services as $serviceData) {
            $basePrice = $serviceData['base_price'];
            unset($serviceData['base_price']);
            
            $serviceData['category_id'] = $category->id;
            $service = Service::create($serviceData);

            // Create prices for each membership
            foreach ($memberships as $membership) {
                $discount = $membership->discount_percentage / 100;
                $price = $basePrice * (1 - $discount);

                ServicePrice::create([
                    'service_id' => $service->id,
                    'membership_id' => $membership->id,
                    'price' => $price,
                ]);
            }
        }
    }
}
