<?php
// app/Models/CarSale.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarSale extends Model
{
    protected $fillable = [
        'car_id',
        'person_id',
        'selling_price',
        'total_profit',
        'sale_date',
        'buyer_name', // برای سازگاری با عقب
        'buyer_phone' // برای سازگاری با عقب
    ];

    protected $casts = [
        'sale_date' => 'date'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * شخص مرتبط با این فروش (خریدار)
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * دریافت نام خریدار
     */
    public function getBuyerNameAttribute($value)
    {
        if ($this->person) {
            return $this->person->display_name;
        }
        return $value;
    }

    /**
     * دریافت تلفن خریدار
     */
    public function getBuyerPhoneAttribute($value)
    {
        if ($this->person) {
            return $this->person->phone;
        }
        return $value;
    }

    public function calculateInvestorProfits(): array
    {
        $investments = $this->car->investments;
        $profits = [];

        foreach ($investments as $investment) {
            $investorProfit = ($this->total_profit * $investment->percentage) / 100;
            $profits[] = [
                'investor' => $investment->investor,
                'percentage' => $investment->percentage,
                'invested_amount' => $investment->amount,
                'profit' => $investorProfit,
                'total_return' => $investment->amount + $investorProfit
            ];
        }

        return $profits;
    }
}