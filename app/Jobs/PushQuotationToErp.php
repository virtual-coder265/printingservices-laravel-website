<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Quotation;
use App\Services\Erp\ErpSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PushQuotationToErp implements ShouldQueue
{
    use Queueable;

    public function __construct(public Quotation $quotation) {}

    public function handle(ErpSyncService $erpSyncService): void
    {
        $erpSyncService->pushQuotation($this->quotation);
    }
}
