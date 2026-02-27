<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Morilog\Jalali\Jalalian;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // دایرکتیو برای نمایش تاریخ شمسی
        Blade::directive('jalali', function ($expression) {
            return "<?php echo \Morilog\Jalali\Jalalian::fromDateTime($expression)->format('Y/m/d'); ?>";
        });

        // دایرکتیو برای نمایش تاریخ و زمان شمسی
        Blade::directive('jalaliDateTime', function ($expression) {
            return "<?php echo \Morilog\Jalali\Jalalian::fromDateTime($expression)->format('Y/m/d H:i'); ?>";
        });

        // دایرکتیو برای تاریخ شمسی با فرمت دلخواه
        Blade::directive('jalaliFormat', function ($expression) {
            return "<?php echo \Morilog\Jalali\Jalalian::fromDateTime($expression)->format($format); ?>";
        });
    }
}