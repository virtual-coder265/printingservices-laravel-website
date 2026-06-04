<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'reference',
        'customer_profile_id',
        'status',
        'payment_status',
        'subtotal',
        'vat_rate',
        'vat_amount',
        'total_amount',
        'notes',
        'internal_notes',
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
            'erp_synced_at' => 'datetime',
        ];
    }

    public function customerProfile(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateReference(): string
    {
        $year = now()->format('Y');
        $last = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('ORD-%s-%04d', $year, $last);
    }
}
