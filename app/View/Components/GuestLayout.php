<?php

namespace App\View\Components;

use App\Services\SiteContentService;
use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public ?string $title = null;

    public array $brand;

    public array $meta;

    public ?string $brandLogo;

    public bool $hasBrandLogo;

    public function __construct(SiteContentService $siteContent)
    {
        $page = $siteContent->mergeSettings(config('homepage', []));

        $this->brand = $page['brand'] ?? config('homepage.brand', []);
        $this->meta = $page['meta'] ?? config('homepage.meta', []);
        $this->brandLogo = $this->brand['logo'] ?? null;
        $this->hasBrandLogo = media_exists($this->brandLogo);
    }

    public function render(): View
    {
        return view('layouts.guest');
    }
}
