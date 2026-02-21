<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\Investor;
use Illuminate\Support\Facades\Log;

class CreateInvestorForUser
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;
        
        // بررسی اینکه آیا این کاربر قبلاً سرمایه‌گذار دارد یا نه
        if ($user->investor) {
            return;
        }
        
        // ایجاد سرمایه‌گذار برای کاربر جدید
        try {
            Investor::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'national_code' => '0000000000', // مقدار پیش‌فرض - کاربر بعداً می‌تونه تغییر بده
                'phone' => '09120000000', // مقدار پیش‌فرض
                'email' => $user->email,
                'address' => 'تهران', // مقدار پیش‌فرض
                'total_invested' => 0,
            ]);
            
            // اختصاص نقش سرمایه‌گذار به کاربر (اختیاری)
            // $user->assignRole('investor');
            
        } catch (\Exception $e) {
            Log::error('خطا در ایجاد سرمایه‌گذار برای کاربر: ' . $e->getMessage());
        }
    }
}