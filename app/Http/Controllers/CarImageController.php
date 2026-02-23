<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CarImageController extends Controller implements HasMiddleware
{
    /**
     * تعریف middlewareها
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('can:edit cars'),
        ];
    }

    /**
     * نمایش صفحه مدیریت تصاویر
     */
    public function index(Car $car)
    {
        $images = $car->images()->orderBy('sort_order')->get();
        return view('cars.images', compact('car', 'images'));
    }

    /**
     * آپلود تصاویر جدید
     */
/**
 * آپلود تصاویر جدید
 */
public function store(Request $request, Car $car)
{
    $request->validate([
        'images' => 'required|array|min:1|max:10',
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // ایجاد پوشه‌ها با دسترسی کامل
    try {
        Storage::disk('public')->makeDirectory('cars/' . $car->id);
        Storage::disk('public')->makeDirectory('cars/thumbnails/' . $car->id);
    } catch (\Exception $e) {
        // اگر پوشه‌ها وجود داشته باشن، ادامه بده
    }

    foreach ($request->file('images') as $index => $image) {
        // ایجاد نام یکتا برای فایل
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        
        // ذخیره تصویر اصلی
        $path = $image->storeAs('cars/' . $car->id, $filename, 'public');
        
        // ایجاد thumbnail
        try {
            // اطمینان از وجود پوشه thumbnail
            $thumbnailDir = storage_path('app/public/cars/thumbnails/' . $car->id);
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }
            
            // خواندن و تغییر اندازه تصویر
            $thumbnail = Image::read($image->getRealPath());
            $thumbnail->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            $thumbnailPath = $thumbnailDir . '/' . $filename;
            $thumbnail->save($thumbnailPath);
            
        } catch (\Exception $e) {
            // اگر thumbnail ساخته نشد، فقط لاگ کن و ادامه بده
            \Log::warning('خطا در ساخت thumbnail: ' . $e->getMessage() . ' برای فایل: ' . $filename);
        }

        // بررسی اینکه آیا این اولین تصویر و خودرو تصویری نداره
        $isPrimary = ($index === 0 && $car->images()->count() === 0);

        // ذخیره در دیتابیس
        CarImage::create([
            'car_id' => $car->id,
            'image_path' => 'cars/' . $car->id . '/' . $filename,
            'is_primary' => $isPrimary,
            'sort_order' => $car->images()->count()
        ]);
    }

    return redirect()->route('cars.images', $car)
        ->with('success', 'تصاویر با موفقیت آپلود شدند.');
}

    /**
     * تنظیم تصویر اصلی
     */
    public function setPrimary(Car $car, CarImage $image)
    {
        if ($image->car_id !== $car->id) {
            abort(403);
        }

        // حذف وضعیت اصلی از همه تصاویر
        $car->images()->update(['is_primary' => false]);

        // تنظیم تصویر جدید به عنوان اصلی
        $image->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'تصویر اصلی با موفقیت تغییر کرد.');
    }

    /**
     * حذف تصویر
     */
    public function destroy(Car $car, CarImage $image)
    {
        if ($image->car_id !== $car->id) {
            abort(403);
        }

        // حذف فایل‌ها
        Storage::disk('public')->delete($image->image_path);
        
        $thumbnailPath = str_replace('cars/', 'cars/thumbnails/', $image->image_path);
        Storage::disk('public')->delete($thumbnailPath);

        // اگر تصویر اصلی بود، اولین تصویر باقی‌مانده رو به عنوان اصلی انتخاب کن
        if ($image->is_primary) {
            $firstImage = $car->images()->where('id', '!=', $image->id)->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
            }
        }

        $image->delete();

        return redirect()->back()->with('success', 'تصویر با موفقیت حذف شد.');
    }

    /**
     * آپلود تصاویر جدید (برای استفاده در فرم ایجاد خودرو)
     */
    public function upload(Request $request, Car $car)
    {
        return $this->store($request, $car);
    }
}