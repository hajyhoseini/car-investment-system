<?php
// app/Models/Receivable.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receivable extends Model
{
    protected $fillable = [
        'title',
        'description',
        'amount',
        'currency_type', // cash, gold, dollar, check, other
        'currency_details', // JSON: for check number, gold weight, etc.
        'receivable_date',
        'due_date',
        'person_id',
        'status', // pending, partially_paid, paid, overdue
        'paid_amount',
        'remaining_amount',
        'attachments',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'receivable_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'currency_details' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($receivable) {
            $receivable->remaining_amount = $receivable->amount;
            $receivable->paid_amount = $receivable->paid_amount ?? 0;
        });

        static::updating(function ($receivable) {
            if ($receivable->isDirty('paid_amount')) {
                $receivable->remaining_amount = $receivable->amount - $receivable->paid_amount;
                
                if ($receivable->remaining_amount <= 0) {
                    $receivable->status = 'paid';
                } elseif ($receivable->remaining_amount < $receivable->amount) {
                    $receivable->status = 'partially_paid';
                }
            }
        });
    }

    /**
     * شخص مرتبط با این مطالبه
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * کاربر ثبت‌کننده
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * دریافت نوع ارز به فارسی
     */
    public function getCurrencyTypeLabelAttribute(): string
    {
        return match($this->currency_type) {
            'cash' => 'نقد',
            'gold' => 'طلا',
            'dollar' => 'دلار',
            'check' => 'چک',
            'other' => 'سایر',
            default => $this->currency_type,
        };
    }

    /**
     * دریافت وضعیت به فارسی
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'در انتظار',
            'partially_paid' => 'پرداخت جزئی',
            'paid' => 'تسویه شده',
            'overdue' => 'سررسید گذشته',
            default => $this->status,
        };
    }

    /**
     * دریافت جزئیات ارز
     */
    public function getFormattedCurrencyDetailsAttribute(): string
    {
        if (!$this->currency_details) {
            return '';
        }

        return match($this->currency_type) {
            'check' => 'چک شماره: ' . ($this->currency_details['check_number'] ?? '') . 
                      ' - بانک: ' . ($this->currency_details['bank_name'] ?? '') .
                      ' - تاریخ: ' . ($this->currency_details['check_date'] ?? ''),
            'gold' => ($this->currency_details['weight'] ?? '') . ' گرم - عیار: ' . 
                      ($this->currency_details['karat'] ?? '') . ' - ' .
                      ($this->currency_details['description'] ?? ''),
            'dollar' => 'نرخ ارز: ' . number_format($this->currency_details['exchange_rate'] ?? 0) . ' ریال',
            default => json_encode($this->currency_details),
        };
    }
}