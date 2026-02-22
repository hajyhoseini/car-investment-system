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
        
        $persian_numbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english_numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        // اگه عدد هست و فرمت می‌خوایم
        if (is_numeric($number)) {
            $number = number_format($number);
        }
        
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
        
        $formatted = number_format((float)$number, $decimals);
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
        return fa_number_format($amount, 0, 'ریال');
    }
}