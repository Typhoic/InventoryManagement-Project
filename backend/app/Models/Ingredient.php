<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity_on_hand',
        'reorder_level',
        'unit_price',
        'unit',
        'category',
    ];

    protected $casts = [
        'quantity_on_hand' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Products that use this ingredient (recipe)
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
            ->withPivot('quantity_required', 'unit');
    }

    /**
     * Check if this ingredient is low on stock
     */
    public function isLowStock(): bool
    {
        return $this->quantity_on_hand < $this->reorder_level;
    }
}
