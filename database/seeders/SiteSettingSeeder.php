<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'RisingCare', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Layanan Kesehatan Terpercaya', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => '/images/logo.png', 'type' => 'image', 'group' => 'general'],
            
            // Contact
            ['key' => 'contact_phone', 'value' => '0812-3456-7890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'info@risingcare.com', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Jl. Kesehatan No. 123, Jakarta', 'type' => 'textarea', 'group' => 'contact'],
            
            // Social Media
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/risingcare', 'type' => 'text', 'group' => 'social_media'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/risingcare', 'type' => 'text', 'group' => 'social_media'],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/risingcare', 'type' => 'text', 'group' => 'social_media'],
            
            // Finance
            ['key' => 'payout_fee', 'value' => '5000', 'type' => 'number', 'group' => 'finance'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }
}
