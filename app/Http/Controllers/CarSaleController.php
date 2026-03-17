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
    public function index()
    {
        $sales = CarSale::with(['car', 'person'])->latest()->paginate(10);
        return view('car-sales.index', compact('sales'));
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