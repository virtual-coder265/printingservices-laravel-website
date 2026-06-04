<?php

namespace App\Policies;

use App\Models\QuotationRequest;
use App\Models\User;

class QuotationRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_quotation_request');
    }

    public function view(User $user, QuotationRequest $quotationRequest): bool
    {
        return $user->can('view_quotation_request');
    }

    public function update(User $user, QuotationRequest $quotationRequest): bool
    {
        return $user->can('update_quotation_request');
    }

    public function approve(User $user, QuotationRequest $quotationRequest): bool
    {
        return $user->can('approve_quotation_request') && $quotationRequest->canApprove();
    }

    public function reject(User $user, QuotationRequest $quotationRequest): bool
    {
        return $user->can('reject_quotation_request') && $quotationRequest->canReject();
    }

    public function sendToErp(User $user, QuotationRequest $quotationRequest): bool
    {
        return $user->can('send_quotation_request_to_erp')
            && ($quotationRequest->canSendToErp() || $quotationRequest->canRetryErp());
    }

    public function markSpam(User $user, QuotationRequest $quotationRequest): bool
    {
        return $user->can('mark_quotation_request_spam') && $quotationRequest->canMarkSpam();
    }
}
