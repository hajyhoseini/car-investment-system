<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssetController extends Controller implements HasMiddleware
{
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
        // دریافت همه دارایی‌ها
        $assets = Asset::latest()->get();
        
        // دریافت آمار امروز
        $today = now()->toDateString();
        
        $todayIncome = Transaction::whereDate('transaction_date', $today)
            ->where('type', 'income')
            ->where('status', 'completed')
            ->sum('amount');
            
        $todayExpense = Transaction::whereDate('transaction_date', $today)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->sum('amount');
        
        // دریافت همه تراکنش‌های مرتبط با حساب‌های بانکی در یک کوئری
        $bankAccountIds = $assets->where('type', 'bank')->pluck('id');
        
        $transactions = collect();
        if ($bankAccountIds->isNotEmpty()) {
            $transactions = Transaction::where('status', 'completed')
                ->where(function($query) use ($bankAccountIds) {
                    $query->whereIn('from_asset_id', $bankAccountIds)
                          ->orWhereIn('to_asset_id', $bankAccountIds);
                })
                ->select('from_asset_id', 'to_asset_id', 'amount')
                ->get()
                ->groupBy(function($item) {
                    return $item->from_asset_id ?? $item->to_asset_id;
                });
        }
        
        // پردازش دارایی‌ها
        $totalValue = 0;
        $bankAccountsWithBalance = collect();
        $dollarAssets = collect();
        $goldAssets = collect();
        $otherAssets = collect();
        
        foreach ($assets as $asset) {
            // ارزش دارایی (بدون قیمت لحظه‌ای)
            $assetValue = $asset->value ?? $asset->amount;
            $totalValue += $assetValue;
            
            switch ($asset->type) {
                case 'bank':
                    // محاسبه موجودی جاری حساب بانکی
                    $accountTransactions = $transactions->get($asset->id, collect());
                    
                    $incoming = $accountTransactions
                        ->where('to_asset_id', $asset->id)
                        ->sum('amount');
                        
                    $outgoing = $accountTransactions
                        ->where('from_asset_id', $asset->id)
                        ->sum('amount');
                    
                    $asset->current_balance = $asset->amount + $incoming - $outgoing;
                    $bankAccountsWithBalance->push($asset);
                    break;
                    
                case 'dollar':
                    $dollarAssets->push($asset);
                    break;
                    
                case 'gold':
                    $goldAssets->push($asset);
                    break;
                    
                default:
                    $otherAssets->push($asset);
            }
        }
        
        // جمع کل موجودی حساب‌های بانکی
        $totalBankBalance = $bankAccountsWithBalance->sum('current_balance');
        
        return view('assets.index', compact(
            'bankAccountsWithBalance',
            'dollarAssets',
            'goldAssets',
            'otherAssets',
            'totalValue',
            'totalBankBalance',
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
            'type' => 'required|in:bank,dollar,gold,other',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'card_number' => 'nullable|string|max:20',
            'sheba_number' => 'nullable|string|max:30',
            'is_active' => 'boolean',
        ]);

        // تنظیم مقادیر پیش‌فرض
        $validated['is_active'] = $request->has('is_active');
        
        // برای حساب بانکی، value رو برابر amount قرار می‌دیم
        if ($validated['type'] === 'bank') {
            $validated['value'] = $validated['amount'];
        }
        
        // برای دلار و طلا، اگر value وارد نشده باشه، می‌تونی مقدار پیش‌فرض بدی یا null بذاری
        if (in_array($validated['type'], ['dollar', 'gold']) && empty($validated['value'])) {
            $validated['value'] = null; // یا یه مقدار پیش‌فرض
        }

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'دارایی با موفقیت اضافه شد.');
    }

    /**
     * نمایش جزئیات دارایی
     */
    public function show(Asset $asset)
    {
        // بارگذاری تراکنش‌های مرتبط
        $asset->load(['incomingTransactions', 'outgoingTransactions']);
        
        // محاسبه موجودی برای حساب بانکی
        if ($asset->type === 'bank') {
            $incoming = $asset->incomingTransactions()
                ->where('status', 'completed')
                ->sum('amount');
                
            $outgoing = $asset->outgoingTransactions()
                ->where('status', 'completed')
                ->sum('amount');
            
            $asset->current_balance = $asset->amount + $incoming - $outgoing;
        }
        
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
            'type' => 'required|in:bank,dollar,gold,other',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'card_number' => 'nullable|string|max:20',
            'sheba_number' => 'nullable|string|max:30',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

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
        // بررسی وجود تراکنش‌های مرتبط
        if ($asset->transactions()->exists()) {
            return back()->with('error', 'این دارایی دارای تراکنش است و قابل حذف نمی‌باشد.');
        }
        
        $asset->delete();
        
        return redirect()->route('assets.index')->with('success', 'دارایی با موفقیت حذف شد.');
    }
}