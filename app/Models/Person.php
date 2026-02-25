<?php
// app/Models/Person.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    protected $fillable = [
        'full_name',
        'national_code',
        'phone',
        'email',
        'address',
        'type', // buyer, seller, creditor, other
        'description',
        'is_legal', // حقیقی یا حقوقی
        'company_name', // در صورت حقوقی بودن
        'economic_code',
        'postal_code',
        'birth_date',
        'avatar'
    ];

    protected $casts = [
        'is_legal' => 'boolean',
        'birth_date' => 'date'
    ];

    /**
     * خریدهای انجام شده توسط این شخص (به عنوان خریدار)
     */
 /**
 * خریدهای انجام شده توسط این شخص (به عنوان خریدار)
 */
public function purchases(): HasMany
{
    // به جای buyer_id از person_id استفاده کن
    return $this->hasMany(CarSale::class, 'person_id');
}

    /**
     * فروش‌های انجام شده توسط این شخص (به عنوان فروشنده)
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Car::class, 'seller_id');
    }

    /**
     * تعهدات مرتبط با این شخص (به عنوان طلبکار یا بدهکار)
     */
    public function liabilities(): HasMany
    {
        return $this->hasMany(Liability::class, 'person_id');
    }

    /**
     * دریافت آواتار یا تصویر پیش‌فرض
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/people/' . $this->avatar)
            : asset('images/default-avatar.png');
    }

    /**
     * دریافت عنوان شخص
     */
    public function getDisplayNameAttribute()
    {
        if ($this->is_legal && $this->company_name) {
            return $this->company_name . ' (' . $this->full_name . ')';
        }
        return $this->full_name;
    }

    /**
     * دریافت نوع شخص به فارسی
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'buyer' => 'خریدار',
            'seller' => 'فروشنده',
            'creditor' => 'طلبکار',
            'debtor' => 'بدهکار',
            default => 'سایر',
        };
    }
}