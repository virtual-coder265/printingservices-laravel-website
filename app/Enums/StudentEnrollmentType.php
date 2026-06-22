<?php

namespace App\Enums;

enum StudentEnrollmentType: string
{
    case Interest = 'interest';
    case Enrollment = 'enrollment';

    public function label(): string
    {
        return match ($this) {
            self::Interest => 'Interest',
            self::Enrollment => 'Enrollment',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Interest => 'gray',
            self::Enrollment => 'info',
        };
    }
}
