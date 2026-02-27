<?php
// app/Traits/JalaliDateTrait.php

namespace App\Traits;

use Morilog\Jalali\Jalalian;

trait JalaliDateTrait
{
    /**
     * تبدیل تاریخ میلادی به شمسی برای نمایش
     */
    public function toJalali($date, $format = 'Y/m/d')
    {
        if (empty($date)) {
            return null;
        }

        try {
            if ($date instanceof \Carbon\Carbon) {
                return Jalalian::fromCarbon($date)->format($format);
            }
            return Jalalian::fromDateTime($date)->format($format);
        } catch (\Exception $e) {
            return $date;
        }
    }

    /**
     * تبدیل تاریخ شمسی به میلادی برای ذخیره
     */
    public function toGregorian($date, $format = 'Y-m-d')
    {
        if (empty($date)) {
            return null;
        }

        try {
            // اگر تاریخ به فرمت شمسی باشه
            if (strpos($date, '/') !== false) {
                $jalali = Jalalian::fromFormat('Y/m/d', $date);
                return $jalali->toCarbon()->format($format);
            }
            return date($format, strtotime($date));
        } catch (\Exception $e) {
            return $date;
        }
    }

    /**
     * دریافت تاریخ فعلی شمسی
     */
    public function nowJalali($format = 'Y/m/d')
    {
        return Jalalian::now()->format($format);
    }
}