<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'selected_toppings',
        'toppings_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'toppings_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'selected_toppings' => 'json',
    ];

    /**
     * Sale this item belongs to
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Product (menu item) for this sale item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
