<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment;
use App\Models\Car;
use App\Models\CarSale;
use App\Models\Investor;
use App\Models\Person;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        
        if (!$investor) {
            // کاربر سرمایه‌گذار نیست
            $userRole = $this->getUserRole($user);
            $message = $this->getInvestorMessage($userRole);
            
            return view('user.dashboard', compact('userRole', 'message'));
        }
        
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
        
        // نقش کاربر برای نمایش
        $userRole = $this->getUserRole($user);
        
        return view('user.dashboard', compact(
            'myInvestments', 
            'myCars', 
            'myProfits', 
            'totalProfit',
            'investor',
            'userRole'
        ));
    }

    /**
     * متد کمکی برای گرفتن یا ایجاد پروفایل سرمایه‌گذار
     */
    private function getInvestorProfile($user)
    {
        try {
            // اگه کاربر قبلاً سرمایه‌گذار داره، همون رو برگردون
            if ($user->investor) {
                return $user->investor;
            }
            
            // جستجوی سرمایه‌گذار با person_id مرتبط با ایمیل کاربر
            // ابتدا person رو با ایمیل کاربر پیدا می‌کنیم
            $person = Person::where('email', $user->email)->first();
            
            if ($person) {
                // person وجود داره، ببینیم سرمایه‌گذار با این person_id داریم؟
                $investor = Investor::where('person_id', $person->id)->first();
                
                if ($investor) {
                    // سرمایه‌گذار وجود داره، user_id رو ست کن
                    $investor->user_id = $user->id;
                    $investor->save();
                    return $investor;
                }
            }
            
            // اگه هیچ سرمایه‌گذاری پیدا نشد، null برگردون
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error in getInvestorProfile: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * متد کمکی برای تعیین نقش کاربر
     */
    private function getUserRole($user)
    {
        if ($user->hasRole('admin')) {
            return 'admin';
        } elseif ($user->hasRole('manager')) {
            return 'manager';
        } elseif ($user->hasRole('investor') || $user->investor) {
            return 'investor';
        }
        
        return 'user';
    }

    /**
     * متد کمکی برای دریافت پیام مناسب
     */
    private function getInvestorMessage($role)
    {
        $messages = [
            'admin' => 'شما به عنوان ادمین وارد شده‌اید. برای مشاهده اطلاعات سرمایه‌گذاری، با حساب سرمایه‌گذار وارد شوید.',
            'manager' => 'شما به عنوان مدیر وارد شده‌اید. دسترسی به اطلاعات سرمایه‌گذاری شخصی نیاز به حساب سرمایه‌گذار دارد.',
            'investor' => 'حساب سرمایه‌گذاری شما در حال تنظیم است. لطفاً با پشتیبانی تماس بگیرید.',
            'user' => 'شما حساب سرمایه‌گذار ندارید. برای سرمایه‌گذاری، ابتدا ثبت‌نام کنید.'
        ];
        
        return $messages[$role] ?? $messages['user'];
    }

    /**
     * نمایش سودهای من
     */
    public function profits()
    {
        $user = auth()->user();
        $investor = $this->getInvestorProfile($user);
        
        if (!$investor) {
            return redirect()->route('user.dashboard')
                ->with('error', 'شمار سرمایه‌گذار نیستید.');
        }
        
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
     * نمایش خودروهای سرمایه‌گذاری شده
     */
    public function cars()
    {
        $user = auth()->user();
        $investor = $this->getInvestorProfile($user);
        
        if (!$investor) {
            return redirect()->route('user.dashboard')
                ->with('error', 'شمار سرمایه‌گذار نیستید.');
        }
        
        $cars = Car::whereHas('investments', function($query) use ($investor) {
            $query->where('investor_id', $investor->id);
        })->with(['investments' => function($query) use ($investor) {
            $query->where('investor_id', $investor->id);
        }])->get();
        
        return view('user.cars', compact('cars', 'investor'));
    }

    /**
     * ایجاد حساب سرمایه‌گذار جدید (برای ادمین‌ها)
     */
    public function createInvestor(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->can('create investors')) {
            return redirect()->back()->with('error', 'شما مجوز ایجاد سرمایه‌گذار ندارید.');
        }
        
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id|unique:investors,person_id',
            'description' => 'nullable|string'
        ]);
        
        $investor = Investor::create([
            'person_id' => $validated['person_id'],
            'user_id' => $user->id,
            'description' => $validated['description'] ?? null
        ]);
        
        return redirect()->route('investors.show', $investor)
            ->with('success', 'سرمایه‌گذار با موفقیت ایجاد شد.');
    }
}