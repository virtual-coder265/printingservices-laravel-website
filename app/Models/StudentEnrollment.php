<?php

namespace App\Models;

use App\Enums\StudentEnrollmentStatus;
use App\Enums\StudentEnrollmentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEnrollment extends Model
{
    protected $fillable = [
        'external_id',
        'type',
        'registration_period_id',
        'status',
        'payload_json',
        'client_ip',
        'user_agent',
        'admin_notes',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'payload_json' => 'array',
            'type' => StudentEnrollmentType::class,
            'status' => StudentEnrollmentStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    public function registrationPeriod(): BelongsTo
    {
        return $this->belongsTo(RegistrationPeriod::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public static function generateExternalId(): string
    {
        $prefix = config('student_enrollments.reference_prefix', 'SEN');
        $year = now()->format('Y');
        $sequence = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('%s-%s-%05d', $prefix, $year, $sequence);
    }

    public function payload(string $key, mixed $default = null): mixed
    {
        return data_get($this->payload_json, $key, $default);
    }

    public function fullName(): string
    {
        $first = (string) $this->payload('personal.first_name', '');
        $last = (string) $this->payload('personal.last_name', '');

        return trim("{$first} {$last}") ?: '—';
    }

    public function phone(): string
    {
        return (string) $this->payload('contact.phone', '—');
    }

    public function highestQualification(): string
    {
        $level = (string) $this->payload('education.level', '');
        $label = config("student_enrollments.qualification_levels.{$level}", $level);

        return $label ?: '—';
    }

    public function getRouteKeyName(): string
    {
        return 'external_id';
    }
}
