<?php

use App\Jobs\SyncProductsFromErp;
use App\Jobs\SyncServicesFromErp;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SyncProductsFromErp)
    ->daily()
    ->when(fn () => (bool) config('erp.enabled'));

Schedule::job(new SyncServicesFromErp)
    ->daily()
    ->when(fn () => (bool) config('erp.enabled'));
