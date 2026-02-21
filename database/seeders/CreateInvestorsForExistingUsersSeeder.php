<?php
// database/seeders/CreateInvestorsForExistingUsersSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Investor;

class CreateInvestorsForExistingUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $created = 0;
        $skipped = 0;

        foreach ($users as $user) {
            if (!$user->investor) {
                Investor::create([
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'national_code' => '000000000' . $user->id,
                    'phone' => '09120000000',
                    'email' => $user->email,
                    'address' => 'تهران',
                    'total_invested' => 0,
                ]);
                $created++;
            } else {
                $skipped++;
            }
        }

        $this->command->info("{$created} سرمایه‌گذار جدید ایجاد شد. {$skipped} کاربر قبلاً سرمایه‌گذار داشتند.");
    }
}