<?php
// app/Http/Controllers/CarSaleController.php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Person;
use App\Models\CarSale;
use App\Models\Investment;
use Illuminate\Http\Request;

class CarSaleController extends Controller
{
public function index(Request $request) // اضافه کردن Request
{
    $query = CarSale::with(['car', 'person']);

    // فیلتر جستجو بر اساس نام خودرو یا خریدار
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('car', function($car) use ($search) {
                $car->where('title', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            })->orWhereHas('person', function($person) use ($search) {
                $person->where('full_name', 'like', "%{$search}%")
                    ->orWhere('national_code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })->orWhere('buyer_name', 'like', "%{$search}%");
        });
    }

    // فیلتر بر اساس حداقل قیمت فروش
    if ($request->filled('min_price')) {
        $minPrice = str_replace(',', '', $request->min_price);
        $query->where('selling_price', '>=', $minPrice);
    }

    // فیلتر بر اساس حداکثر قیمت فروش
    if ($request->filled('max_price')) {
        $maxPrice = str_replace(',', '', $request->max_price);
        $query->where('selling_price', '<=', $maxPrice);
    }

    // فیلتر بر اساس خودرو
    if ($request->filled('car_id')) {
        $query->where('car_id', $request->car_id);
    }

    // فیلتر بر اساس خریدار
    if ($request->filled('buyer_person_id')) {
        $query->where('person_id', $request->buyer_person_id);
    }

    // فیلتر بر اساس بازه تاریخ
    if ($request->filled('start_date')) {
        $startDate = jalali_to_gregorian($request->start_date);
        $query->whereDate('sale_date', '>=', $startDate);
    }

    if ($request->filled('end_date')) {
        $endDate = jalali_to_gregorian($request->end_date);
        $query->whereDate('sale_date', '<=', $endDate);
    }

    $sales = $query->latest()->paginate(20);

    // محاسبه آمار با در نظر گرفتن فیلترها
    $totalCount = $sales->total();
    $totalAmount = $sales->sum('selling_price');
    $totalProfit = $sales->sum('total_profit');
    $averageProfit = $sales->avg('total_profit') ?? 0;

    // تهیه لیست خودروها برای کامپوننت فیلتر
    $cars = Car::orderBy('title')->get();
    $carOptions = $cars->map(function($car) {
        return [
            'id' => $car->id,
            'text' => $car->title . ' - ' . $car->brand . ' ' . $car->model,
            'subtext' => number_format($car->purchase_price) . ' ریال'
        ];
    })->prepend(['id' => '', 'text' => 'همه خودروها'])->values()->toArray();

    // تهیه لیست خریداران برای کامپوننت فیلتر
    $buyers = Person::orderBy('full_name')->get();
    $buyerOptions = $buyers->map(function($person) {
        $text = $person->full_name;
        if ($person->national_code) {
            $text .= ' (کد ملی: ' . $person->national_code . ')';
        }
        return [
            'id' => $person->id,
            'text' => $text,
            'subtext' => $person->phone ?? ''
        ];
    })->prepend(['id' => '', 'text' => 'همه خریداران'])->values()->toArray();

    return view('car-sales.index', compact(
        'sales',
        'totalCount',
        'totalAmount',
        'totalProfit',
        'averageProfit',
        'carOptions',
        'buyerOptions'
    ));
}

    public function create(Car $car)
    {
        // بررسی اینکه خودرو موجود باشد
        if ($car->status !== 'available') {
            return redirect()->route('cars.index')->with('error', 'این خودرو قبلاً فروخته شده یا رزرو است.');
        }
        
        // بارگذاری سرمایه‌گذاری‌ها
        $car->load('investments.investor');
        
        // دریافت لیست اشخاص برای انتخاب خریدار
        $buyers = Person::orderBy('full_name')->get();
        
        // فرمت کردن اشخاص برای کامپوننت searchable-select
        $formattedBuyers = $buyers->map(function($person) {
            return [
                'id' => $person->id,
                'text' => $person->display_name . ($person->national_code ? ' (کد ملی: ' . $person->national_code . ')' : ''),
                'data' => [
                    'national-code' => $person->national_code,
                    'phone' => $person->phone,
                    'type-label' => $person->type_label,
                    'email' => $person->email,
                    'address' => $person->address
                ]
            ];
        })->toArray();
        
        return view('car-sales.create', compact('car', 'formattedBuyers'));
    }

    public function store(Request $request, Car $car)
    {
        // اعتبارسنجی - فقط person_id نیاز داریم
        $validated = $request->validate([
            'selling_price' => 'required|numeric|min:' . $car->purchase_price,
            'sale_date' => 'required|date',
            'person_id' => 'required|exists:people,id', // الان required شده
        ]);

        // محاسبه سود کل
        $totalProfit = $validated['selling_price'] - $car->purchase_price;

        // دریافت اطلاعات شخص برای مقداردهی buyer_name و buyer_phone (برای سازگاری با دیتابیس)
        $person = Person::find($validated['person_id']);

        // ایجاد رکورد فروش
        $sale = CarSale::create([
            'car_id' => $car->id,
            'person_id' => $validated['person_id'],
            'selling_price' => $validated['selling_price'],
            'total_profit' => $totalProfit,
            'sale_date' => $validated['sale_date'],
            'buyer_name' => $person->full_name, // مقداردهی از شخص انتخاب شده
            'buyer_phone' => $person->phone,     // مقداردهی از شخص انتخاب شده
        ]);

        // به‌روزرسانی وضعیت خودرو
        $car->update(['status' => 'sold']);

        return redirect()->route('car-sales.profits', $sale)
            ->with('success', 'فروش با موفقیت ثبت شد. گزارش سود سرمایه‌گذاران:');
    }

    public function show(CarSale $carSale)
    {
        $carSale->load(['car.investments.investor', 'person']);
        return view('car-sales.show', compact('carSale'));
    }

    /**
     * نمایش فرم ویرایش فروش
     */
    public function edit(CarSale $carSale)
    {
        $carSale->load('car');
        
        // دریافت لیست اشخاص برای انتخاب خریدار
        $buyers = Person::orderBy('full_name')->get();
        
        // فرمت کردن اشخاص برای کامپوننت searchable-select
        $formattedBuyers = $buyers->map(function($person) {
            return [
                'id' => $person->id,
                'text' => $person->display_name . ($person->national_code ? ' (کد ملی: ' . $person->national_code . ')' : ''),
                'data' => [
                    'national-code' => $person->national_code,
                    'phone' => $person->phone,
                    'type-label' => $person->type_label,
                    'email' => $person->email,
                    'address' => $person->address
                ]
            ];
        })->toArray();
        
        return view('car-sales.edit', compact('carSale', 'formattedBuyers'));
    }

    /**
     * بروزرسانی اطلاعات فروش
     */
    public function update(Request $request, CarSale $carSale)
    {
        $validated = $request->validate([
            'selling_price' => 'required|numeric|min:' . $carSale->car->purchase_price,
            'sale_date' => 'required|date',
            'person_id' => 'required|exists:people,id',
        ]);

        // محاسبه مجدد سود کل
        $totalProfit = $validated['selling_price'] - $carSale->car->purchase_price;

        // دریافت اطلاعات شخص برای مقداردهی buyer_name و buyer_phone
        $person = Person::find($validated['person_id']);

        // بروزرسانی فروش
        $carSale->update([
            'person_id' => $validated['person_id'],
            'selling_price' => $validated['selling_price'],
            'total_profit' => $totalProfit,
            'sale_date' => $validated['sale_date'],
            'buyer_name' => $person->full_name,
            'buyer_phone' => $person->phone,
        ]);

        return redirect()->route('car-sales.show', $carSale)
            ->with('success', 'اطلاعات فروش با موفقیت ویرایش شد.');
    }

    public function investorProfits(CarSale $carSale)
    {
        $carSale->load(['car.investments.investor', 'person']);
        $profits = $carSale->calculateInvestorProfits();
        
        return view('car-sales.profits', compact('carSale', 'profits'));
    }

    public function destroy(CarSale $carSale)
    {
        // برگردوندن وضعیت خودرو به available
        $carSale->car->update(['status' => 'available']);
        
        $carSale->delete();

        return redirect()->route('car-sales.index')
            ->with('success', 'فروش با موفقیت حذف شد.');
    }
}