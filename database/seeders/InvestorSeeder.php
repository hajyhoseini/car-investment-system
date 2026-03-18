<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Investor;

class InvestorSeeder extends Seeder
{
    public function run(): void
    {
        $investors = [
            [
                'full_name' => 'علی محمدی',
                'national_code' => '0012345678',
                'phone' => '09121234567',
                'email' => 'ali@gmail.com',
                'address' => 'تهران، خیابان انقلاب',
            ],
            [
                'full_name' => 'سارا احمدی',
                'national_code' => '0012345679',
                'phone' => '09131234567',
                'email' => 'sara@yahoo.com',
                'address' => 'اصفهان، چهارباغ',
            ],
            [
                'full_name' => 'رضا کریمی',
                'national_code' => '0012345680',
                'phone' => '09141234567',
                'email' => 'reza@gmail.com',
                'address' => 'شیراز، معالی‌آباد',
            ],
            [
                'full_name' => 'مریم حسینی',
                'national_code' => '0012345681',
                'phone' => '09151234567',
                'email' => 'maryam@yahoo.com',
                'address' => 'مشهد، احمدآباد',
            ],
            [
                'full_name' => 'مهدی رضایی',
                'national_code' => '0012345682',
                'phone' => '09161234567',
                'email' => 'mehdi@gmail.com',
                'address' => 'تبریز، ولیعصر',
            ],
            [
                'full_name' => 'زهرا موسوی',
                'national_code' => '0012345683',
                'phone' => '09171234567',
                'email' => 'zahra@yahoo.com',
                'address' => 'کرج، عظیمیه',
            ],
        ];

        foreach ($investors as $investorData) {
            // اول Person رو ایجاد کن
            $person = Person::firstOrCreate(
                ['national_code' => $investorData['national_code']],
                $investorData
            );
            
            // بعد Investor رو بهش وصل کن
            Investor::firstOrCreate(
                ['person_id' => $person->id],
                [
                    'person_id' => $person->id,
                    'description' => 'سرمایه‌گذار خودکار ایجاد شده'
                ]
            );
        }
    }
}