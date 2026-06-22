<?php

namespace App\Services\StudentEnrollments;

use App\Models\RegistrationPeriod;

class RegistrationCalendarService
{
    public function getOpenPeriod(): ?RegistrationPeriod
    {
        return RegistrationPeriod::currentOpen();
    }

    public function isEnrollmentOpen(): bool
    {
        return $this->getOpenPeriod() !== null;
    }

    public function getUpcomingPeriod(): ?RegistrationPeriod
    {
        return RegistrationPeriod::query()
            ->where('is_published', true)
            ->where('opens_at', '>', now())
            ->orderBy('opens_at')
            ->first();
    }
}
