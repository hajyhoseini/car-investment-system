<?php
// app/Models/Liability.php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Liability extends BaseModel
{
    protected $jalaliDates = ['due_date'];
    
    protected $fillable = [
        'type',
        'person_id',
        'creditor_name',
        'amount',
        'remaining_amount',
        'due_date',
        'status',
        'description'
    ];

    protected $casts = [
        'due_date' => 'datetime', // تغییر از 'date' به 'datetime'
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2'
    ];

    /**
     * شخص مرتبط با این تعهد (طلبکار یا بدهکار)
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function isOverdue(): bool
    {
        // بررسی کن که due_date وجود داره و از نوع تاریخ هست
        if (!$this->due_date) {
            return false;
        }
        
        // اطمینان حاصل کن که due_date به صورت Carbon درآمده
        $dueDate = $this->due_date instanceof Carbon 
            ? $this->due_date 
            : Carbon::parse($this->due_date);
        
        return $dueDate->isPast() && $this->status !== 'paid';
    }

    /**
     * دریافت نام طلبکار (از شخص یا نام مستقیم)
     */
    public function getCreditorDisplayNameAttribute(): string
    {
        if ($this->person) {
            return $this->person->full_name;
        }
        return $this->creditor_name ?? '—';
    }

    /**
     * دریافت تاریخ سررسید به صورت شمسی
     */
    public function getDueDateJalaliAttribute(): string
    {
        return $this->due_date ? jalali_date($this->due_date) : '—';
    }
}