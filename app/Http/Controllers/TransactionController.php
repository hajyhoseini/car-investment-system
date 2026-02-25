<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Person;
use App\Models\PaymentMethod;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TransactionController extends Controller implements HasMiddleware
{
    /**
     * تعریف middlewareها
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            // می‌تونی middlewareهای دیگه هم اضافه کنی
            // new Middleware('can:view transactions')->only(['index', 'show']),
            // new Middleware('can:create transactions')->only(['create', 'store']),
            // new Middleware('can:edit transactions')->only(['edit', 'update']),
            // new Middleware('can:delete transactions')->only(['destroy']),
        ];
    }

    /**
     * نمایش لیست تراکنش‌ها
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['fromAccount', 'toAccount', 'person', 'paymentMethod'])
            ->latest('transaction_date');

        // فیلتر بر اساس تاریخ
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        // فیلتر بر اساس نوع
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // فیلتر بر اساس حساب
        if ($request->filled('account_id')) {
            $query->where(function($q) use ($request) {
                $q->where('from_account_id', $request->account_id)
                  ->orWhere('to_account_id', $request->account_id);
            });
        }

        // فیلتر بر اساس شخص
        if ($request->filled('person_id')) {
            $query->where('person_id', $request->person_id);
        }

        // فیلتر بر اساس وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(20);
        
        // آمار امروز
        $today = now()->toDateString();
        $todayIncome = Transaction::whereDate('transaction_date', $today)
            ->where('type', 'income')
            ->where('status', 'completed')
            ->sum('amount');
        $todayExpense = Transaction::whereDate('transaction_date', $today)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->sum('amount');

        // جمع کل دریافتی و پرداختی
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

    /**
     * فرم ایجاد تراکنش جدید
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'income');
        $accounts = Account::where('is_active', true)->get();
        $people = Person::orderBy('full_name')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $assets = Asset::all();

        return view('transactions.create', compact('type', 'accounts', 'people', 'paymentMethods', 'assets'));
    }

    /**
     * ذخیره تراکنش جدید
     */
    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1000',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ];

        // قوانین شرطی بر اساس نوع تراکنش
        if ($request->type === 'income') {
            $rules['to_account_id'] = 'required|exists:accounts,id';
        } else {
            $rules['from_account_id'] = 'required|exists:accounts,id';
        }

        // فیلدهای اختیاری
        $rules['person_id'] = 'nullable|exists:people,id';
        $rules['payment_method_id'] = 'nullable|exists:payment_methods,id';
        $rules['check_number'] = 'nullable|string|max:50';
        $rules['check_date'] = 'nullable|date';
        $rules['asset_id'] = 'nullable|exists:assets,id';
        $rules['asset_type'] = 'nullable|in:car,gold,dollar,other';
        $rules['car_sale_id'] = 'nullable|exists:car_sales,id';
        $rules['investment_id'] = 'nullable|exists:investments,id';

        $validated = $request->validate($rules);
        $validated['created_by'] = auth()->id();

        DB::transaction(function () use ($validated) {
            $transaction = Transaction::create($validated);
            
            // اگر تراکنش کامل شده، موجودی‌ها رو آپدیت کن
            if ($transaction->status === 'completed') {
                $transaction->updateBalances();
            }
        });

        $message = $request->type === 'income' ? 'دریافت' : 'پرداخت';
        return redirect()->route('transactions.index')
            ->with('success', "{$message} با موفقیت ثبت شد.");
    }

    /**
     * نمایش جزئیات تراکنش
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['fromAccount', 'toAccount', 'person', 'paymentMethod', 'asset', 'creator']);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * فرم ویرایش تراکنش
     */
    public function edit(Transaction $transaction)
    {
        $accounts = Account::where('is_active', true)->get();
        $people = Person::orderBy('full_name')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $assets = Asset::all();

        return view('transactions.edit', compact('transaction', 'accounts', 'people', 'paymentMethods', 'assets'));
    }

    /**
     * بروزرسانی تراکنش
     */
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
            $rules['to_account_id'] = 'required|exists:accounts,id';
        } else {
            $rules['from_account_id'] = 'required|exists:accounts,id';
        }

        $rules['person_id'] = 'nullable|exists:people,id';
        $rules['payment_method_id'] = 'nullable|exists:payment_methods,id';
        $rules['check_number'] = 'nullable|string|max:50';
        $rules['check_date'] = 'nullable|date';
        $rules['asset_id'] = 'nullable|exists:assets,id';
        $rules['asset_type'] = 'nullable|in:car,gold,dollar,other';

        $validated = $request->validate($rules);

        DB::transaction(function () use ($transaction, $validated) {
            // برگرداندن وضعیت قبلی
            if ($transaction->status === 'completed') {
                $transaction->revertBalances();
            }

            $transaction->update($validated);

            // اعمال وضعیت جدید
            if ($transaction->status === 'completed') {
                $transaction->updateBalances();
            }
        });

        return redirect()->route('transactions.index')
            ->with('success', 'تراکنش با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف تراکنش
     */
    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            // برگرداندن موجودی‌ها قبل از حذف
            if ($transaction->status === 'completed') {
                $transaction->revertBalances();
            }
            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'تراکنش با موفقیت حذف شد.');
    }

    /**
     * گزارش روزانه
     */
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        
        $transactions = Transaction::whereDate('transaction_date', $date)
            ->with(['fromAccount', 'toAccount', 'person'])
            ->orderBy('created_at')
            ->get();

        $income = $transactions->where('type', 'income')->where('status', 'completed')->sum('amount');
        $expense = $transactions->where('type', 'expense')->where('status', 'completed')->sum('amount');
        $balance = $income - $expense;

        return view('transactions.daily', compact('date', 'transactions', 'income', 'expense', 'balance'));
    }
}