<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
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

    public function updateBalances()
    {
        if ($this->status !== 'completed') {
            return;
        }

        if ($this->type === 'income' && $this->to_asset_id) {
            $this->toAsset->increment('amount', $this->amount);
            if ($this->toAsset->type === 'bank') {
                $this->toAsset->value = $this->toAsset->amount;
                $this->toAsset->save();
            }
        } elseif ($this->type === 'expense' && $this->from_asset_id) {
            $this->fromAsset->decrement('amount', $this->amount);
            if ($this->fromAsset->type === 'bank') {
                $this->fromAsset->value = $this->fromAsset->amount;
                $this->fromAsset->save();
            }
        } elseif ($this->from_asset_id && $this->to_asset_id) {
            $this->fromAsset->decrement('amount', $this->amount);
            $this->toAsset->increment('amount', $this->amount);
            
            if ($this->fromAsset->type === 'bank') {
                $this->fromAsset->value = $this->fromAsset->amount;
                $this->fromAsset->save();
            }
            if ($this->toAsset->type === 'bank') {
                $this->toAsset->value = $this->toAsset->amount;
                $this->toAsset->save();
            }
        }
    }

    public function revertBalances()
    {
        if ($this->type === 'income' && $this->to_asset_id) {
            $this->toAsset->decrement('amount', $this->amount);
            if ($this->toAsset->type === 'bank') {
                $this->toAsset->value = $this->toAsset->amount;
                $this->toAsset->save();
            }
        } elseif ($this->type === 'expense' && $this->from_asset_id) {
            $this->fromAsset->increment('amount', $this->amount);
            if ($this->fromAsset->type === 'bank') {
                $this->fromAsset->value = $this->fromAsset->amount;
                $this->fromAsset->save();
            }
        } elseif ($this->from_asset_id && $this->to_asset_id) {
            $this->fromAsset->increment('amount', $this->amount);
            $this->toAsset->decrement('amount', $this->amount);
            
            if ($this->fromAsset->type === 'bank') {
                $this->fromAsset->value = $this->fromAsset->amount;
                $this->fromAsset->save();
            }
            if ($this->toAsset->type === 'bank') {
                $this->toAsset->value = $this->toAsset->amount;
                $this->toAsset->save();
            }
        }
    }
}