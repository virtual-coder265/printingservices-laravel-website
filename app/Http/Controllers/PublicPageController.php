<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\SiteContentService;

class PublicPageController extends Controller
{
    public function __construct(
        protected SiteContentService $siteContent,
        protected CartService $cartService,
    ) {}

    private function pageData(string $currentPage): array
    {
        $data = $this->siteContent->getPageData($currentPage);
        $data['page']['utility']['cart_count'] = $this->cartService->count();

        return $data;
    }

    public function home()
    {
        return view('welcome', $this->pageData('home'));
    }

    public function services()
    {
        return view('pages.services', $this->pageData('services'));
    }

    public function products()
    {
        return view('pages.products', $this->pageData('products'));
    }

    public function training()
    {
        return view('pages.training', $this->pageData('training'));
    }

    public function about()
    {
        return view('pages.about', $this->pageData('about'));
    }

    public function contact()
    {
        return view('pages.contact', $this->pageData('contact'));
    }

    public function quotation()
    {
        $data = $this->pageData('quotation');
        $data['quoteConfig'] = [
            'jobTypes' => config('quotation_requests.job_types'),
            'jobTypesRequiringSize' => config('quotation_requests.job_types_requiring_size'),
            'jobTypesRequiringPages' => config('quotation_requests.job_types_requiring_pages'),
            'colourOptions' => config('quotation_requests.colour_options'),
            'deliveryOptions' => config('quotation_requests.delivery_options'),
            'preferredContactOptions' => config('quotation_requests.preferred_contact_options'),
            'artworkStatusOptions' => config('quotation_requests.artwork_status_options'),
            'finishingOptions' => config('quotation_requests.finishing_options'),
            'paperRoles' => config('quotation_requests.paper_roles'),
            'maxFiles' => config('quotation_requests.uploads.max_files'),
            'maxFileSizeMb' => (int) (config('quotation_requests.uploads.max_size_kb') / 1024),
        ];

        return view('pages.quotation', $data);
    }

    public function cart()
    {
        $data = $this->pageData('cart');
        $data['cart'] = $this->cartService->getCart()->load('items.product');

        return view('pages.cart', $data);
    }

    public function wishlist()
    {
        return view('pages.wishlist', $this->pageData('wishlist'));
    }
}
