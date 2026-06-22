<?php

namespace App\Enums;

enum StudentEnrollmentStatus: string
{
    case Pending = 'pending';
    case Reviewed = 'reviewed';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Waitlisted = 'waitlisted';
    case Withdrawn = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Reviewed => 'Reviewed',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::Waitlisted => 'Waitlisted',
            self::Withdrawn => 'Withdrawn',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Reviewed => 'info',
            self::Accepted => 'success',
            self::Rejected => 'danger',
            self::Waitlisted => 'gray',
            self::Withdrawn => 'gray',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Accepted, self::Rejected, self::Withdrawn], true);
    }
}
