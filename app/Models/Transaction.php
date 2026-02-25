<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'type',
        'amount',
        'from_account_id',
        'to_account_id',
        'person_id',
        'payment_method_id',
        'check_number',
        'check_date',
        'transaction_date',
        'description',
        'notes',
        'status',
        'asset_id',
        'asset_type',
        'car_sale_id',
        'investment_id',
        'created_by'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'check_date' => 'date',
        'amount' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // تولید شماره تراکنش یکتا
            $transaction->transaction_number = 'TR' . now()->format('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        });
    }

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function carSale(): BelongsTo
    {
        return $this->belongsTo(CarSale::class);
    }

    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * دریافت نوع تراکنش به فارسی
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'income' ? 'دریافت' : 'پرداخت';
    }

    /**
     * دریافت وضعیت به فارسی
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'در انتظار',
            'completed' => 'تکمیل شده',
            'cancelled' => 'لغو شده',
            default => $this->status,
        };
    }

    /**
     * دریافت مبلغ فرمت شده
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount) . ' ریال';
    }

    /**
     * بروزرسانی موجودی حساب‌ها پس از تایید تراکنش
     */
    public function updateBalances()
    {
        if ($this->status !== 'completed') {
            return;
        }

        if ($this->type === 'income' && $this->to_account_id) {
            // دریافت پول => به حساب مقصد اضافه می‌شود
            $this->toAccount->increment('balance', $this->amount);
        } elseif ($this->type === 'expense' && $this->from_account_id) {
            // پرداخت پول => از حساب مبدأ کم می‌شود
            $this->fromAccount->decrement('balance', $this->amount);
        } elseif ($this->from_account_id && $this->to_account_id) {
            // انتقال بین دو حساب
            $this->fromAccount->decrement('balance', $this->amount);
            $this->toAccount->increment('balance', $this->amount);
        }
    }

    /**
     * برگرداندن موجودی‌ها در صورت لغو تراکنش
     */
    public function revertBalances()
    {
        if ($this->status === 'cancelled') {
            if ($this->type === 'income' && $this->to_account_id) {
                $this->toAccount->decrement('balance', $this->amount);
            } elseif ($this->type === 'expense' && $this->from_account_id) {
                $this->fromAccount->increment('balance', $this->amount);
            } elseif ($this->from_account_id && $this->to_account_id) {
                $this->fromAccount->increment('balance', $this->amount);
                $this->toAccount->decrement('balance', $this->amount);
            }
        }
    }
}