<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ساخت کاربر ادمین
        User::create([
            'name' => 'مدیر سیستم',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '09123456789',
            'bio' => 'مدیر اصلی سیستم',
        ]);

        // ساخت چند کاربر با فکتوری (اگه فکتوری داری)
        User::factory(5)->create(); // این ۵ تا کاربر می‌سازه

        // یا ساخت کاربرای مشخص
        $users = [
            [
                'name' => 'کاربر یک',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
                'phone' => '09123456788',
            ],
            [
                'name' => 'کاربر دو',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
                'phone' => '09123456787',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}