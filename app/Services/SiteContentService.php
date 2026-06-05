<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use App\Models\SiteSetting;

class SiteContentService
{
    public function getPageData(string $currentPage): array
    {
        $config = config('homepage');
        $page = $this->mergeSettings($config);

        $page['services'] = $this->getServicesForPublic();
        $page['catalogue'] = array_merge($page['catalogue'] ?? [], [
            'products' => $this->getProductsForPublic(),
        ]);

        return [
            'page' => $page,
            'currentPage' => $currentPage,
        ];
    }

    public function mergeSettings(array $config): array
    {
        $utilityContact = SiteSetting::get('utility', 'contact');
        if ($utilityContact) {
            $config['utility'] = array_merge($config['utility'] ?? [], $utilityContact);
        }

        $socialLinks = SiteSetting::get('utility', 'social_links');
        if ($socialLinks) {
            $config['utility']['social_links'] = $socialLinks;
        }

        $brand = SiteSetting::get('brand', 'info');
        if ($brand) {
            $config['brand'] = array_merge($config['brand'] ?? [], $brand);
        }

        $meta = SiteSetting::get('meta', 'info');
        if ($meta) {
            $config['meta'] = array_merge($config['meta'] ?? [], $meta);
        }

        return $this->mergeHomepageSections($config);
    }

    public function mergeHomepageSections(array $config): array
    {
        $sectionKeys = [
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

        foreach ($sectionKeys as $key) {
            $value = SiteSetting::get('homepage', $key);
            if ($value !== null) {
                $config[$key] = $value;
            }
        }

        $catalogue = SiteSetting::get('homepage', 'catalogue');
        if ($catalogue !== null) {
            $config['catalogue'] = array_merge($config['catalogue'] ?? [], $catalogue);
        }

        return $config;
    }

    public function getServicesForPublic(): array
    {
        $services = Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('category')
            ->get();

        if ($services->isEmpty()) {
            return config('homepage.services', []);
        }

        return $services->map(fn (Service $service) => [
            'icon' => $service->category?->icon ?? 'press',
            'category' => $service->category?->name ?? 'Services',
            'title' => $service->title,
            'description' => $service->description,
            'points' => $service->short_description
                ? array_filter(array_map('trim', explode('.', $service->short_description)))
                : [],
            'slug' => $service->slug,
            'id' => $service->id,
        ])->all();
    }

    public function getProductsForPublic(): array
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($products->isEmpty()) {
            return config('homepage.catalogue.products', []);
        }

        return $products->map(fn (Product $product) => [
            'id' => $product->id,
            'name' => $product->title,
            'slug' => $product->slug,
            'type' => $product->type,
            'note' => $product->note ?? $product->description,
            'price' => $product->price,
            'sku' => $product->sku,
        ])->all();
    }

    public function getPublishedPages()
    {
        return Page::query()->where('is_published', true)->orderBy('title')->get();
    }
}
