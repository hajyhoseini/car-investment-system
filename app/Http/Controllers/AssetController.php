<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Transaction;
use App\Services\PriceService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssetController extends Controller implements HasMiddleware
{
    protected $priceService;

    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

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
        
        // دریافت قیمت‌های لحظه‌ای
        $livePrices = $this->priceService->getPrices();
        
        // محاسبه ارزش کل دارایی‌ها با قیمت‌های لحظه‌ای
        $totalValue = 0;
        foreach ($assets as $asset) {
            if ($asset->type === 'dollar') {
                $asset->live_value = $asset->amount * ($livePrices['currency']['usd'] ?? 600000);
            } elseif ($asset->type === 'gold') {
                $asset->live_value = $this->calculateGoldValue($asset, $livePrices);
            } else {
                $asset->live_value = $asset->value ?? $asset->amount;
            }
            $totalValue += $asset->live_value;
        }
        
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
        
        // تفکیک دارایی‌ها بر اساس نوع
        $bankAccounts = $assets->where('type', 'bank');
        $dollarAssets = $assets->where('type', 'dollar');
        $goldAssets = $assets->where('type', 'gold');
        
        // محاسبه موجودی واقعی حساب‌های بانکی
        $totalBankBalance = 0;
        $bankAccountsWithBalance = $bankAccounts->map(function($account) use (&$totalBankBalance) {
            // بارگذاری تراکنش‌ها برای نمایش
            $account->load(['incomingTransactions' => function($query) {
                $query->where('status', 'completed');
            }, 'outgoingTransactions' => function($query) {
                $query->where('status', 'completed');
            }]);
            
            $account->current_balance = $account->amount;
            $totalBankBalance += $account->amount;
            
            return $account;
        });
        
        return view('assets.index', compact(
            'bankAccountsWithBalance',
            'dollarAssets',
            'goldAssets',
            'totalValue',
            'todayIncome',
            'todayExpense',
            'totalBankBalance',
            'livePrices'
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
        
        // برای دلار و طلا، value رو با قیمت لحظه‌ای محاسبه کن
        if ($validated['type'] === 'dollar') {
            $livePrices = $this->priceService->getPrices();
            $validated['value'] = $validated['amount'] * ($livePrices['currency']['usd'] ?? 600000);
        }
        
        if ($validated['type'] === 'gold') {
            $livePrices = $this->priceService->getPrices();
            $validated['value'] = $this->calculateGoldValueFromName(
                $validated['name'], 
                $validated['amount'], 
                $livePrices
            );
        }

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'دارایی با موفقیت اضافه شد.');
    }

    /**
     * نمایش جزئیات دارایی
     */
    public function show(Asset $asset)
    {
        // دریافت قیمت لحظه‌ای برای نمایش
        $livePrices = $this->priceService->getPrices();
        
        if ($asset->type === 'dollar') {
            $asset->live_value = $asset->amount * ($livePrices['currency']['usd'] ?? 600000);
            $asset->live_price = $livePrices['currency']['usd'] ?? 600000;
        } elseif ($asset->type === 'gold') {
            $asset->live_value = $this->calculateGoldValue($asset, $livePrices);
            $asset->live_price = $livePrices['gold']['geram18'] ?? 3500000;
        }
        
        return view('assets.show', compact('asset', 'livePrices'));
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
        
        // برای دلار و طلا، value رو با قیمت لحظه‌ای آپدیت کن
        if ($validated['type'] === 'dollar') {
            $livePrices = $this->priceService->getPrices();
            $validated['value'] = $validated['amount'] * ($livePrices['currency']['usd'] ?? 600000);
        }
        
        if ($validated['type'] === 'gold') {
            $livePrices = $this->priceService->getPrices();
            $validated['value'] = $this->calculateGoldValueFromName(
                $validated['name'], 
                $validated['amount'], 
                $livePrices
            );
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

    /**
     * آپدیت دستی همه دارایی‌ها
     */
    public function updatePrices()
    {
        $this->priceService->updateAllAssets();
        return redirect()->route('assets.index')->with('success', 'قیمت‌ها با موفقیت به‌روزرسانی شدند.');
    }

    /**
     * دریافت قیمت‌ها به صورت JSON
     */
    public function getPrices()
    {
        return response()->json($this->priceService->getPrices());
    }

    /**
     * محاسبه ارزش طلا بر اساس نام
     */
    private function calculateGoldValue(Asset $asset, array $livePrices): float
    {
        return $this->calculateGoldValueFromName($asset->name, $asset->amount, $livePrices);
    }

    /**
     * محاسبه ارزش طلا از روی نام و مقدار
     */
    private function calculateGoldValueFromName(string $name, float $amount, array $livePrices): float
    {
        if (str_contains($name, 'سکه تمام')) {
            return $amount * ($livePrices['coin']['sekeb'] ?? 280000000);
        }
        if (str_contains($name, 'نیم سکه')) {
            return $amount * ($livePrices['coin']['nim'] ?? 140000000);
        }
        if (str_contains($name, 'ربع سکه')) {
            return $amount * ($livePrices['coin']['rob'] ?? 75000000);
        }
        if (str_contains($name, 'طلای آب شده') || str_contains($name, 'طلای')) {
            return $amount * ($livePrices['gold']['geram18'] ?? 3500000);
        }
        
        // پیش‌فرض: هر گرم طلا
        return $amount * ($livePrices['gold']['geram18'] ?? 3500000);
    }
}