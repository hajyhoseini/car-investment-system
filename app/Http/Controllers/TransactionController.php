<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Asset;
use App\Models\Person;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TransactionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index(Request $request)
    {
        $query = Transaction::with(['fromAsset', 'toAsset', 'person', 'paymentMethod'])
            ->latest('transaction_date');

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('account_id')) {
            $query->where(function($q) use ($request) {
                $q->where('from_asset_id', $request->account_id)
                  ->orWhere('to_asset_id', $request->account_id);
            });
        }

        $transactions = $query->paginate(20);
        
        $today = now()->toDateString();
        $todayIncome = Transaction::whereDate('transaction_date', $today)
            ->where('type', 'income')
            ->where('status', 'completed')
            ->sum('amount');
        $todayExpense = Transaction::whereDate('transaction_date', $today)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->sum('amount');

        $totalIncome = Transaction::where('type', 'income')->where('status', 'completed')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->where('status', 'completed')->sum('amount');

        return view('transactions.index', compact(
            'transactions', 
            'todayIncome', 
            'todayExpense',
            'totalIncome',
            'totalExpense'
        ));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'income');
        
        // دریافت حساب‌های بانکی فعال با محاسبه موجودی واقعی
        $accounts = Asset::where('type', 'bank')
            ->where('is_active', true)
            ->get()
            ->map(function($account) {
                $account->current_balance = $account->current_balance;
                return $account;
            });
        
        $people = Person::orderBy('full_name')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $assets = Asset::whereIn('type', ['dollar', 'gold'])->get();

        return view('transactions.create', compact('type', 'accounts', 'people', 'paymentMethods', 'assets'));
    }

public function store(Request $request)
{
    
    \Log::info('Transaction store called', [
        'type' => $request->type,
        'amount' => $request->amount,
        'status' => $request->status
    ]);

    $rules = [
        'type' => 'required|in:income,expense',
        'amount' => 'required|numeric|min:1000',
        'transaction_date' => 'required|date',
        'description' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'status' => 'required|in:pending,completed,cancelled',
    ];

    if ($request->type === 'income') {
        $rules['to_account_id'] = 'required|exists:assets,id';
    } else {
        $rules['from_account_id'] = 'required|exists:assets,id';
    }

    $validated = $request->validate($rules);
    $validated['created_by'] = auth()->id();

    if (isset($validated['to_account_id'])) {
        $validated['to_asset_id'] = $validated['to_account_id'];
        unset($validated['to_account_id']);
    }
    
    if (isset($validated['from_account_id'])) {
        $validated['from_asset_id'] = $validated['from_account_id'];
        unset($validated['from_account_id']);
    }

    // بررسی موجودی برای پرداخت
    if ($request->type === 'expense' && $request->status === 'completed') {
        $asset = Asset::find($validated['from_asset_id']);
        
        // محاسبه موجودی واقعی با استفاده از متد مدل
        $currentBalance = $asset->current_balance;
        
        if ($currentBalance < $request->amount) {
            return back()->withErrors([
                'amount' => 'موجودی حساب کافی نیست. موجودی فعلی: ' . number_format($currentBalance) . ' ریال'
            ])->withInput();
        }
    }

    DB::beginTransaction();
    
    try {
        // ایجاد تراکنش
        $transaction = Transaction::create($validated);
        
        // فقط در صورتی که وضعیت completed باشه، موجودی رو آپدیت می‌کنیم
        // و فقط فیلد amount رو تغییر می‌دیم (خود مدل balance رو محاسبه می‌کنه)
        if ($transaction->status === 'completed') {
            if ($transaction->type === 'income' && $transaction->to_asset_id) {
                // دریافت: به amount حساب مقصد اضافه کن
                $targetAsset = Asset::find($transaction->to_asset_id);
                $targetAsset->amount += $transaction->amount;
                $targetAsset->save();
                
            } elseif ($transaction->type === 'expense' && $transaction->from_asset_id) {
                // پرداخت: از amount حساب مبدأ کم کن
                $sourceAsset = Asset::find($transaction->from_asset_id);
                $sourceAsset->amount -= $transaction->amount;
                $sourceAsset->save();
            }
        }
        
        DB::commit();
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'خطا در ثبت تراکنش: ' . $e->getMessage())->withInput();
    }

    $message = $request->type === 'income' ? 'دریافت' : 'پرداخت';
    return redirect()->route('transactions.index')
        ->with('success', "{$message} با موفقیت ثبت شد.");
}

    public function show(Transaction $transaction)
    {
        $transaction->load(['fromAsset', 'toAsset', 'person', 'paymentMethod', 'asset', 'creator']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $accounts = Asset::where('type', 'bank')->where('is_active', true)->get()
            ->map(function($account) {
                $account->current_balance = $account->current_balance;
                return $account;
            });
        
        $people = Person::orderBy('full_name')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $assets = Asset::whereIn('type', ['dollar', 'gold'])->get();

        return view('transactions.edit', compact('transaction', 'accounts', 'people', 'paymentMethods', 'assets'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $rules = [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1000',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ];

        if ($request->type === 'income') {
            $rules['to_account_id'] = 'required|exists:assets,id';
        } else {
            $rules['from_account_id'] = 'required|exists:assets,id';
        }

        $rules['person_id'] = 'nullable|exists:people,id';
        $rules['payment_method_id'] = 'nullable|exists:payment_methods,id';
        $rules['check_number'] = 'nullable|string|max:50';
        $rules['check_date'] = 'nullable|date';
        $rules['asset_id'] = 'nullable|exists:assets,id';

        $validated = $request->validate($rules);

        // تبدیل account_id به asset_id
        if (isset($validated['to_account_id'])) {
            $validated['to_asset_id'] = $validated['to_account_id'];
            unset($validated['to_account_id']);
        }
        
        if (isset($validated['from_account_id'])) {
            $validated['from_asset_id'] = $validated['from_account_id'];
            unset($validated['from_account_id']);
        }

        DB::transaction(function () use ($transaction, $validated) {
            if ($transaction->status === 'completed') {
                $transaction->revertBalances();
            }

            $transaction->update($validated);

            if ($transaction->status === 'completed') {
                $transaction->updateBalances();
            }
        });

        return redirect()->route('transactions.index')
            ->with('success', 'تراکنش با موفقیت بروزرسانی شد.');
    }

    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            if ($transaction->status === 'completed') {
                $transaction->revertBalances();
            }
            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'تراکنش با موفقیت حذف شد.');
    }
}