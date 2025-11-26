<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topping extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quantity_on_hand',
        'reorder_level',
        'unit',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity_on_hand' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Products that can have this topping
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_toppings');
    }

    /**
     * Check if this topping is low on stock
     */
    public function isLowStock(): bool
    {
        return $this->quantity_on_hand < $this->reorder_level;
    }
}
