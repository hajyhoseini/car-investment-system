<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            [
                'title' => 'پژو ۲۰۶ تیپ ۲',
                'brand' => 'پژو',
                'model' => '۲۰۶',
                'year' => 1400,
                'kilometers' => 45000,
                'fuel_type' => 'بنزین',
                'transmission' => 'دنده‌ای',
                'color' => 'سفید',
                'description' => 'ماشین سالم، روغ‌کاری شده، بدون رنگ',
                'purchase_price' => 450000000,
                'purchase_date' => '1402-05-15',
                'status' => 'available',
            ],
            [
                'title' => 'پراید ۱۳۱',
                'brand' => 'سایپا',
                'model' => '۱۳۱',
                'year' => 1399,
                'kilometers' => 62000,
                'fuel_type' => 'بنزین',
                'transmission' => 'دنده‌ای',
                'color' => 'نقره‌ای',
                'description' => 'موتور سالم، کولر سالم',
                'purchase_price' => 280000000,
                'purchase_date' => '1402-06-20',
                'status' => 'available',
            ],
            [
                'title' => 'سمند LX',
                'brand' => 'ایران خودرو',
                'model' => 'سمند',
                'year' => 1401,
                'kilometers' => 28000,
                'fuel_type' => 'بنزین',
                'transmission' => 'دنده‌ای',
                'color' => 'مشکی',
                'description' => 'صفر کیلومتر، فقط ۲۸۰۰۰ کارکرد',
                'purchase_price' => 550000000,
                'purchase_date' => '1402-07-10',
                'status' => 'reserved',
            ],
            [
                'title' => 'رنو تندر ۹۰',
                'brand' => 'رنو',
                'model' => 'تندر',
                'year' => 1398,
                'kilometers' => 85000,
                'fuel_type' => 'بنزین',
                'transmission' => 'دنده‌ای',
                'color' => 'سرمه‌ای',
                'description' => 'دست اول، سرویس شده',
                'purchase_price' => 620000000,
                'purchase_date' => '1402-04-05',
                'status' => 'sold',
            ],
            [
                'title' => 'هایما S7',
                'brand' => 'هایما',
                'model' => 'S7',
                'year' => 1402,
                'kilometers' => 5000,
                'fuel_type' => 'بنزین',
                'transmission' => 'اتوماتیک',
                'color' => 'طلایی',
                'description' => 'تقریبا نو، آپشن کامل',
                'purchase_price' => 1850000000,
                'purchase_date' => '1402-08-15',
                'status' => 'available',
            ],
            [
                'title' => 'کوییک R',
                'brand' => 'سایپا',
                'model' => 'کوییک',
                'year' => 1401,
                'kilometers' => 15000,
                'fuel_type' => 'بنزین',
                'transmission' => 'دنده‌ای',
                'color' => 'قرمز',
                'description' => 'بسیار تمیز، فول آپشن',
                'purchase_price' => 420000000,
                'purchase_date' => '1402-09-01',
                'status' => 'available',
            ],
            [
                'title' => 'دنا پلاس',
                'brand' => 'ایران خودرو',
                'model' => 'دنا',
                'year' => 1402,
                'kilometers' => 8000,
                'fuel_type' => 'بنزین',
                'transmission' => 'اتوماتیک',
                'color' => 'سفید',
                'description' => 'تیپ ۶، کروز کنترل',
                'purchase_price' => 980000000,
                'purchase_date' => '1402-10-20',
                'status' => 'available',
            ],
            [
                'title' => 'تیبا ۲',
                'brand' => 'سایپا',
                'model' => 'تیبا',
                'year' => 1400,
                'kilometers' => 35000,
                'fuel_type' => 'بنزین',
                'transmission' => 'دنده‌ای',
                'color' => 'نقره‌ای',
                'description' => 'باکس دنده سالم',
                'purchase_price' => 320000000,
                'purchase_date' => '1402-11-05',
                'status' => 'sold',
            ],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}