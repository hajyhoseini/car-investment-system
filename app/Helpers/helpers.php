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