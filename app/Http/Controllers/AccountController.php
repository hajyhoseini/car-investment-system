<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AccountController extends Controller implements HasMiddleware
{
    /**
     * تعریف middlewareها به روش صحیح در لاراول ۱۲
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:view accounts', only: ['index', 'show']),
            new Middleware('can:create accounts', only: ['create', 'store']),
            new Middleware('can:edit accounts', only: ['edit', 'update']),
            new Middleware('can:delete accounts', only: ['destroy']),
        ];
    }

    /**
     * نمایش لیست حساب‌ها
     */
    public function index()
    {
        $accounts = Account::latest()->paginate(15);
        $totalBalance = Account::sum('balance');
        
        return view('accounts.index', compact('accounts', 'totalBalance'));
    }

    /**
     * فرم ایجاد حساب جدید
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * ذخیره حساب جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,cash,wallet',
            'bank_name' => 'nullable|required_if:type,bank|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'card_number' => 'nullable|string|max:20',
            'sheba_number' => 'nullable|string|max:30',
            'balance' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Account::create($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'حساب با موفقیت ایجاد شد.');
    }

    /**
     * نمایش جزئیات حساب
     */
    public function show(Account $account)
    {
        $account->load(['outgoingTransactions', 'incomingTransactions']);
        
        $outgoingTotal = $account->outgoingTransactions()
            ->where('status', 'completed')
            ->sum('amount');
        $incomingTotal = $account->incomingTransactions()
            ->where('status', 'completed')
            ->sum('amount');
        
        return view('accounts.show', compact('account', 'outgoingTotal', 'incomingTotal'));
    }

    /**
     * فرم ویرایش حساب
     */
    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    /**
     * بروزرسانی حساب
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,cash,wallet',
            'bank_name' => 'nullable|required_if:type,bank|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'card_number' => 'nullable|string|max:20',
            'sheba_number' => 'nullable|string|max:30',
            'balance' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $account->update($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'حساب با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف حساب
     */
    public function destroy(Account $account)
    {
        // بررسی وجود تراکنش مرتبط
        if ($account->outgoingTransactions()->count() > 0 || $account->incomingTransactions()->count() > 0) {
            return redirect()->route('accounts.index')
                ->with('error', 'این حساب دارای تراکنش است و قابل حذف نمی‌باشد.');
        }

        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'حساب با موفقیت حذف شد.');
    }
}