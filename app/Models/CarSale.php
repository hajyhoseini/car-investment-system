<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarSale extends Model
{
    protected $fillable = [
        'car_id',
        'selling_price',
        'total_profit',
        'sale_date',
        'buyer_name',
        'buyer_phone'
    ];

    protected $casts = [
        'sale_date' => 'date'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
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