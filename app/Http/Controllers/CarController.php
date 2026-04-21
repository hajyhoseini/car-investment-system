<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Traits\JalaliDateTrait;

class CarController extends Controller
{
    use JalaliDateTrait;

public function index(Request $request)
{
    $query = Car::query();
    
    // فیلتر جستجو
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('model', 'like', "%{$search}%");
        });
    }
    
    // فیلتر وضعیت
    if ($request->filled('status') && $request->status != 'all') {
        $query->where('status', $request->status);
    }
    
    // فیلتر برند
    if ($request->filled('brand') && $request->brand != 'all') {
        $query->where('brand', $request->brand);
    }
    
    // فیلتر اولویت
    if ($request->filled('priority') && $request->priority != 'all') {
        $query->where('purchase_priority', $request->priority);
    }
    
    // فیلتر مالک
    if ($request->filled('owner') && $request->owner != 'all') {
        $query->where('owner_type', $request->owner);
    }
    
    // مرتب‌سازی
    $sort = $request->get('sort', 'created_at');
    $direction = $request->get('direction', 'desc');
    $query->orderBy($sort, $direction);
    
    $cars = $query->paginate(10);
    
    // دریافت برندهای یکتا برای فیلتر
    $brands = Car::select('brand')->distinct()->pluck('brand');
    
    foreach ($cars as $car) {
        $car->jalali_purchase_date = jalali_date($car->purchase_date);
        $car->jalali_inquiry_date = jalali_date($car->inquiry_date);
    }
    
    return view('cars.index', compact('cars', 'brands'));
}

    public function create()
    {
        $todayJalali = now_jalali('Y/m/d');
        return view('cars.create', compact('todayJalali'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // ========== صفحه 1 - اجباری ==========
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1300|max:1405',
            'kilometers' => 'required|integer|min:0',
            'color' => 'required|string|max:50',
            'inquiry_date' => 'required|string',
            'showroom_name' => 'nullable|string',
            
            // ========== صفحه 2 - اجباری ==========
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'document_status' => 'required|string',
            'storage_location' => 'required|string',
            'holding_price' => 'required|numeric|min:0',
            'purchase_priority' => 'required|string|in:high,medium,low',
            
            // ========== صفحه 3 - اختیاری (nullable) ==========
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|string',
            'body_condition' => 'nullable|string',
            'technical_condition' => 'nullable|string',
            'owner_type' => 'nullable|string',
            'market_price' => 'nullable|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'customer_type' => 'nullable|string',
            'description' => 'nullable|string',
            
            // ========== صفحه 4 - اختیاری (nullable) ==========
            'listing_url' => 'nullable|url|max:500',
            
            // تصاویر
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // تبدیل تاریخ شمسی به میلادی (فقط اگه مقدار داشته باشه)
        if (!empty($request->purchase_date)) {
            $validated['purchase_date'] = jalali_to_gregorian($request->purchase_date) . ' ' . now()->format('H:i:s');
        } else {
            $validated['purchase_date'] = null;
        }
        
        if (!empty($request->inquiry_date)) {
            $validated['inquiry_date'] = jalali_to_gregorian($request->inquiry_date);
        }
        
        // تنظیم status پیش‌فرض
        $validated['status'] = 'available';
        
        // حذف فیلدهای خالی از آرایه validated (برای اینکه دیتابیس null ذخیره کنه)
        $validated = array_filter($validated, function($value) {
            return !is_null($value) && $value !== '';
        });

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
        $car->jalali_purchase_date = jalali_date($car->purchase_date);
        $car->jalali_inquiry_date = jalali_date($car->inquiry_date);
        
        return view('cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        $car->jalali_purchase_date = jalali_date($car->purchase_date);
        $car->jalali_inquiry_date = jalali_date($car->inquiry_date);
        
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            // ========== صفحه 1 - اجباری ==========
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1300|max:1405',
            'kilometers' => 'required|integer|min:0',
            'color' => 'required|string|max:50',
            'inquiry_date' => 'required|string',
            'showroom_name' => 'nullable|string',
            
            // ========== صفحه 2 - اجباری ==========
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'document_status' => 'required|string',
            'storage_location' => 'required|string',
            'holding_price' => 'required|numeric|min:0',
            'purchase_priority' => 'required|string|in:high,medium,low',
            
            // ========== صفحه 3 - اختیاری ==========
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|string',
            'body_condition' => 'nullable|string',
            'technical_condition' => 'nullable|string',
            'owner_type' => 'nullable|string',
            'market_price' => 'nullable|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'customer_type' => 'nullable|string',
            'description' => 'nullable|string',
            
            // ========== صفحه 4 - اختیاری ==========
            'listing_url' => 'nullable|url|max:500',
            'status' => 'nullable|in:available,sold,reserved',
        ]);

        // تبدیل تاریخ‌های شمسی به میلادی (فقط اگه مقدار داشته باشه)
        if (!empty($request->purchase_date)) {
            $validated['purchase_date'] = jalali_to_gregorian($request->purchase_date) . ' ' . now()->format('H:i:s');
        } else {
            $validated['purchase_date'] = null;
        }
        
        if (!empty($request->inquiry_date)) {
            $validated['inquiry_date'] = jalali_to_gregorian($request->inquiry_date);
        }
        
        // تنظیم status پیش‌فرض اگه نیومده بود
        if (empty($validated['status'])) {
            $validated['status'] = 'available';
        }
        
        // حذف فیلدهای خالی از آرایه validated
        $validated = array_filter($validated, function($value) {
            return !is_null($value) && $value !== '';
        });

        $car->update($validated);

        return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت ویرایش شد.');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت حذف شد.');
    }
}