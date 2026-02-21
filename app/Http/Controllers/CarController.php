<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Traits\JalaliDateTrait;

class CarController extends Controller
{
    use JalaliDateTrait;

    public function index()
    {
        $cars = Car::latest()->paginate(10);
        
        // تبدیل تاریخ خرید به شمسی برای نمایش
        foreach ($cars as $car) {
            $car->jalali_purchase_date = $this->convertToJalali($car->purchase_date);
        }
        
        return view('cars.index', compact('cars'));
    }

    public function create()
    {
        // تاریخ پیش‌فرض شمسی برای امروز
        $todayJalali = $this->nowJalali();
        return view('cars.create', compact('todayJalali'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1300|max:1405',
            'kilometers' => 'required|integer|min:0',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|string', // تاریخ به صورت شمسی دریافت می‌شود
        ]);

        // تبدیل تاریخ شمسی به میلادی برای ذخیره در دیتابیس
        $validated['purchase_date'] = $this->convertToGregorian($request->purchase_date);

        Car::create($validated);

        return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت اضافه شد.');
    }

    public function show(Car $car)
    {
        $car->load('investments.investor');
        // تبدیل تاریخ به شمسی برای نمایش
        $car->jalali_purchase_date = $this->convertToJalali($car->purchase_date);
        
        return view('cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        // تبدیل تاریخ ذخیره شده به شمسی برای نمایش در فرم ویرایش
        $car->jalali_purchase_date = $this->convertToJalali($car->purchase_date);
        
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1300|max:1405',
            'kilometers' => 'required|integer|min:0',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|string',
            'status' => 'required|in:available,sold,reserved',
        ]);

        // تبدیل تاریخ شمسی به میلادی برای ذخیره در دیتابیس
        $validated['purchase_date'] = $this->convertToGregorian($request->purchase_date);

        $car->update($validated);

        return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت ویرایش شد.');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت حذف شد.');
    }
}