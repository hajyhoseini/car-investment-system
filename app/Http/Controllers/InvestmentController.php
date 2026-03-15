<?php
// app/Http/Controllers/InvestmentController.php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Investor;
use App\Models\Investment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::with(['car', 'investor'])->latest()->paginate(10);
        
        // تبدیل تاریخ‌ها به شمسی برای نمایش (با ساعت)
        foreach ($investments as $investment) {
            $investment->jalali_date = jalali_datetime($investment->investment_date);
        }
        
        return view('investments.index', compact('investments'));
    }

    public function create()
    {
        $cars = Car::where('status', 'available')
            ->with('investments')
            ->get()
            ->filter(function($car) {
                return $car->total_invested < $car->purchase_price;
            });
        
        $investors = Investor::all();
        
        $todayJalali = now_jalali('Y/m/d');
        
        return view('investments.create', compact('cars', 'investors', 'todayJalali'));
    }

    public function store(Request $request)
    {
        // اعتبارسنجی با تاریخ شمسی
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'investor_id' => 'required|exists:investors,id',
            'amount' => 'required|numeric|min:1000',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'investment_date' => 'required|string',
        ]);

        // تبدیل تاریخ شمسی به میلادی
        $gregorianDate = jalali_to_gregorian($request->investment_date);
        
        // گرفتن ساعت به وقت تهران
        $tehranTime = Carbon::now('Asia/Tehran')->format('H:i:s');
        
        // ترکیب تاریخ و ساعت
        $validated['investment_date'] = $gregorianDate . ' ' . $tehranTime;

        $car = Car::find($validated['car_id']);
        
        // بررسی اینکه خودرو قابل سرمایه‌گذاری هست یا نه
        if ($car->status != 'available') {
            return back()->withErrors(['car_id' => 'این خودرو قابل سرمایه‌گذاری نیست.'])->withInput();
        }
        
        // محاسبه مجموع سرمایه‌گذاری‌های قبلی
        $totalInvested = $car->investments()->sum('amount');
        
        // بررسی اینکه خودرو کامل نشده
        if ($totalInvested >= $car->purchase_price) {
            return back()->withErrors(['car_id' => 'سرمایه‌ی این خودرو کامل شده و قابل سرمایه‌گذاری نیست.'])->withInput();
        }
        
        // بررسی اینکه مجموع سرمایه‌گذاری‌ها از قیمت خودرو بیشتر نشه
        if (($totalInvested + $validated['amount']) > $car->purchase_price) {
            $remaining = $car->purchase_price - $totalInvested;
            return back()->withErrors(['amount' => "مجموع سرمایه‌گذاری‌ها نمی‌تواند از قیمت خودرو بیشتر باشد. مبلغ باقی‌مانده: " . fa_currency($remaining) . " ریال"])->withInput();
        }

        $investment = Investment::create($validated);
        
        // به‌روزرسانی کل سرمایه‌گذاری سرمایه‌گذار
        $investment->investor->updateTotalInvested();
        
        // بررسی اینکه آیا بعد از این سرمایه‌گذاری، خودرو کامل شده؟
        $newTotalInvested = $car->investments()->sum('amount');
        if ($newTotalInvested >= $car->purchase_price) {
            // می‌تونیم خودرو رو رزرو کنیم یا یه نوتیف بفرستیم
            // $car->update(['status' => 'reserved']);
        }

        return redirect()->route('investments.index')->with('success', 'سرمایه‌گذاری با موفقیت ثبت شد.');
    }

    public function show(Investment $investment)
    {
        $investment->load(['car', 'investor']);
        
        // تبدیل تاریخ به شمسی برای نمایش (با ساعت)
        $investment->jalali_date = jalali_datetime($investment->investment_date);
        
        return view('investments.show', compact('investment'));
    }

    /**
     * نمایش فرم ویرایش با اطلاعات کامل
     */
    public function edit(Investment $investment)
    {
        // دریافت لیست خودروهای موجود
        $cars = Car::where('status', 'available')
            ->with('investments')
            ->get()
            ->map(function($car) use ($investment) {
                // محاسبه مجموع سرمایه‌گذاری‌ها به جز این سرمایه‌گذاری
                $totalInvested = $car->investments()
                    ->where('id', '!=', $investment->id)
                    ->sum('amount');
                $car->available_for_investment = $car->purchase_price - $totalInvested;
                return $car;
            });
        
        $investors = Investor::all();
        
        // تبدیل تاریخ به شمسی برای نمایش در فرم
        $investment->jalali_date = jalali_date($investment->investment_date);
        
        // محاسبه باقی‌مانده برای این خودرو
        $car = $investment->car;
        $totalInvestedInCar = $car->investments()
            ->where('id', '!=', $investment->id)
            ->sum('amount');
        $remainingForCar = $car->purchase_price - $totalInvestedInCar;

        return view('investments.edit', compact(
            'investment', 
            'cars', 
            'investors',
            'remainingForCar'
        ));
    }

    /**
     * بروزرسانی سرمایه‌گذاری
     */
    public function update(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'investor_id' => 'required|exists:investors,id',
            'amount' => 'required|numeric|min:1000',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'investment_date' => 'required|string',
        ]);

        // تبدیل تاریخ شمسی به میلادی
        $gregorianDate = jalali_to_gregorian($request->investment_date);
        
        // گرفتن ساعت به وقت تهران
        $tehranTime = Carbon::now('Asia/Tehran')->format('H:i:s');
        
        // ترکیب تاریخ و ساعت
        $validated['investment_date'] = $gregorianDate . ' ' . $tehranTime;
        
        // دریافت خودروی جدید
        $newCar = Car::find($validated['car_id']);
        
        // اگر خودرو تغییر کرده
        if ($investment->car_id != $validated['car_id']) {
            // محاسبه مجموع سرمایه‌گذاری‌ها در خودروی جدید (بدون احتساب این سرمایه‌گذاری)
            $totalInvestedInNewCar = $newCar->investments()->sum('amount');
            
            // بررسی محدودیت
            if (($totalInvestedInNewCar + $validated['amount']) > $newCar->purchase_price) {
                $remaining = $newCar->purchase_price - $totalInvestedInNewCar;
                return back()->withErrors([
                    'amount' => 'مجموع سرمایه‌گذاری‌ها در خودروی جدید نمی‌تواند از قیمت آن بیشتر باشد. '
                        . 'مبلغ باقی‌مانده: ' . fa_currency($remaining)
                ])->withInput();
            }
        } else {
            // خودرو ثابت است، فقط مبلغ تغییر کرده
            $totalInvested = $newCar->investments()
                ->where('id', '!=', $investment->id)
                ->sum('amount');
            
            if (($totalInvested + $validated['amount']) > $newCar->purchase_price) {
                $remaining = $newCar->purchase_price - $totalInvested;
                return back()->withErrors([
                    'amount' => 'مجموع سرمایه‌گذاری‌ها نمی‌تواند از قیمت خودرو بیشتر باشد. '
                        . 'مبلغ باقی‌مانده: ' . fa_currency($remaining)
                ])->withInput();
            }
        }

        // بررسی درصد (اختیاری - می‌تونی خودکار محاسبه کنی)
        $calculatedPercentage = ($validated['amount'] / $newCar->purchase_price) * 100;
        if (abs($calculatedPercentage - $validated['percentage']) > 0.01) {
            // اگر درصد با مبلغ همخوانی نداشت، می‌تونی خطا بدی یا خودکار اصلاح کنی
            // اینجا خودکار اصلاح می‌کنیم
            $validated['percentage'] = $calculatedPercentage;
        }

        // به‌روزرسانی سرمایه‌گذاری
        $investment->update($validated);
        
        // به‌روزرسانی کل سرمایه‌گذاری سرمایه‌گذار قبلی و جدید
        $investment->investor->updateTotalInvested();
        if ($investment->investor_id != $validated['investor_id']) {
            Investor::find($validated['investor_id'])->updateTotalInvested();
        }

        return redirect()->route('investments.index')
            ->with('success', 'سرمایه‌گذاری با موفقیت ویرایش شد.');
    }

    public function destroy(Investment $investment)
    {
        $investor = $investment->investor;
        $investment->delete();
        $investor->updateTotalInvested();
        
        return redirect()->route('investments.index')
            ->with('success', 'سرمایه‌گذاری با موفقیت حذف شد.');
    }
}