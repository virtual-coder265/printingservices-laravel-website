<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'title',
        'slug',
        'description',
        'type',
        'note',
        'price',
        'vat_rate',
        'stock_status',
        'thumbnail',
        'is_active',
        'is_featured',
        'sort_order',
        'external_erp_id',
        'erp_synced_at',
        'fulfillment_type',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'vat_rate' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'erp_synced_at' => 'datetime',
        ];
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function priceWithVat(): float
    {
        return round((float) $this->price * (1 + (float) $this->vat_rate / 100), 2);
    }
}
