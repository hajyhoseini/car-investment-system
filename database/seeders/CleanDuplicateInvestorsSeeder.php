<?php
// database/seeders/CleanDuplicateInvestorsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Investor;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CleanDuplicateInvestorsSeeder extends Seeder
{
    public function run(): void
    {
        // پیدا کردن کاربرهایی که چند سرمایه‌گذار دارند
        $duplicates = DB::table('investors')
            ->select('user_id', DB::raw('COUNT(*) as count'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->having('count', '>', 1)
            ->get();
        
        foreach ($duplicates as $dup) {
            $investors = Investor::where('user_id', $dup->user_id)
                ->orderBy('created_at')
                ->get();
            
            // اولین سرمایه‌گذار رو نگه می‌داریم
            $keep = $investors->shift();
            
            // بقیه رو حذف می‌کنیم، ولی اول سرمایه‌گذاری‌هاشون رو منتقل می‌کنیم
            foreach ($investors as $investor) {
                Investment::where('investor_id', $investor->id)
                    ->update(['investor_id' => $keep->id]);
                $investor->delete();
            }
            
            // آپدیت total_invested
            $keep->updateTotalInvested();
        }
        
        $this->command->info('سرمایه‌گذارهای تکراری پاک‌سازی شدند.');
    }
}