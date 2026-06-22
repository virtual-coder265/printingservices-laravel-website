<?php

namespace App\Services\StudentEnrollments;

use App\Models\RegistrationPeriod;

class StudentEnrollmentPayloadBuilder
{
    public function buildFromInput(array $validated, string $externalId, bool $isEnrollmentOpen, ?RegistrationPeriod $period): array
    {
        $now = now()->toIso8601String();

        $payload = [
            'external_id' => $externalId,
            'submitted_at' => $now,
            'source' => 'company-website',
            'personal' => [
                'first_name' => $validated['personal']['first_name'],
                'last_name' => $validated['personal']['last_name'],
                'date_of_birth' => $validated['personal']['date_of_birth'] ?? null,
                'gender' => $validated['personal']['gender'] ?? null,
                'national_id' => $validated['personal']['national_id'] ?? null,
                'district' => $validated['personal']['district'] ?? null,
                'address' => $validated['personal']['address'] ?? null,
            ],
            'contact' => [
                'phone' => $validated['contact']['phone'],
                'emergency_name' => $validated['contact']['emergency_name'] ?? null,
                'emergency_phone' => $validated['contact']['emergency_phone'] ?? null,
            ],
            'education' => [
                'level' => $validated['education']['level'],
                'institution' => $validated['education']['institution'] ?? null,
                'year_obtained' => isset($validated['education']['year_obtained'])
                    ? (int) $validated['education']['year_obtained']
                    : null,
                'details' => $validated['education']['details'] ?? null,
            ],
            'consent' => [
                'accepted' => true,
                'accepted_at' => $now,
            ],
        ];

        if ($isEnrollmentOpen && $period) {
            $payload['registration'] = [
                'period_id' => $period->id,
                'period_name' => $period->name,
            ];
            $payload['experience'] = [
                'prior_printing_experience' => $validated['experience']['prior_printing_experience'] ?? null,
                'motivation' => $validated['experience']['motivation'] ?? null,
            ];
        }

        return $payload;
    }
}
