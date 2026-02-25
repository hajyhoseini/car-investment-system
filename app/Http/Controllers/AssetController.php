<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssetController extends Controller implements HasMiddleware
{
    /**
     * تعریف middlewareها
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * نمایش لیست دارایی‌ها
     */
    public function index()
    {
        $assets = Asset::latest()->get();
        $totalValue = $assets->sum(function($asset) {
            return $asset->value ?? $asset->amount;
        });
        
        // محاسبه آمار امروز
        $today = now()->toDateString();
        $todayIncome = Transaction::whereDate('transaction_date', $today)
            ->where('type', 'income')
            ->where('status', 'completed')
            ->sum('amount');
            
        $todayExpense = Transaction::whereDate('transaction_date', $today)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->sum('amount');
        
        return view('assets.index', compact(
            'assets', 
            'totalValue',
            'todayIncome',
            'todayExpense'
        ));
    }

    /**
     * فرم ایجاد دارایی جدید
     */
    public function create()
    {
        return view('assets.create');
    }

    /**
     * ذخیره دارایی جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:bank,dollar,gold',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // برای حساب بانکی، value رو برابر amount قرار می‌دیم
        if ($validated['type'] === 'bank') {
            $validated['value'] = $validated['amount'];
        }

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'دارایی با موفقیت اضافه شد.');
    }

    /**
     * نمایش جزئیات دارایی
     */
    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    /**
     * فرم ویرایش دارایی
     */
    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    /**
     * بروزرسانی دارایی
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'type' => 'required|in:bank,dollar,gold',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validated['type'] === 'bank') {
            $validated['value'] = $validated['amount'];
        }

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'دارایی با موفقیت ویرایش شد.');
    }

    /**
     * حذف دارایی
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'دارایی با موفقیت حذف شد.');
    }
}