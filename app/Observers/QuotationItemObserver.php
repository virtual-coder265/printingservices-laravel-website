<?php

namespace App\Observers;

use App\Models\Quotation;
use App\Models\QuotationItem;

class QuotationItemObserver
{
    public function saved(QuotationItem $item): void
    {
        $item->recalculateLine();
        $item->quotation?->recalculateTotals();
    }

    public function deleted(QuotationItem $item): void
    {
        $quotation = Quotation::find($item->quotation_id);
        $quotation?->recalculateTotals();
    }
}
