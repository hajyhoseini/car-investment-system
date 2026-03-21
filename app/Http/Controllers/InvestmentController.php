<?php
// app/Http/Controllers/InvestmentController.php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Investor;
use App\Models\Investment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class InvestmentController extends Controller
{
  public function index(Request $request)
{
    $query = Investment::with(['car', 'investor.person']); // اضافه کردن person به لود

    // فیلتر جستجو بر اساس نام خودرو یا سرمایه‌گذار
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('car', function($car) use ($search) {
                $car->where('title', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            })->orWhereHas('investor.person', function($person) use ($search) { // از طریق person جستجو کن
                $person->where('full_name', 'like', "%{$search}%")
                    ->orWhere('national_code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        });
    }

    // فیلتر بر اساس حداقل مبلغ
    if ($request->filled('min_amount')) {
        $minAmount = str_replace(',', '', $request->min_amount);
        $query->where('amount', '>=', $minAmount);
    }

    // فیلتر بر اساس حداکثر مبلغ
    if ($request->filled('max_amount')) {
        $maxAmount = str_replace(',', '', $request->max_amount);
        $query->where('amount', '<=', $maxAmount);
    }

    // فیلتر بر اساس خودرو
    if ($request->filled('car_id')) {
        $query->where('car_id', $request->car_id);
    }

    // فیلتر بر اساس سرمایه‌گذار
    if ($request->filled('investor_id')) {
        $query->where('investor_id', $request->investor_id);
    }

    // فیلتر بر اساس بازه تاریخ
    if ($request->filled('start_date')) {
        $startDate = jalali_to_gregorian($request->start_date);
        $query->whereDate('investment_date', '>=', $startDate);
    }

    if ($request->filled('end_date')) {
        $endDate = jalali_to_gregorian($request->end_date);
        $query->whereDate('investment_date', '<=', $endDate);
    }

    $investments = $query->latest()->paginate(20);

    // محاسبه آمار با در نظر گرفتن فیلترها
    $totalAmount = $investments->sum('amount');
    $totalCount = $investments->total();
    $averageAmount = $investments->avg('amount') ?? 0;

    // تهیه لیست خودروها برای کامپوننت فیلتر
    $cars = Car::orderBy('title')->get();
    $carOptions = $cars->map(function($car) {
        return [
            'id' => $car->id,
            'text' => $car->title . ' - ' . $car->brand . ' ' . $car->model,
            'subtext' => number_format($car->purchase_price) . ' ریال'
        ];
    })->prepend(['id' => '', 'text' => 'همه خودروها'])->values()->toArray();

    // تهیه لیست سرمایه‌گذاران برای کامپوننت فیلتر (با استفاده از person)
    $investors = Investor::with('person')->get();
    $investorOptions = $investors->map(function($investor) {
        $person = $investor->person;
        $text = $person->full_name ?? 'نامشخص';
        if ($person->national_code) {
            $text .= ' (کد ملی: ' . $person->national_code . ')';
        }
        return [
            'id' => $investor->id,
            'text' => $text,
            'subtext' => $person->phone ?? ''
        ];
    })->prepend(['id' => '', 'text' => 'همه سرمایه‌گذاران'])->values()->toArray();

    // تبدیل تاریخ‌ها به شمسی برای نمایش
    foreach ($investments as $investment) {
        $investment->jalali_date = jalali_datetime($investment->investment_date);
    }

    return view('investments.index', compact(
        'investments',
        'totalAmount',
        'totalCount',
        'averageAmount',
        'carOptions',
        'investorOptions'
    ));
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
        // اعتبارسنجی - دیگه percentage رو چک نمی‌کنیم
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'investor_id' => 'required|exists:investors,id',
            'amount' => 'required|numeric|min:1',
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
            return back()->withErrors(['amount' => "مجموع سرمایه‌گذاری‌ها نمی‌تواند از قیمت خودرو بیشتر باشد. مبلغ باقی‌مانده: " . number_format($remaining) . " ریال"])->withInput();
        }

        // محاسبه درصد در سرور
        $validated['percentage'] = ($validated['amount'] / $car->purchase_price) * 100;

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

  public function edit(Investment $investment)
{
    // دریافت لیست خودروهای موجود
    $cars = Car::where('status', 'available')
        ->with('investments')
        ->get();
    
    // فرمت کردن خودروها برای کامپوننت searchable-select
    $formattedCars = $cars->map(function($car) use ($investment) {
        // محاسبه مجموع سرمایه‌گذاری‌ها به جز این سرمایه‌گذاری
        $totalInvested = $car->investments()
            ->where('id', '!=', $investment->id)
            ->sum('amount');
        
        $remaining = $car->purchase_price - $totalInvested;
        $fundedPercentage = $car->purchase_price > 0 ? ($totalInvested / $car->purchase_price) * 100 : 0;
        
        return [
            'id' => $car->id,
            'text' => $car->title . ' - ' . $car->brand . ' ' . $car->model,
            'subtext' => number_format($car->purchase_price) . ' ریال - ' . 
                         number_format($fundedPercentage, 1) . '% تأمین - ' .
                         number_format($remaining) . ' ریال باقی‌مانده',
            'data' => [
                'price' => $car->purchase_price,
                'remaining' => $remaining,
                'total_invested' => $totalInvested
            ]
        ];
    })->values();
    
    // فرمت کردن سرمایه‌گذاران برای کامپوننت searchable-select
    $investors = Investor::all();
    $formattedInvestors = $investors->map(function($investor) {
        return [
            'id' => $investor->id,
            'text' => $investor->full_name,
            'subtext' => $investor->user_id == auth()->id() ? 'شما' : '',
            'data' => [
                'national_code' => $investor->national_code,
                'phone' => $investor->phone
            ]
        ];
    })->values();
    
    // تاریخ رو به فرمت صحیح تبدیل کن
    // اگه تاریخ به صورت شمسی در دیتابیسه، باید اول به میلادی تبدیل بشه
    $investmentDate = $investment->investment_date;
    
    // بررسی کن ببینیم تاریخ میلادی هست یا شمسی
    if (preg_match('/^[1-4]\d{3}\/\d{1,2}\/\d{1,2}/', $investmentDate)) {
        // اگه شمسی بود، به میلادی تبدیل کن
        $gregorianDate = jalali_to_gregorian($investmentDate);
        $carbonDate = Carbon::parse($gregorianDate);
    } else {
        // اگه میلادی بود، مستقیم استفاده کن
        $carbonDate = Carbon::parse($investmentDate);
    }
    
    $investment->jalali_date = Jalalian::fromCarbon($carbonDate)->format('Y/m/d');
    
    // محاسبه باقی‌مانده برای این خودرو
    $car = $investment->car;
    $totalInvestedInCar = $car->investments()
        ->where('id', '!=', $investment->id)
        ->sum('amount');
    $remainingForCar = $car->purchase_price - $totalInvestedInCar;

    return view('investments.edit', compact(
        'investment', 
        'formattedCars',
        'formattedInvestors',
        'remainingForCar'
    ));
}
    public function update(Request $request, Investment $investment)
    {
        // اعتبارسنجی - دیگه percentage رو چک نمی‌کنیم
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'investor_id' => 'required|exists:investors,id',
            'amount' => 'required|numeric|min:1000',
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
                        . 'مبلغ باقی‌مانده: ' . number_format($remaining) . ' ریال'
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
                        . 'مبلغ باقی‌مانده: ' . number_format($remaining) . ' ریال'
                ])->withInput();
            }
        }

        // محاسبه درصد در سرور
        $validated['percentage'] = ($validated['amount'] / $newCar->purchase_price) * 100;

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