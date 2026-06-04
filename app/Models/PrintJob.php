<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrintJob extends Model
{
    protected $table = 'print_jobs';

    protected $fillable = [
        'reference',
        'quotation_id',
        'customer_profile_id',
        'assigned_to_user_id',
        'status',
        'target_delivery_date',
        'actual_delivery_date',
        'external_erp_id',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'target_delivery_date' => 'date',
            'actual_delivery_date' => 'date',
        ];
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function customerProfile(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(JobStatusLog::class);
    }

    public function artworkFiles(): HasMany
    {
        return $this->hasMany(ArtworkFile::class);
    }

    public static function generateReference(): string
    {
        $year = now()->format('Y');
        $last = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('JOB-%s-%04d', $year, $last);
    }
}
