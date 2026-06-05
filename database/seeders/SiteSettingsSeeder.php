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

        $homepageSections = [
            'hero_slides',
            'highlights',
            'overview',
            'stats',
            'values',
            'featured_images',
            'trust_metrics',
            'school',
            'contact',
            'footer',
        ];

        foreach ($homepageSections as $section) {
            if (! empty($homepage[$section])) {
                SiteSetting::set('homepage', $section, $homepage[$section]);
            }
        }

        if (! empty($homepage['catalogue'])) {
            SiteSetting::set('homepage', 'catalogue', array_intersect_key(
                $homepage['catalogue'],
                array_flip(['eyebrow', 'title', 'lead', 'image_primary', 'image_secondary'])
            ));
        }
    }
}
