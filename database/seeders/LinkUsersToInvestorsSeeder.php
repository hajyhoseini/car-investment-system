<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Investor;

class LinkUsersToInvestorsSeeder extends Seeder
{
    public function run(): void
    {
        // ارتباط سارا احمدی (ایمیل sara@example.com) با سرمایه‌گذار سارا احمدی
        $saraUser = User::where('email', 'sara@example.com')->first();
        $saraInvestor = Investor::where('email', 'sara@example.com')->first();
        if ($saraUser && $saraInvestor) {
            $saraInvestor->user_id = $saraUser->id;
            $saraInvestor->save();
        }

        // ارتباط علی محمدی (ایمیل ali@example.com) با سرمایه‌گذار علی محمدی
        $aliUser = User::where('email', 'ali@example.com')->first();
        $aliInvestor = Investor::where('full_name', 'علی محمدی')->first();
        if ($aliUser && $aliInvestor) {
            $aliInvestor->user_id = $aliUser->id;
            $aliInvestor->save();
        }

        // ارتباط رضا کریمی (ایمیل reza@example.com) با سرمایه‌گذار رضا کریمی
        $rezaUser = User::where('email', 'reza@example.com')->first();
        $rezaInvestor = Investor::where('full_name', 'رضا کریمی')->first();
        if ($rezaUser && $rezaInvestor) {
            $rezaInvestor->user_id = $rezaUser->id;
            $rezaInvestor->save();
        }

        $this->command->info('ارتباط کاربران با سرمایه‌گذاران برقرار شد.');
    }
}