<?php

namespace App\Services\Erp;

use App\Enums\QuotationRequestStatus;
use App\Models\QuotationRequest;
use App\Services\QuotationRequests\QuotationRequestEventLogger;
use App\Services\QuotationRequests\QuotationRequestPayloadBuilder;

/**
 * Sends approved website quotation requests to Press ERP (Phase 3+).
 * Currently stubs the HTTP call until the ERP import API is available.
 */
class QuotationRequestImportService
{
    public function __construct(
        protected QuotationRequestPayloadBuilder $payloadBuilder,
        protected QuotationRequestEventLogger $eventLogger,
    ) {}

    public function import(QuotationRequest $request, ?int $websiteUserId = null): bool
    {
        if (! config('erp.enabled') || ! config('erp.base_url')) {
            $request->update([
                'status' => QuotationRequestStatus::ErpFailed,
                'erp_import_error' => 'ERP integration is not configured. Set ERP_SYNC_ENABLED and ERP_BASE_URL when the Press ERP import API is ready.',
            ]);

            $this->eventLogger->log($request, 'erp_failed', null, [
                'reason' => 'erp_not_configured',
            ]);

            return false;
        }

        $body = array_merge(
            $this->payloadBuilder->mergeAdminContext($request),
            [
                'imported_by_website_user_id' => $websiteUserId,
            ]
        );

        // Phase 4: POST to config('erp.base_url') . config('quotation_requests.erp.import_endpoint')
        $request->update([
            'status' => QuotationRequestStatus::ErpFailed,
            'erp_import_error' => 'ERP import API not yet implemented on Press ERP.',
        ]);

        $this->eventLogger->log($request, 'erp_failed', null, [
            'endpoint' => config('quotation_requests.erp.import_endpoint'),
            'payload_preview' => ['external_id' => $body['external_id'] ?? null],
        ]);

        return false;
    }
}
