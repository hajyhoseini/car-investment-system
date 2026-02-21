<?php
// database/seeders/FixSaraInvestorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Investor;

class FixSaraInvestorSeeder extends Seeder
{
    public function run(): void
    {
        // پیدا کردن کاربر سارا
        $user = User::where('email', 'sara@example.com')->first();
        
        if (!$user) {
            $this->command->error('کاربر سارا پیدا نشد!');
            return;
        }
        
        // پیدا کردن سرمایه‌گذار با ایمیل سارا
        $investor = Investor::where('email', 'sara@example.com')->first();
        
        if ($investor) {
            // سرمایه‌گذار وجود داره، فقط user_id رو ست کن
            $investor->user_id = $user->id;
            $investor->save();
            $this->command->info("سرمایه‌گذار موجود با کاربر ارتباط داده شد.");
        } else {
            // پیدا کردن یه کد ملی یکتا
            $existingCodes = Investor::pluck('national_code')->toArray();
            $newCode = '123456789' . $user->id;
            
            // اگه کد تکراری بود، یه کد جدید بساز
            while (in_array($newCode, $existingCodes)) {
                $newCode = '12345678' . rand(10, 99);
            }
            
            // ایجاد سرمایه‌گذار جدید
            $investor = Investor::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'national_code' => $newCode,
                'phone' => '09131234567',
                'email' => $user->email,
                'address' => 'تهران',
                'total_invested' => 0,
            ]);
            $this->command->info("سرمایه‌گذار جدید با کد ملی {$newCode} ایجاد شد.");
        }
        
        $this->command->info('عملیات با موفقیت انجام شد.');
    }
}