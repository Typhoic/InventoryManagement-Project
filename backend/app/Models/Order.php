<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['channel', 'total_amount', 'status'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
