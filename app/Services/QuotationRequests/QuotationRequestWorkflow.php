<?php

namespace App\Services\QuotationRequests;

use App\Enums\QuotationRequestStatus;
use App\Models\QuotationRequest;
use App\Models\User;
use App\Services\Erp\QuotationRequestImportService;
use Illuminate\Support\Facades\DB;

class QuotationRequestWorkflow
{
    public function __construct(
        protected QuotationRequestEventLogger $eventLogger,
        protected QuotationRequestImportService $erpImport,
    ) {}

    public function approve(QuotationRequest $request, User $actor): void
    {
        DB::transaction(function () use ($request, $actor): void {
            $request->update([
                'status' => QuotationRequestStatus::Approved,
                'reviewed_at' => now(),
                'reviewed_by' => $actor->id,
            ]);

            $this->eventLogger->log($request, 'approved', $actor);
        });
    }

    public function reject(QuotationRequest $request, User $actor, string $reason): void
    {
        DB::transaction(function () use ($request, $actor, $reason): void {
            $request->update([
                'status' => QuotationRequestStatus::Rejected,
                'rejection_reason' => $reason,
                'reviewed_at' => now(),
                'reviewed_by' => $actor->id,
            ]);

            $this->eventLogger->log($request, 'rejected', $actor, ['reason' => $reason]);
        });
    }

    public function markSpam(QuotationRequest $request, User $actor): void
    {
        $request->update(['status' => QuotationRequestStatus::Spam]);
        $this->eventLogger->log($request, 'marked_spam', $actor);
    }

    public function sendToErp(QuotationRequest $request, User $actor): bool
    {
        $success = $this->erpImport->import($request, $actor->id);

        if ($success) {
            $request->refresh();
            $this->eventLogger->log($request, 'erp_sent', $actor, [
                'erp_estimation_id' => $request->erp_estimation_id,
            ]);
        }

        return $success;
    }

    public function updateAdminFields(QuotationRequest $request, User $actor, array $data): void
    {
        $request->update([
            'admin_notes' => $data['admin_notes'] ?? $request->admin_notes,
            'priority' => $data['priority'] ?? $request->priority,
            'assigned_to' => $data['assigned_to'] ?? $request->assigned_to,
        ]);

        $this->eventLogger->log($request, 'note_updated', $actor, [
            'priority' => $request->priority,
        ]);
    }
}
