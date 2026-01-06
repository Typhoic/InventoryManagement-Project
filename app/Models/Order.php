<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['channel', 'total_amount', 'status'];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

        const CHANNELS = [
        'dine_in' => 'Dine In',
        'catering' => 'Catering',
        'go_food' => 'Go Food',
        'grab_food' => 'Grab Food',
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    // Relationships
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

    // Scopes
    public function scopeByChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    public function scopeDateRange($query, string $period)
    {
        return match ($period) {
            'week' => $query->thisWeek(),
            'month' => $query->thisMonth(),
            'year' => $query->thisYear(),
            default => $query,
        };
    }

    // Helper Methods
    public function getChannelLabel(): string
    {
        return self::CHANNELS[$this->channel] ?? $this->channel;
    }

    public function getStatusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getTotalItems(): int
    {
        return $this->orderItems()->sum('quantity');
    }
}