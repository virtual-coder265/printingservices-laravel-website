<?php

namespace App\Services\QuotationRequests;

use App\Models\QuotationRequest;
use App\Models\QuotationRequestEvent;
use App\Models\User;

class QuotationRequestEventLogger
{
    public function log(QuotationRequest $request, string $event, ?User $user = null, ?array $meta = null): QuotationRequestEvent
    {
        return QuotationRequestEvent::create([
            'quotation_request_id' => $request->id,
            'event' => $event,
            'user_id' => $user?->id,
            'meta_json' => $meta,
            'created_at' => now(),
        ]);
    }
}
