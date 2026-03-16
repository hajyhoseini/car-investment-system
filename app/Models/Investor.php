<?php
// app/Models/Investor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investor extends Model
{
    protected $fillable = [
        'person_id',  // به جای فیلدهای قبلی
        'user_id',
        'description'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'person_id' => 'integer'
    ];

    /**
     * رابطه با Person
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * رابطه با User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * رابطه با سرمایه‌گذاری‌ها
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    /**
     * دریافت نام سرمایه‌گذار از طریق Person
     */
    public function getFullNameAttribute()
    {
        return $this->person?->display_name ?? 'نامشخص';
    }

    /**
     * دریافت کد ملی از طریق Person
     */
    public function getNationalCodeAttribute()
    {
        return $this->person?->national_code;
    }

    /**
     * دریافت تلفن از طریق Person
     */
    public function getPhoneAttribute()
    {
        return $this->person?->phone;
    }
}