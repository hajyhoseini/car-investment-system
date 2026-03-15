<?php
// app/Http/Controllers/CarSaleController.php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarSale;
use App\Models\Investment;
use Illuminate\Http\Request;

class CarSaleController extends Controller
{
    public function index()
    {
        $sales = CarSale::with('car')->latest()->paginate(10);
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
        
        return view('car-sales.create', compact('car'));
    }

    public function store(Request $request, Car $car)
    {
        $validated = $request->validate([
            'selling_price' => 'required|numeric|min:' . $car->purchase_price,
            'sale_date' => 'required|date',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
        ]);

        // محاسبه سود کل
        $totalProfit = $validated['selling_price'] - $car->purchase_price;

        // ایجاد رکورد فروش
        $sale = CarSale::create([
            'car_id' => $car->id,
            'selling_price' => $validated['selling_price'],
            'total_profit' => $totalProfit,
            'sale_date' => $validated['sale_date'],
            'buyer_name' => $validated['buyer_name'],
            'buyer_phone' => $validated['buyer_phone'],
        ]);

        // به‌روزرسانی وضعیت خودرو
        $car->update(['status' => 'sold']);

        return redirect()->route('car-sales.profits', $sale)
            ->with('success', 'فروش با موفقیت ثبت شد. گزارش سود سرمایه‌گذاران:');
    }

    public function show(CarSale $carSale)
    {
        $carSale->load('car.investments.investor');
        return view('car-sales.show', compact('carSale'));
    }

    /**
     * نمایش فرم ویرایش فروش
     */
    public function edit(CarSale $carSale)
    {
        $carSale->load('car');
        return view('car-sales.edit', compact('carSale'));
    }

    /**
     * بروزرسانی اطلاعات فروش
     */
    public function update(Request $request, CarSale $carSale)
    {
        $validated = $request->validate([
            'selling_price' => 'required|numeric|min:' . $carSale->car->purchase_price,
            'sale_date' => 'required|date',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
        ]);

        // محاسبه مجدد سود کل
        $totalProfit = $validated['selling_price'] - $carSale->car->purchase_price;

        // بروزرسانی فروش
        $carSale->update([
            'selling_price' => $validated['selling_price'],
            'total_profit' => $totalProfit,
            'sale_date' => $validated['sale_date'],
            'buyer_name' => $validated['buyer_name'],
            'buyer_phone' => $validated['buyer_phone'],
        ]);

        return redirect()->route('car-sales.show', $carSale)
            ->with('success', 'اطلاعات فروش با موفقیت ویرایش شد.');
    }

    public function investorProfits(CarSale $carSale)
    {
        $carSale->load('car.investments.investor');
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