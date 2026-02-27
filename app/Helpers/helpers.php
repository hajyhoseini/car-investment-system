<?php

if (!function_exists('fa_number')) {
    /**
     * تبدیل اعداد انگلیسی به فارسی
     */
    function fa_number($number)
    {
        if ($number === null || $number === '') {
            return '';
        }
        
        // اگه عدد اعشاریه و فرمت خواسته نشده
        if (is_numeric($number) && !is_string($number)) {
            // اگه عدد اعشاری هست با دو رقم اعشار نشون بده
            if (floor($number) != $number) {
                $number = number_format($number, 2);
            }
        }
        
        $persian_numbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english_numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        return str_replace($english_numbers, $persian_numbers, (string)$number);
    }
}

if (!function_exists('fa_number_format')) {
    /**
     * فرمت عدد و تبدیل به فارسی
     */
    function fa_number_format($number, $decimals = 0, $append = '')
    {
        if ($number === null || $number === '') {
            return '';
        }
        
        // جلوگیری از حلقه بینهایت
        if (is_numeric($number)) {
            $number = (float)$number;
        } else {
            $number = 0;
        }
        
        $formatted = number_format($number, $decimals);
        $persian = fa_number($formatted);
        
        return $persian . ($append ? ' ' . $append : '');
    }
}

if (!function_exists('fa_currency')) {
    /**
     * نمایش پول با فرمت ریال
     */
    function fa_currency($amount)
    {
        if ($amount === null || $amount === '') {
            return '';
        }
        
        // جلوگیری از حلقه بینهایت
        $amount = (float)$amount;
        
        return fa_number_format($amount, 0, '');
    }
}

if (!function_exists('fa_percentage')) {
    /**
     * نمایش درصد
     */
    function fa_percentage($value, $decimals = 1)
    {
        if ($value === null || $value === '') {
            return '';
        }
        
        return fa_number_format($value, $decimals, '٪');
    }
}

// =============================================
// توابع تاریخ شمسی
// =============================================

if (!function_exists('jalali_date')) {
    /**
     * تبدیل تاریخ میلادی به شمسی
     * 
     * @param string|Carbon|null $date تاریخ میلادی
     * @param string $format فرمت خروجی (پیش‌فرض: Y/m/d)
     * @return string
     */
    function jalali_date($date, $format = 'Y/m/d')
    {
        if (empty($date)) {
            return '';
        }
        
        try {
            if (class_exists('\Morilog\Jalali\Jalalian')) {
                return \Morilog\Jalali\Jalalian::fromDateTime($date)->format($format);
            }
            
            // اگر پکیج نصب نبود، تاریخ رو با اعداد فارسی برمی‌گردونیم
            if ($date instanceof \Carbon\Carbon) {
                return fa_number($date->format($format));
            }
            return fa_number(date($format, strtotime($date)));
        } catch (\Exception $e) {
            return $date;
        }
    }
}

if (!function_exists('jalali_datetime')) {
    /**
     * تبدیل تاریخ و زمان میلادی به شمسی
     * 
     * @param string|Carbon|null $date تاریخ میلادی
     * @return string
     */
    function jalali_datetime($date)
    {
        return jalali_date($date, 'Y/m/d H:i');
    }
}

if (!function_exists('jalali_date_input')) {
    /**
     * آماده‌سازی تاریخ برای input type="date"
     * (تبدیل به فرمت Y-m-d)
     * 
     * @param string|Carbon|null $date تاریخ میلادی
     * @return string
     */
    function jalali_date_input($date)
    {
        if (empty($date)) {
            return '';
        }
        
        try {
            if ($date instanceof \Carbon\Carbon) {
                return $date->format('Y-m-d');
            }
            return date('Y-m-d', strtotime($date));
        } catch (\Exception $e) {
            return $date;
        }
    }
}

if (!function_exists('now_jalali')) {
    /**
     * دریافت تاریخ و زمان فعلی به شمسی
     * 
     * @param string $format فرمت خروجی
     * @return string
     */
    function now_jalali($format = 'Y/m/d H:i:s')
    {
        if (class_exists('\Morilog\Jalali\Jalalian')) {
            return \Morilog\Jalali\Jalalian::now()->format($format);
        }
        
        return fa_number(now()->format($format));
    }
}

if (!function_exists('is_jalali')) {
    /**
     * بررسی می‌کند که آیا تاریخ وارد شده شمسی است یا خیر
     * 
     * @param string $date
     * @return bool
     */
    function is_jalali($date)
    {
        if (empty($date)) {
            return false;
        }
        
        // الگوی تاریخ شمسی: ۱۴۰۲/۱۲/۲۵
        return preg_match('/^[۰-۹]{4}\/[۰-۹]{2}\/[۰-۹]{2}$/', $date) === 1;
    }
}

if (!function_exists('jalali_to_gregorian')) {
    /**
     * تبدیل تاریخ شمسی به میلادی
     * 
     * @param string $jalaliDate تاریخ شمسی
     * @param string $format فرمت خروجی
     * @return string
     */
    function jalali_to_gregorian($jalaliDate, $format = 'Y-m-d')
    {
        if (empty($jalaliDate)) {
            return '';
        }
        
        try {
            if (class_exists('\Morilog\Jalali\Jalalian')) {
                return \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $jalaliDate)
                    ->toCarbon()
                    ->format($format);
            }
            
            // اگر پکیج نصب نبود، تاریخ رو بدون تغییر برمی‌گردونیم
            return $jalaliDate;
        } catch (\Exception $e) {
            return $jalaliDate;
        }
    }
}

if (!function_exists('gregorian_to_jalali')) {
    /**
     * تبدیل تاریخ میلادی به شمسی (نام مستعار برای jalali_date)
     */
    function gregorian_to_jalali($date, $format = 'Y/m/d')
    {
        return jalali_date($date, $format);
    }
}
if (!function_exists('jalali_datetime')) {
    /**
     * نمایش تاریخ و زمان به شمسی
     * 
     * @param string|Carbon|null $date
     * @param string $format
     * @return string
     */
    function jalali_datetime($date, $format = 'Y/m/d H:i')
    {
        if (empty($date)) {
            return '';
        }
        
        try {
            if (class_exists('\Morilog\Jalali\Jalalian')) {
                return \Morilog\Jalali\Jalalian::fromDateTime($date)->format($format);
            }
            return date($format, strtotime($date));
        } catch (\Exception $e) {
            return $date;
        }
    }
}

if (!function_exists('jalali_time')) {
    /**
     * نمایش فقط زمان به شمسی
     */
    function jalali_time($date, $format = 'H:i')
    {
        return jalali_datetime($date, $format);
    }
}
// به فایل app/Helpers/helpers.php اضافه کن

if (!function_exists('jalali_time')) {
    /**
     * نمایش ساعت به شمسی
     */
    function jalali_time($date, $format = 'H:i')
    {
        if (empty($date)) {
            return '';
        }
        
        try {
            if ($date instanceof \Carbon\Carbon) {
                return fa_number($date->format($format));
            }
            return fa_number(date($format, strtotime($date)));
        } catch (\Exception $e) {
            return '';
        }
    }
}