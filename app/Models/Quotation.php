<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quotation extends Model
{
    protected $fillable = [
        'reference',
        'customer_profile_id',
        'status',
        'subtotal',
        'vat_rate',
        'vat_amount',
        'total_amount',
        'notes',
        'rejection_reason',
        'expires_at',
        'external_erp_id',
        'erp_sync_status',
        'erp_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'vat_rate' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'expires_at' => 'datetime',
            'erp_synced_at' => 'datetime',
        ];
    }

    public function customerProfile(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function printJob(): HasOne
    {
        return $this->hasOne(PrintJob::class);
    }

    public function artworkFiles(): HasMany
    {
        return $this->hasMany(ArtworkFile::class);
    }

    public function recalculateTotals(): void
    {
        $subtotal = $this->items->sum(fn (QuotationItem $item) => (float) $item->unit_price * $item->quantity);
        $vatAmount = $this->items->sum(fn (QuotationItem $item) => (float) $item->vat_amount);
        $total = $subtotal + $vatAmount;

        $this->update([
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'total_amount' => $total,
        ]);
    }

    public static function generateReference(): string
    {
        $year = now()->format('Y');
        $last = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('QUO-%s-%04d', $year, $last);
    }
}
