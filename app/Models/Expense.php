<?php
// app/Models/Expense.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends BaseModel
{
    protected $jalaliDates = ['expense_date'];
    
    protected $fillable = [
        'title',
        'description',
        'amount',
        'expense_date',
        'category',
        'car_id',
        'person_id',
        'account_id',
        'payment_method_id',
        'receipt_image',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'expense_date' => 'datetime', // تغییر از 'date' به 'datetime'
        'amount' => 'decimal:2'
    ];

    /**
     * خودرو مرتبط با هزینه (اگر مربوط به خودرو باشد)
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * شخص مرتبط با هزینه
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * حسابی که هزینه از آن پرداخت شده
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'account_id');
    }

    /**
     * روش پرداخت
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * کاربر ثبت‌کننده
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * دریافت دسته‌بندی به فارسی
     */
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'car_service' => 'خدمات خودرو',
            'car_repair' => 'تعمیرات خودرو',
            'car_wash' => 'کارواش',
            'fuel' => 'سوخت',
            'rent' => 'اجاره',
            'snapp' => 'اسنپ/تاکسی',
            'food' => 'غذا',
            'office' => 'لوازم اداری',
            'utility' => 'قبوض',
            'other' => 'سایر',
            default => $this->category,
        };
    }

    /**
     * دریافت تاریخ هزینه به صورت شمسی
     */
    public function getExpenseDateJalaliAttribute(): string
    {
        return $this->expense_date ? jalali_date($this->expense_date) : '';
    }

    /**
     * دریافت تاریخ هزینه به صورت شمسی با زمان
     */
    public function getExpenseDateJalaliFullAttribute(): string
    {
        return $this->expense_date ? jalali_datetime($this->expense_date) : '';
    }

    /**
     * دریافت تاریخ ثبت به صورت شمسی
     */
    public function getCreatedAtJalaliAttribute(): string
    {
        return $this->created_at ? jalali_datetime($this->created_at) : '';
    }

    /**
     * دریافت تاریخ آخرین بروزرسانی به صورت شمسی
     */
    public function getUpdatedAtJalaliAttribute(): string
    {
        return $this->updated_at ? jalali_datetime($this->updated_at) : '';
    }
}