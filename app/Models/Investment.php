<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends BaseModel
{
    protected $jalaliDates = ['investment_date'];

    protected $fillable = [
        'car_id',
        'investor_id',
        'amount',
        'percentage',
        'investment_date'
    ];

    protected $casts = [
        'investment_date' => 'datetime',  // ✅ تغییر به datetime
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function investor(): BelongsTo
    {
        return $this->belongsTo(Investor::class);
    }
}