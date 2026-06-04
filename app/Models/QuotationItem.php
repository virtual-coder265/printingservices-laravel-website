<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'service_id',
        'description',
        'quantity',
        'unit_price',
        'vat_rate',
        'vat_amount',
        'line_total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'vat_rate' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function recalculateLine(): void
    {
        $lineSubtotal = (float) $this->unit_price * $this->quantity;
        $vatAmount = round($lineSubtotal * ((float) $this->vat_rate / 100), 2);
        $lineTotal = $lineSubtotal + $vatAmount;

        static::withoutEvents(function () use ($vatAmount, $lineTotal) {
            $this->update([
                'vat_amount' => $vatAmount,
                'line_total' => $lineTotal,
            ]);
        });
    }
}
