<?php

namespace App\Jobs;

use App\Services\Erp\ErpSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncProductsFromErp implements ShouldQueue
{
    use Queueable;

    public function handle(ErpSyncService $erpSyncService): void
    {
        $erpSyncService->syncProductsFromErp();
    }
}
