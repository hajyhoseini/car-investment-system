<?php
// app/Http/Controllers/PriceController.php

namespace App\Http\Controllers;

use App\Services\PriceService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PriceController extends Controller implements HasMiddleware
{
    protected $priceService;

    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    /**
     * تعریف middlewareها (متد استاتیک برای لاراول ۱۲)
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:view prices', only: ['index', 'getPrices']),
            new Middleware('can:update prices', only: ['update']),
        ];
    }

    /**
     * نمایش صفحه قیمت‌های لحظه‌ای
     */
    public function index()
    {
        $prices = $this->priceService->getPrices();
        return view('prices.index', compact('prices'));
    }

    /**
     * آپدیت دستی همه قیمت‌ها
     */
    public function update(Request $request)
    {
        try {
            $this->priceService->updateAllAssets();
            
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'قیمت‌ها با موفقیت به‌روزرسانی شدند.']);
            }
            
            return redirect()->back()->with('success', 'قیمت‌ها با موفقیت به‌روزرسانی شدند.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'خطا در به‌روزرسانی قیمت‌ها: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'خطا در به‌روزرسانی قیمت‌ها: ' . $e->getMessage());
        }
    }

    /**
     * دریافت قیمت‌ها به صورت JSON (برای AJAX)
     */
    public function getPrices()
    {
        return response()->json($this->priceService->getPrices());
    }
}