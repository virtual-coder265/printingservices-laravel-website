<?php

namespace App\Models;

use App\Enums\QuotationRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuotationRequest extends Model
{
    protected $fillable = [
        'external_id',
        'status',
        'payload_json',
        'client_ip',
        'user_agent',
        'admin_notes',
        'assigned_to',
        'priority',
        'rejection_reason',
        'erp_estimation_id',
        'erp_imported_at',
        'erp_import_error',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'payload_json' => 'array',
            'erp_imported_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'status' => QuotationRequestStatus::class,
        ];
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(QuotationAttachment::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(QuotationRequestEvent::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public static function generateExternalId(): string
    {
        $prefix = config('quotation_requests.reference_prefix', 'WEB');
        $year = now()->format('Y');
        $sequence = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('%s-%s-%05d', $prefix, $year, $sequence);
    }

    public function payload(string $key, mixed $default = null): mixed
    {
        return data_get($this->payload_json, $key, $default);
    }

    public function contactName(): string
    {
        return (string) $this->payload('contact.name', '—');
    }

    public function contactEmail(): string
    {
        return (string) $this->payload('contact.email', '');
    }

    public function contactCompany(): ?string
    {
        return $this->payload('contact.company');
    }

    public function jobTitle(): string
    {
        return (string) $this->payload('job.title', '—');
    }

    public function jobType(): string
    {
        return (string) $this->payload('job.type', '—');
    }

    public function jobQuantity(): ?int
    {
        $qty = $this->payload('job.quantity');

        return $qty !== null ? (int) $qty : null;
    }

    public function canApprove(): bool
    {
        return $this->status === QuotationRequestStatus::Pending;
    }

    public function canReject(): bool
    {
        return $this->status === QuotationRequestStatus::Pending;
    }

    public function canSendToErp(): bool
    {
        return $this->status === QuotationRequestStatus::Approved
            && $this->erp_estimation_id === null;
    }

    public function canRetryErp(): bool
    {
        return $this->status === QuotationRequestStatus::ErpFailed;
    }

    public function canMarkSpam(): bool
    {
        return ! $this->status->isTerminal();
    }

    public function getRouteKeyName(): string
    {
        return 'external_id';
    }
}
