<?php
// database/seeders/PaymentMethodSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'نقدی',
                'type' => 'cash',
                'description' => 'پرداخت نقدی',
                'is_active' => true,
            ],
            [
                'name' => 'چک',
                'type' => 'check',
                'description' => 'پرداخت با چک',
                'is_active' => true,
            ],
            [
                'name' => 'کارت به کارت',
                'type' => 'card_to_card',
                'description' => 'انتقال کارت به کارت',
                'is_active' => true,
            ],
            [
                'name' => 'حواله بانکی',
                'type' => 'transfer',
                'description' => 'حواله بین بانکی',
                'is_active' => true,
            ],
            [
                'name' => 'سایر',
                'type' => 'other',
                'description' => 'سایر روش‌های پرداخت',
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(
                ['name' => $method['name']],
                $method
            );
        }
    }
}