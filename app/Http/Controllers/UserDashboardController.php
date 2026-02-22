<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment;
use App\Models\Car;
use App\Models\CarSale;
use App\Models\Investor;
use Illuminate\Support\Facades\Log;

class UserDashboardController extends Controller
{
    /**
     * نمایش داشبورد شخصی کاربر
     */
    public function index()
    {
        $user = auth()->user();
        
        // استفاده از متد کمکی برای گرفتن یا ایجاد پروفایل سرمایه‌گذار
        $investor = $this->getInvestorProfile($user);
        
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

    /**
     * متد کمکی برای گرفتن یا ایجاد پروفایل سرمایه‌گذار
     */
    private function getInvestorProfile($user)
    {
        // اگه کاربر قبلاً سرمایه‌گذار داره، همون رو برگردون
        if ($user->investor) {
            return $user->investor;
        }
        
        // اگه سرمایه‌گذار نداره، بر اساس ایمیل جستجو کن
        $investor = Investor::where('email', $user->email)->first();
        
        if ($investor) {
            // سرمایه‌گذار با این ایمیل وجود داره، فقط user_id رو ست کن
            $investor->user_id = $user->id;
            $investor->full_name = $user->name; // آپدیت نام
            $investor->save();
            return $investor;
        }
        
        // اگه هیچ سرمایه‌گذاری پیدا نشد، یک نمونه جدید ایجاد کن
        $investor = Investor::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'national_code' => '0000000000' . $user->id, // کد ملی یکتا
            'phone' => '09120000000',
            'email' => $user->email,
            'address' => 'تهران',
            'total_invested' => 0,
        ]);
        
        return $investor;
    }

    /**
     * نمایش سودهای من (اختیاری)
     */
    public function profits()
    {
        $user = auth()->user();
        $investor = $this->getInvestorProfile($user);
        
        $profits = CarSale::whereHas('car.investments', function($query) use ($investor) {
            $query->where('investor_id', $investor->id);
        })->with('car')->get();
        
        $totalProfit = 0;
        foreach ($profits as $sale) {
            $investment = $sale->car->investments()
                ->where('investor_id', $investor->id)
                ->first();
            if ($investment) {
                $sale->investor_profit = ($sale->total_profit * $investment->percentage) / 100;
                $totalProfit += $sale->investor_profit;
            }
        }
        
        return view('user.profits', compact('profits', 'totalProfit', 'investor'));
    }

    /**
     * نمایش خودروهای سرمایه‌گذاری شده (اختیاری)
     */
    public function cars()
    {
        $user = auth()->user();
        $investor = $this->getInvestorProfile($user);
        
        $cars = Car::whereHas('investments', function($query) use ($investor) {
            $query->where('investor_id', $investor->id);
        })->with(['investments' => function($query) use ($investor) {
            $query->where('investor_id', $investor->id);
        }])->get();
        
        return view('user.cars', compact('cars', 'investor'));
    }
}