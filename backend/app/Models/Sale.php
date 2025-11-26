<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'payment_method',
        'order_type',
        'sale_date',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'sale_date' => 'date',
    ];

    /**
     * Items in this sale
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
