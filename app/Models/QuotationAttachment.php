<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class QuotationAttachment extends Model
{
    protected $fillable = [
        'quotation_request_id',
        'original_name',
        'stored_path',
        'mime',
        'size_bytes',
    ];

    public function quotationRequest(): BelongsTo
    {
        return $this->belongsTo(QuotationRequest::class);
    }

    public function disk(): string
    {
        return config('quotation_requests.uploads.disk', 'local');
    }

    public function existsOnDisk(): bool
    {
        return Storage::disk($this->disk())->exists($this->stored_path);
    }
}
