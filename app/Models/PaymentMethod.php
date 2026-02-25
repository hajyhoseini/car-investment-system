<?php
// app/Models/PaymentMethod.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * دریافت نوع روش پرداخت به فارسی
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'cash' => 'نقدی',
            'check' => 'چکی',
            'card_to_card' => 'کارت به کارت',
            'transfer' => 'حواله',
            'other' => 'سایر',
            default => $this->type,
        };
    }
}