<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Investment;
use App\Models\Car;
use App\Models\Investor;

class InvestmentSeeder extends Seeder
{
    public function run(): void
    {
        $cars = Car::all();
        $investors = Investor::all();

        $investments = [
            // سرمایه‌گذاری روی پژو ۲۰۶
            [
                'car_id' => 1,
                'investor_id' => 1,
                'amount' => 225000000,
                'percentage' => 50,
                'investment_date' => '1402-05-16',
            ],
            [
                'car_id' => 1,
                'investor_id' => 2,
                'amount' => 135000000,
                'percentage' => 30,
                'investment_date' => '1402-05-17',
            ],
            [
                'car_id' => 1,
                'investor_id' => 3,
                'amount' => 90000000,
                'percentage' => 20,
                'investment_date' => '1402-05-18',
            ],

            // سرمایه‌گذاری روی پراید
            [
                'car_id' => 2,
                'investor_id' => 2,
                'amount' => 140000000,
                'percentage' => 50,
                'investment_date' => '1402-06-21',
            ],
            [
                'car_id' => 2,
                'investor_id' => 4,
                'amount' => 84000000,
                'percentage' => 30,
                'investment_date' => '1402-06-22',
            ],
            [
                'car_id' => 2,
                'investor_id' => 5,
                'amount' => 56000000,
                'percentage' => 20,
                'investment_date' => '1402-06-23',
            ],

            // سرمایه‌گذاری روی سمند
            [
                'car_id' => 3,
                'investor_id' => 3,
                'amount' => 330000000,
                'percentage' => 60,
                'investment_date' => '1402-07-11',
            ],
            [
                'car_id' => 3,
                'investor_id' => 6,
                'amount' => 220000000,
                'percentage' => 40,
                'investment_date' => '1402-07-12',
            ],

            // سرمایه‌گذاری روی تندر
            [
                'car_id' => 4,
                'investor_id' => 1,
                'amount' => 310000000,
                'percentage' => 50,
                'investment_date' => '1402-04-06',
            ],
            [
                'car_id' => 4,
                'investor_id' => 4,
                'amount' => 186000000,
                'percentage' => 30,
                'investment_date' => '1402-04-07',
            ],
            [
                'car_id' => 4,
                'investor_id' => 5,
                'amount' => 124000000,
                'percentage' => 20,
                'investment_date' => '1402-04-08',
            ],

            // سرمایه‌گذاری روی هایما
            [
                'car_id' => 5,
                'investor_id' => 2,
                'amount' => 925000000,
                'percentage' => 50,
                'investment_date' => '1402-08-16',
            ],
            [
                'car_id' => 5,
                'investor_id' => 3,
                'amount' => 555000000,
                'percentage' => 30,
                'investment_date' => '1402-08-17',
            ],
            [
                'car_id' => 5,
                'investor_id' => 6,
                'amount' => 370000000,
                'percentage' => 20,
                'investment_date' => '1402-08-18',
            ],

            // سرمایه‌گذاری روی کوییک
            [
                'car_id' => 6,
                'investor_id' => 1,
                'amount' => 210000000,
                'percentage' => 50,
                'investment_date' => '1402-09-02',
            ],
            [
                'car_id' => 6,
                'investor_id' => 4,
                'amount' => 126000000,
                'percentage' => 30,
                'investment_date' => '1402-09-03',
            ],
            [
                'car_id' => 6,
                'investor_id' => 5,
                'amount' => 84000000,
                'percentage' => 20,
                'investment_date' => '1402-09-04',
            ],

            // سرمایه‌گذاری روی دنا
            [
                'car_id' => 7,
                'investor_id' => 2,
                'amount' => 490000000,
                'percentage' => 50,
                'investment_date' => '1402-10-21',
            ],
            [
                'car_id' => 7,
                'investor_id' => 3,
                'amount' => 294000000,
                'percentage' => 30,
                'investment_date' => '1402-10-22',
            ],
            [
                'car_id' => 7,
                'investor_id' => 6,
                'amount' => 196000000,
                'percentage' => 20,
                'investment_date' => '1402-10-23',
            ],

            // سرمایه‌گذاری روی تیبا (فروخته شده)
            [
                'car_id' => 8,
                'investor_id' => 1,
                'amount' => 160000000,
                'percentage' => 50,
                'investment_date' => '1402-11-06',
            ],
            [
                'car_id' => 8,
                'investor_id' => 4,
                'amount' => 96000000,
                'percentage' => 30,
                'investment_date' => '1402-11-07',
            ],
            [
                'car_id' => 8,
                'investor_id' => 5,
                'amount' => 64000000,
                'percentage' => 20,
                'investment_date' => '1402-11-08',
            ],
        ];

        foreach ($investments as $investment) {
            Investment::create($investment);
        }

        // به‌روزرسانی total_invested برای هر سرمایه‌گذار
        foreach ($investors as $investor) {
            $investor->updateTotalInvested();
        }
    }
}