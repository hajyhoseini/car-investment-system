<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment;
use App\Models\Car;
use App\Models\CarSale;
use Illuminate\Support\Facades\Log;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // استفاده از متد کمکی برای گرفتن یا ایجاد پروفایل سرمایه‌گذار
        $investor = $user->getInvestorProfile();
        
        // اگه کاربر سرمایه‌گذار هست
        if ($investor) {
            // سرمایه‌گذاری‌های من
            $myInvestments = Investment::where('investor_id', $investor->id)
                ->with('car')
                ->latest()
                ->paginate(10);
            
            // خودروهایی که در آنها سرمایه‌گذاری کردم
            $myCars = Car::whereHas('investments', function($query) use ($investor) {
                $query->where('investor_id', $investor->id);
            })->get();
            
            // سودهای من
            $myProfits = CarSale::whereHas('car.investments', function($query) use ($investor) {
                $query->where('investor_id', $investor->id);
            })->with('car')->get();
            
            // محاسبه کل سود
            $totalProfit = 0;
            foreach ($myProfits as $sale) {
                $investment = $sale->car->investments()
                    ->where('investor_id', $investor->id)
                    ->first();
                if ($investment) {
                    $totalProfit += ($sale->total_profit * $investment->percentage) / 100;
                }
            }
            
            return view('user.dashboard', compact(
                'myInvestments', 
                'myCars', 
                'myProfits', 
                'totalProfit',
                'investor'
            ));
        }
        
        // اینجا نباید بیاد، چون getInvestorProfile حتماً یه چیزی برمی‌گردونه
        return view('user.dashboard', [
            'message' => 'خطا در ایجاد پروفایل سرمایه‌گذار. لطفاً با ادمین تماس بگیرید.'
        ]);
    }
}