<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name', 
        'initial_stock', 
        'current_stock', 
        'unit', 
        'low_stock_threshold'
    ];

    public function groups()
    {
        return $this->belongsToMany(IngredientGroup::class, 'ingredient_group_items');
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_ingredients')
            ->withPivot('quantity_used')
            ->withTimestamps();
    }

    // Check if ingredient is low stock
    public function isLowStock()
    {
        $percentageRemaining = ($this->current_stock / $this->initial_stock) * 100;
        return $percentageRemaining <= $this->low_stock_threshold;
    }

    // Get percentage remaining
    public function getStockPercentage()
    {
        if ($this->initial_stock == 0) return 0;
        return ($this->current_stock / $this->initial_stock) * 100;
    }
}
