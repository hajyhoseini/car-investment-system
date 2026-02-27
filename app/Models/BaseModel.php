<?php
// app/Models/BaseModel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\JalaliDateTrait;

abstract class BaseModel extends Model
{
    use JalaliDateTrait;

    /**
     * فیلدهای تاریخ که باید به شمسی تبدیل شوند
     */
    protected $jalaliDates = [];

    /**
     * دریافت فیلد تاریخ به صورت شمسی
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        // اگر فیلد در لیست تاریخ‌های شمسی بود و مقدار داشت
        if (in_array($key, $this->jalaliDates) && !empty($value)) {
            return $this->toJalali($value);
        }
        
        return $value;
    }

    /**
     * تنظیم مقدار فیلد تاریخ (تبدیل خودکار به میلادی)
     */
    public function setAttribute($key, $value)
    {
        // اگر فیلد در لیست تاریخ‌های شمسی بود و مقدار داشت
        if (in_array($key, $this->jalaliDates) && !empty($value)) {
            $value = $this->toGregorian($value);
        }
        
        parent::setAttribute($key, $value);
    }

    /**
     * دریافت آرایه‌ای از تمام فیلدهای تاریخ شمسی
     */
    public function getJalaliDates()
    {
        return $this->jalaliDates;
    }
}