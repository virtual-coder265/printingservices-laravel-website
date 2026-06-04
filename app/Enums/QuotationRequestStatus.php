<?php

namespace App\Enums;

enum QuotationRequestStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case SentToErp = 'sent_to_erp';
    case ErpFailed = 'erp_failed';
    case Spam = 'spam';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::SentToErp => 'Sent to ERP',
            self::ErpFailed => 'ERP failed',
            self::Spam => 'Spam',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'info',
            self::Rejected => 'danger',
            self::SentToErp => 'success',
            self::ErpFailed => 'danger',
            self::Spam => 'gray',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Rejected, self::SentToErp, self::Spam], true);
    }
}
