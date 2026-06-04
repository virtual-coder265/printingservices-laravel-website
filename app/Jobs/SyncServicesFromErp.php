<?php

namespace App\Jobs;

use App\Services\Erp\ErpSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncServicesFromErp implements ShouldQueue
{
    use Queueable;

    public function handle(ErpSyncService $erpSyncService): void
    {
        $erpSyncService->syncServicesFromErp();
    }
}
