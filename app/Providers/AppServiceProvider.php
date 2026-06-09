<?php

namespace App\Providers;

use App\Models\PrintJob;
use App\Models\QuotationItem;
use App\Observers\PrintJobObserver;
use App\Observers\QuotationItemObserver;
use App\Services\Erp\ErpClientInterface;
use App\Http\Responses\Auth\LoginResponse;
use App\Services\Erp\HttpErpClient;
use App\Services\Erp\NullErpClient;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LoginResponseContract::class, LoginResponse::class);

        $this->app->bind(ErpClientInterface::class, function () {
            if (config('erp.enabled') && config('erp.base_url')) {
                return new HttpErpClient;
            }

            return new NullErpClient;
        });
    }

    public function boot(): void
    {
        $this->configureApplicationUrl();
        $this->configureRateLimiting();

        PrintJob::observe(PrintJobObserver::class);
        QuotationItem::observe(QuotationItemObserver::class);

        Event::listen(Login::class, \App\Listeners\MergeGuestCart::class);
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('quotation-submit', function (Request $request) {
            $max = config('quotation_requests.rate_limit.max_attempts', 5);
            $minutes = config('quotation_requests.rate_limit.decay_minutes', 60);

            return Limit::perMinutes($minutes, $max)->by($request->ip());
        });
    }

    protected function configureApplicationUrl(): void
    {
        $appUrl = config('app.url');

        if (! $appUrl) {
            return;
        }

        $basePath = parse_url($appUrl, PHP_URL_PATH) ?: '';
        $basePath = rtrim($basePath, '/');

        if ($basePath !== '' && $basePath !== '/') {
            config(['session.path' => $basePath]);
        }

        $this->app->booted(function (): void {
            config([
                'livewire.asset_url' => url('/livewire/livewire.js'),
            ]);
        });
    }
}
