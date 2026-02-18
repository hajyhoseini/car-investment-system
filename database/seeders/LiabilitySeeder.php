<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Liability;

class LiabilitySeeder extends Seeder
{
    public function run(): void
    {
        $liabilities = [
            // بدهی‌ها
            [
                'type' => 'debt',
                'creditor_name' => 'حسین رحیمی',
                'amount' => 150000000,
                'remaining_amount' => 150000000,
                'due_date' => '1403-03-15',
                'status' => 'pending',
                'description' => 'قرض الحسنه',
            ],
            [
                'type' => 'debt',
                'creditor_name' => 'شرکت پخش البرز',
                'amount' => 85000000,
                'remaining_amount' => 85000000,
                'due_date' => '1403-02-20',
                'status' => 'pending',
                'description' => 'بابت خرید قطعات',
            ],

            // چک‌ها
            [
                'type' => 'check',
                'creditor_name' => 'نمایندگی ایران خودرو',
                'amount' => 550000000,
                'remaining_amount' => 550000000,
                'due_date' => '1403-04-01',
                'status' => 'pending',
                'description' => 'چک تضمینی برای خرید خودرو',
            ],
            [
                'type' => 'check',
                'creditor_name' => 'تعمیرگاه مجاز',
                'amount' => 45000000,
                'remaining_amount' => 45000000,
                'due_date' => '1403-01-30',
                'status' => 'overdue',
                'description' => 'چک بابت تعمیرات',
            ],
            [
                'type' => 'check',
                'creditor_name' => 'نمایشگاه خودرو اطلس',
                'amount' => 320000000,
                'remaining_amount' => 0,
                'due_date' => '1402-12-20',
                'status' => 'paid',
                'description' => 'پرداخت شده',
            ],

            // اقساط
            [
                'type' => 'installment',
                'creditor_name' => 'بانک اقتصاد نوین',
                'amount' => 900000000,
                'remaining_amount' => 600000000,
                'due_date' => '1403-06-15',
                'status' => 'pending',
                'description' => 'وام خرید خودرو - ۱۲ قسط ۷۵ میلیونی، ۸ قسط باقی‌مانده',
            ],
            [
                'type' => 'installment',
                'creditor_name' => 'لیزینگ خودرو',
                'amount' => 480000000,
                'remaining_amount' => 160000000,
                'due_date' => '1403-03-25',
                'status' => 'pending',
                'description' => 'اجاره به شرط تملیک - ۲ قسط باقی‌مانده',
            ],
            [
                'type' => 'installment',
                'creditor_name' => 'صندوق کارآفرینی امید',
                'amount' => 300000000,
                'remaining_amount' => 100000000,
                'due_date' => '1403-02-10',
                'status' => 'pending',
                'description' => 'تسهیلات کسب و کار - ۲ قسط آخر',
            ],
        ];

        foreach ($liabilities as $liability) {
            Liability::create($liability);
        }
    }
}