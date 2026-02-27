<?php
// app/Models/Car.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends BaseModel
{
    protected $jalaliDates = ['purchase_date'];
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
    'purchase_date' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(CarSale::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(CarImage::class)->where('is_primary', true);
    }

    public function getPrimaryImageUrlAttribute()
    {
        $primary = $this->primaryImage;
        if ($primary) {
            return $primary->url;
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->url;
        }
        
        return asset('images/no-image.jpg');
    }

    public function getThumbnailUrlAttribute()
    {
        $primary = $this->primaryImage;
        if ($primary) {
            return $primary->thumbnail_url;
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->thumbnail_url;
        }
        
        return asset('images/no-image-thumb.jpg');
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

    public function getRemainingForInvestmentAttribute(): float
    {
        $remaining = $this->purchase_price - $this->total_invested;
        return max($remaining, 0);
    }

    public function getFundedPercentageAttribute(): float
    {
        if ($this->purchase_price <= 0) return 0;
        return ($this->total_invested / $this->purchase_price) * 100;
    }
    // در مدل Car مثلاً


public function getCreatedAtJalaliAttribute()
{
    return jalali_datetime($this->created_at);
}

public function getUpdatedAtJalaliAttribute()
{
    return jalali_datetime($this->updated_at);
}
}