<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            // حساب‌های بانکی
            [
                'type' => 'bank',
                'name' => 'بانک ملی - حساب جاری',
                'amount' => 125000000,
                'value' => 125000000,
                'description' => 'حساب اصلی شرکت',
            ],
            [
                'type' => 'bank',
                'name' => 'بانک ملت - سپرده',
                'amount' => 450000000,
                'value' => 450000000,
                'description' => 'سپرده بلندمدت با سود ۱۸٪',
            ],
            [
                'type' => 'bank',
                'name' => 'بانک تجارت - جاری',
                'amount' => 75000000,
                'value' => 75000000,
                'description' => 'برای هزینه‌های جاری',
            ],

            // دلار
            [
                'type' => 'dollar',
                'name' => 'دلار آمریکا',
                'amount' => 5000,
                'value' => 5000 * 600000, // نرخ ۶۰,۰۰۰ تومان
                'description' => 'ذخیره ارزی',
            ],
            [
                'type' => 'dollar',
                'name' => 'دلار آمریکا',
                'amount' => 2000,
                'value' => 2000 * 600000,
                'description' => 'برای واردات',
            ],

            // طلا
            [
                'type' => 'gold',
                'name' => 'سکه تمام بهار آزادی',
                'amount' => 10,
                'value' => 10 * 280000000, // هر سکه ۲۸۰ میلیون
                'description' => 'سکه سرمایه‌گذاری',
            ],
            [
                'type' => 'gold',
                'name' => 'نیم سکه',
                'amount' => 5,
                'value' => 5 * 140000000, // هر نیم سکه ۱۴۰ میلیون
                'description' => 'برای نقدشوندگی سریع',
            ],
            [
                'type' => 'gold',
                'name' => 'ربع سکه',
                'amount' => 8,
                'value' => 8 * 75000000, // هر ربع سکه ۷۵ میلیون
                'description' => 'سرمایه‌گذاری کوچک',
            ],
            [
                'type' => 'gold',
                'name' => 'طلای آب شده',
                'amount' => 150, // گرم
                'value' => 150 * 3500000, // هر گرم ۳.۵ میلیون
                'description' => 'طلای آب شده با خلوص ۱۸',
            ],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}