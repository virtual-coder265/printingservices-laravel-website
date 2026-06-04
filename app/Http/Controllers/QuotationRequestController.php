<?php

namespace App\Http\Controllers;

use App\Enums\QuotationRequestStatus;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\QuotationRequest;
use App\Services\QuotationRequests\QuotationAttachmentStorage;
use App\Services\QuotationRequests\QuotationRequestEventLogger;
use App\Services\CartService;
use App\Services\QuotationRequests\QuotationRequestPayloadBuilder;
use App\Services\SiteContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuotationRequestController extends Controller
{
    public function __construct(
        protected SiteContentService $siteContent,
        protected CartService $cartService,
    ) {}

    public function store(
        StoreQuotationRequest $request,
        QuotationRequestPayloadBuilder $payloadBuilder,
        QuotationAttachmentStorage $attachmentStorage,
        QuotationRequestEventLogger $eventLogger,
    ): RedirectResponse {
        $externalId = QuotationRequest::generateExternalId();
        $payload = $payloadBuilder->buildFromInput($request->validated(), $externalId);

        $quotationRequest = DB::transaction(function () use ($request, $payload, $externalId, $attachmentStorage, $eventLogger) {
            $record = QuotationRequest::create([
                'external_id' => $externalId,
                'status' => QuotationRequestStatus::Pending,
                'payload_json' => $payload,
                'client_ip' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
            ]);

            $files = $request->file('attachments', []);
            if ($files) {
                $attachmentStorage->storeMany($record, $files);
                $attachmentStorage->refreshPayloadAttachments($record);
            }

            $eventLogger->log($record, 'submitted');

            return $record;
        });

        return redirect()
            ->route('quotation.confirmation', $quotationRequest)
            ->with('external_id', $quotationRequest->external_id);
    }

    public function confirmation(QuotationRequest $quotationRequest): View
    {
        $data = $this->siteContent->getPageData('quotation');
        $data['page']['utility']['cart_count'] = $this->cartService->count();
        $data['quotationRequest'] = $quotationRequest;

        return view('pages.quotation-confirmation', $data);
    }
}
