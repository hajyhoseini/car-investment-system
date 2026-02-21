<?php

namespace App\Traits;

use Morilog\Jalali\Jalalian;

trait JalaliDateTrait
{
    /**
     * تبدیل تاریخ شمسی به میلادی برای ذخیره در دیتابیس
     */
    public function convertToGregorian($date, $format = 'Y-m-d')
    {
        if (empty($date)) {
            return null;
        }

        try {
            // اگر تاریخ به فرمت شمسی باشه
            if (strpos($date, '/') !== false) {
                $parts = explode('/', $date);
                if (count($parts) == 3) {
                    $jalali = Jalalian::fromFormat('Y/m/d', $date);
                    return $jalali->toCarbon()->format($format);
                }
            }
            
            // اگر تاریخ میلادی باشه
            return date($format, strtotime($date));
        } catch (\Exception $e) {
            return $date;
        }
    }

    /**
     * تبدیل تاریخ میلادی به شمسی برای نمایش
     */
    public function convertToJalali($date, $format = 'Y/m/d')
    {
        if (empty($date)) {
            return null;
        }

        try {
            return Jalalian::fromDateTime($date)->format($format);
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

    /**
     * دریافت تاریخ فعلی میلادی
     */
    public function nowGregorian($format = 'Y-m-d')
    {
        return date($format);
    }
}