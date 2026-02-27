<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends BaseModel
{
    protected $jalaliDates = ['transaction_date', 'check_date'];
    protected $fillable = [
        'transaction_number',
        'type',
        'amount',
        'from_asset_id',
        'to_asset_id',
        'person_id',
        'payment_method_id',
        'check_number',
        'check_date',
        'transaction_date',
        'description',
        'notes',
        'status',
        'asset_id',
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
            $transaction->transaction_number = 'TR' . now()->format('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        });
    }

    public function fromAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'from_asset_id');
    }

    public function toAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'to_asset_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'income' ? 'دریافت' : 'پرداخت';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'در انتظار',
            'completed' => 'تکمیل شده',
            'cancelled' => 'لغو شده',
            default => $this->status,
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount) . ' ریال';
    }

    // متد updateBalances به طور کامل حذف شد
}