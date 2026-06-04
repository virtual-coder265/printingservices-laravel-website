<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Controlled production', 'slug' => 'controlled-production', 'icon' => 'shield'],
            ['name' => 'Public information', 'slug' => 'public-information', 'icon' => 'publication'],
            ['name' => 'Commercial jobs', 'slug' => 'commercial-jobs', 'icon' => 'press'],
            ['name' => 'Pre-press support', 'slug' => 'pre-press-support', 'icon' => 'design'],
            ['name' => 'Presentation quality', 'slug' => 'presentation-quality', 'icon' => 'finish'],
            ['name' => 'Skills development', 'slug' => 'skills-development', 'icon' => 'school'],
        ];

        $categoryMap = [];
        foreach ($categories as $index => $cat) {
            $categoryMap[$cat['name']] = ServiceCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['sort_order' => $index + 1, 'is_active' => true])
            );
        }

        $services = config('homepage.services', []);
        foreach ($services as $index => $service) {
            $category = $categoryMap[$service['category']] ?? ServiceCategory::first();
            Service::updateOrCreate(
                ['slug' => Str::slug($service['title'])],
                [
                    'service_category_id' => $category->id,
                    'title' => $service['title'],
                    'description' => $service['description'],
                    'short_description' => implode(' ', $service['points'] ?? []),
                    'base_price' => null,
                    'is_active' => true,
                    'is_featured' => $index < 3,
                    'requires_quotation' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        $products = config('homepage.catalogue.products', []);
        $prices = [
            'Banners (Vinyl)' => 45000,
            'Brochures (Tri-Fold)' => 8500,
            'Business Cards' => 12000,
            'Calendars' => 25000,
            'College Certificates' => 35000,
            'Flyers (A5)' => 3500,
            'Posters (A2)' => 7500,
        ];

        foreach ($products as $index => $product) {
            $sku = 'PRD-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    'sku' => $sku,
                    'title' => $product['name'],
                    'type' => $product['type'],
                    'note' => $product['note'],
                    'description' => $product['note'],
                    'price' => $prices[$product['name']] ?? 5000,
                    'vat_rate' => 17.50,
                    'stock_status' => 'in_stock',
                    'is_active' => true,
                    'is_featured' => $index < 4,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
