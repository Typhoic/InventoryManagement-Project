<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'category',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Ingredients required for this product (recipe)
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredients')
            ->withPivot('quantity_required', 'unit');
    }

    /**
     * Available toppings for this product
     */
    public function toppings(): BelongsToMany
    {
        return $this->belongsToMany(Topping::class, 'product_toppings');
    }

    /**
     * Sale items for this product
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Check if this product can be made with current stock
     * Returns: true if all ingredients are available, false otherwise
     */
    public function canBeMade(int $quantity = 1): bool
    {
        foreach ($this->ingredients as $ingredient) {
            $required = $ingredient->pivot->quantity_required * $quantity;
            if ($ingredient->quantity_on_hand < $required) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get ingredients needed for this product with their stock status
     */
    public function getIngredientsWithStock()
    {
        return $this->ingredients()->select(
            'ingredients.id',
            'ingredients.name',
            'ingredients.quantity_on_hand',
            'ingredients.reorder_level',
            'ingredients.unit',
            'product_ingredients.quantity_required'
        )->get();
    }
}
