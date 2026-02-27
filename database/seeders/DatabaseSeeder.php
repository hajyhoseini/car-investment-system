<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RolePermissionSeeder::class, // اگه قبلی داری
            CarSeeder::class,
            InvestorSeeder::class,
            InvestmentSeeder::class,
            CarSaleSeeder::class,
            AssetSeeder::class,
            LiabilitySeeder::class,
            PaymentMethodSeeder::class,
            PermissionSeeder::class, // سیدر جدید
        ]);
    }
}