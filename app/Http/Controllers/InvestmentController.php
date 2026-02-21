<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Investor;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Traits\JalaliDateTrait;

class InvestmentController extends Controller
{
    use JalaliDateTrait;

    public function index()
    {
        $investments = Investment::with(['car', 'investor'])->latest()->paginate(10);
        
        // تبدیل تاریخ‌ها به شمسی برای نمایش
        foreach ($investments as $investment) {
            $investment->jalali_date = $this->convertToJalali($investment->investment_date);
        }
        
        return view('investments.index', compact('investments'));
    }

    public function create()
    {
        $cars = Car::where('status', 'available')->get();
        $investors = Investor::all();
        
        // تاریخ پیش‌فرض شمسی برای امروز
        $todayJalali = $this->nowJalali();
        
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
            'investment_date' => 'required|string', // تاریخ به صورت شمسی دریافت می‌شود
        ]);

        // تبدیل تاریخ شمسی به میلادی برای ذخیره در دیتابیس
        $validated['investment_date'] = $this->convertToGregorian($request->investment_date);

        $car = Car::find($validated['car_id']);
        
        // محاسبه مجموع سرمایه‌گذاری‌های قبلی
        $totalInvested = $car->investments()->sum('amount');
        
        // بررسی اینکه مجموع سرمایه‌گذاری‌ها از قیمت خودرو بیشتر نشه
        if (($totalInvested + $validated['amount']) > $car->purchase_price) {
            return back()->withErrors(['amount' => 'مجموع سرمایه‌گذاری‌ها نمی‌تواند از قیمت خودرو بیشتر باشد.'])->withInput();
        }

        $investment = Investment::create($validated);
        
        // به‌روزرسانی کل سرمایه‌گذاری سرمایه‌گذار
        $investment->investor->updateTotalInvested();

        return redirect()->route('investments.index')->with('success', 'سرمایه‌گذاری با موفقیت ثبت شد.');
    }

    public function show(Investment $investment)
    {
        $investment->load(['car', 'investor']);
        
        // تبدیل تاریخ به شمسی برای نمایش
        $investment->jalali_date = $this->convertToJalali($investment->investment_date);
        
        return view('investments.show', compact('investment'));
    }

    public function edit(Investment $investment)
    {
        $cars = Car::where('status', 'available')->get();
        $investors = Investor::all();
        
        // تبدیل تاریخ ذخیره شده به شمسی برای نمایش در فرم ویرایش
        $investment->jalali_date = $this->convertToJalali($investment->investment_date);
        
        return view('investments.edit', compact('investment', 'cars', 'investors'));
    }

    public function update(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'investor_id' => 'required|exists:investors,id',
            'amount' => 'required|numeric|min:1000',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'investment_date' => 'required|string', // تاریخ به صورت شمسی دریافت می‌شود
        ]);

        // تبدیل تاریخ شمسی به میلادی برای ذخیره در دیتابیس
        $validated['investment_date'] = $this->convertToGregorian($request->investment_date);

        // اگر خودرو تغییر کرده
        if ($investment->car_id != $validated['car_id']) {
            // بررسی خودروی جدید
            $newCar = Car::find($validated['car_id']);
            $totalInvestedInNewCar = $newCar->investments()->sum('amount');
            
            if (($totalInvestedInNewCar + $validated['amount']) > $newCar->purchase_price) {
                return back()->withErrors(['amount' => 'مجموع سرمایه‌گذاری‌ها در خودروی جدید نمی‌تواند از قیمت آن بیشتر باشد.'])->withInput();
            }
        } else {
            // خودرو ثابت است، فقط مبلغ تغییر کرده
            $car = $investment->car;
            $totalInvested = $car->investments()->where('id', '!=', $investment->id)->sum('amount');
            
            if (($totalInvested + $validated['amount']) > $car->purchase_price) {
                return back()->withErrors(['amount' => 'مجموع سرمایه‌گذاری‌ها نمی‌تواند از قیمت خودرو بیشتر باشد.'])->withInput();
            }
        }

        // به‌روزرسانی سرمایه‌گذاری
        $investment->update($validated);
        
        // به‌روزرسانی کل سرمایه‌گذاری سرمایه‌گذار قبلی و جدید
        $investment->investor->updateTotalInvested();
        if ($investment->investor_id != $validated['investor_id']) {
            Investor::find($validated['investor_id'])->updateTotalInvested();
        }

        return redirect()->route('investments.index')->with('success', 'سرمایه‌گذاری با موفقیت ویرایش شد.');
    }

    public function destroy(Investment $investment)
    {
        $investor = $investment->investor;
        $investment->delete();
        $investor->updateTotalInvested();
        
        return redirect()->route('investments.index')->with('success', 'سرمایه‌گذاری با موفقیت حذف شد.');
    }
}