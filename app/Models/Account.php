<?php
// app/Models/Account.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'name',
        'type',
        'bank_name',
        'account_number',
        'card_number',
        'sheba_number',
        'balance',
        'currency',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'balance' => 'decimal:2'
    ];

    /**
     * تراکنش‌هایی که این حساب به عنوان مبدأ است
     */
    public function outgoingTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    /**
     * تراکنش‌هایی که این حساب به عنوان مقصد است
     */
    public function incomingTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }

    /**
     * دریافت موجودی فرمت شده
     */
    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance) . ' ' . $this->currency;
    }

    /**
     * دریافت نوع حساب به فارسی
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'bank' => 'بانکی',
            'cash' => 'صندوق',
            'wallet' => 'کیف پول',
            default => $this->type,
        };
    }
}