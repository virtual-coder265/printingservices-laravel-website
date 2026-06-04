<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Erp\ErpSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PushOrderToErp implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function handle(ErpSyncService $erpSyncService): void
    {
        $erpSyncService->pushOrder($this->order);
    }
}
