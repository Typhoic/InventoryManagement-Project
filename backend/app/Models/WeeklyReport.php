<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_start',
        'total_income',
        'notes',
    ];

    protected $casts = [
        'week_start' => 'date',
        'total_income' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(WeeklyReportItem::class);
    }
}
