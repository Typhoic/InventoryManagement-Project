<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['name', 'price', 'image_url', 'description', 'is_active'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'menu_item_ingredients')
            ->withPivot('quantity_used')
            ->withTimestamps();
    }

    // Scope for active menu items
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Check if all ingredients are available
    public function canMake(int $quantity = 1): bool
    {
        foreach ($this->ingredients as $ingredient) {
            $needed = $ingredient->pivot->quantity_used * $quantity;
            if ($ingredient->current_stock < $needed) {
                return false;
            }
        }
        return true;
    }
}