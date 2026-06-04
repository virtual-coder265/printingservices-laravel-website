<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $homepage = config('homepage');

        SiteSetting::set('utility', 'contact', [
            'phone' => $homepage['utility']['phone'] ?? '',
            'phone_href' => $homepage['utility']['phone_href'] ?? '',
            'email' => $homepage['utility']['email'] ?? '',
            'email_href' => $homepage['utility']['email_href'] ?? '',
            'hours' => $homepage['utility']['hours'] ?? '',
            'whatsapp' => $homepage['utility']['whatsapp'] ?? '',
            'notice' => $homepage['utility']['notice'] ?? '',
        ]);

        SiteSetting::set('utility', 'social_links', $homepage['utility']['social_links'] ?? []);
        SiteSetting::set('brand', 'info', $homepage['brand'] ?? []);
        SiteSetting::set('meta', 'info', $homepage['meta'] ?? []);
    }
}
