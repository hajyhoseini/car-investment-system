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
        
        foreach ($cars as $car) {
$car->jalali_purchase_date = jalali_date($car->purchase_date);
        }
        
        return view('cars.index', compact('cars'));
    }

    public function create()
    {
$todayJalali = now_jalali('Y/m/d');
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
        'purchase_date' => 'required|string',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // تبدیل تاریخ شمسی به میلادی با استفاده از تابع کمکی
    $gregorianDate = jalali_to_gregorian($request->purchase_date);
    
    // اضافه کردن زمان فعلی به تاریخ
    $validated['purchase_date'] = $gregorianDate . ' ' . now()->format('H:i:s');

    // ایجاد خودرو
    $car = Car::create($validated);

    // آپلود تصاویر اگر وجود داشته باشند
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // ذخیره تصویر اصلی
            $path = $image->storeAs('cars/' . $car->id, $filename, 'public');
            
            // ذخیره در دیتابیس
            $car->images()->create([
                'image_path' => 'cars/' . $car->id . '/' . $filename,
                'is_primary' => ($index === 0),
                'sort_order' => $index
            ]);
        }
    }

    return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت اضافه شد.');
}
    public function show(Car $car)
    {
        $car->load('investments.investor');
        // تبدیل تاریخ به شمسی برای نمایش
// خط ۶۹ رو به این شکل تغییر بده:
$car->jalali_purchase_date = jalali_date($car->purchase_date);        
        return view('cars.show', compact('car'));
    }


public function edit(Car $car)
{
    // تبدیل تاریخ ذخیره شده به شمسی برای نمایش در فرم ویرایش
    $car->jalali_purchase_date = jalali_date($car->purchase_date);
    
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

    // تبدیل تاریخ شمسی به میلادی
    $validated['purchase_date'] = jalali_to_gregorian($request->purchase_date);

    $car->update($validated);

    return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت ویرایش شد.');
}

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت حذف شد.');
    }
}