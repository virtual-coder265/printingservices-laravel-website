<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtworkFile extends Model
{
    protected $fillable = [
        'print_job_id',
        'quotation_id',
        'uploaded_by_user_id',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'is_approved',
        'review_notes',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'is_approved' => 'boolean',
        ];
    }

    public function printJob(): BelongsTo
    {
        return $this->belongsTo(PrintJob::class);
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
