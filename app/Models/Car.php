<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    protected $fillable = [
        'title',
        'brand',
        'model',
        'year',
        'kilometers',
        'fuel_type',
        'transmission',
        'color',
        'description',
        'purchase_price',
        'purchase_date',
        'status'
    ];

    protected $casts = [
        'purchase_date' => 'date'
    ];

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(CarSale::class);
    }

    public function getTotalInvestedAttribute(): float
    {
        return $this->investments()->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->purchase_price - $this->total_invested;
    }
    
public function isFullyFunded(): bool
{
    return $this->total_invested >= $this->purchase_price;
}

/**
 * مقدار باقی‌مانده برای سرمایه‌گذاری
 */
public function getRemainingForInvestmentAttribute(): float
{
    $remaining = $this->purchase_price - $this->total_invested;
    return max($remaining, 0);
}

/**
 * درصد سرمایه‌گذاری شده
 */
public function getFundedPercentageAttribute(): float
{
    if ($this->purchase_price <= 0) return 0;
    return ($this->total_invested / $this->purchase_price) * 100;
}
}