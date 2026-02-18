<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarSale;
use App\Models\Car;

class CarSaleSeeder extends Seeder
{
    public function run(): void
    {
        $sales = [
            [
                'car_id' => 4, // تندر فروخته شده
                'selling_price' => 750000000,
                'total_profit' => 130000000,
                'sale_date' => '1402-12-10',
                'buyer_name' => 'احمد رضایی',
                'buyer_phone' => '09123456789',
            ],
            [
                'car_id' => 8, // تیبا فروخته شده
                'selling_price' => 380000000,
                'total_profit' => 60000000,
                'sale_date' => '1402-12-15',
                'buyer_name' => 'سعید کریمی',
                'buyer_phone' => '09129876543',
            ],
        ];

        foreach ($sales as $sale) {
            CarSale::create($sale);
            // به‌روزرسانی وضعیت خودرو
            Car::where('id', $sale['car_id'])->update(['status' => 'sold']);
        }
    }
}