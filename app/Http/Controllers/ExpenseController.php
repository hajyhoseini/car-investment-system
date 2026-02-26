<?php
// app/Http/Controllers/ExpenseController.php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Car;
use App\Models\Asset;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ExpenseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * نمایش لیست هزینه‌ها
     */
    public function index(Request $request)
    {
        $query = Expense::with(['car', 'account', 'paymentMethod', 'creator'])
            ->latest('expense_date');

        // فیلتر بر اساس خودرو
        if ($request->filled('car_id')) {
            $query->where('car_id', $request->car_id);
        }

        // فیلتر بر اساس دسته‌بندی
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // فیلتر بر اساس بازه زمانی
        if ($request->filled('start_date')) {
            $query->whereDate('expense_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('expense_date', '<=', $request->end_date);
        }

        $expenses = $query->paginate(20);
        
        // آمار هزینه‌ها
        $totalExpenses = $expenses->sum('amount');
        $carExpenses = $expenses->whereNotNull('car_id')->sum('amount');
        $generalExpenses = $expenses->whereNull('car_id')->sum('amount');

        return view('expenses.index', compact(
            'expenses',
            'totalExpenses',
            'carExpenses',
            'generalExpenses'
        ));
    }

    /**
     * فرم ایجاد هزینه جدید
     */
    public function create()
    {
        $cars = Car::where('status', '!=', 'sold')->get();
        $accounts = Asset::where('type', 'bank')->where('is_active', true)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // دسته‌بندی‌های هزینه
        $categories = [
            'car_service' => 'خدمات خودرو',
            'car_repair' => 'تعمیرات خودرو',
            'car_wash' => 'کارواش',
            'fuel' => 'سوخت',
            'rent' => 'اجاره',
            'snapp' => 'اسنپ/تاکسی',
            'food' => 'غذا',
            'office' => 'لوازم اداری',
            'utility' => 'قبوض',
            'other' => 'سایر',
        ];

        return view('expenses.create', compact('cars', 'accounts', 'paymentMethods', 'categories'));
    }

    /**
     * ذخیره هزینه جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1000',
            'expense_date' => 'required|date',
            'category' => 'required|string',
            'car_id' => 'nullable|exists:cars,id',
            'account_id' => 'nullable|exists:assets,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string',
        ]);

        // آپلود تصویر رسید
        if ($request->hasFile('receipt_image')) {
            $path = $request->file('receipt_image')->store('expenses', 'public');
            $validated['receipt_image'] = $path;
        }

        $validated['created_by'] = auth()->id();

        // اگر حساب انتخاب شده، موجودی رو کم کن
        if (isset($validated['account_id'])) {
            $account = Asset::find($validated['account_id']);
            if ($account->amount < $validated['amount']) {
                return back()->withErrors([
                    'amount' => 'موجودی حساب کافی نیست. موجودی: ' . number_format($account->amount) . ' ریال'
                ])->withInput();
            }
            $account->decrement('amount', $validated['amount']);
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'هزینه با موفقیت ثبت شد.');
    }

    /**
     * نمایش جزئیات هزینه
     */
    public function show(Expense $expense)
    {
        $expense->load(['car', 'account', 'paymentMethod', 'creator']);
        return view('expenses.show', compact('expense'));
    }

    /**
     * فرم ویرایش هزینه
     */
    public function edit(Expense $expense)
    {
        $cars = Car::where('status', '!=', 'sold')->get();
        $accounts = Asset::where('type', 'bank')->where('is_active', true)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        $categories = [
            'car_service' => 'خدمات خودرو',
            'car_repair' => 'تعمیرات خودرو',
            'car_wash' => 'کارواش',
            'fuel' => 'سوخت',
            'rent' => 'اجاره',
            'snapp' => 'اسنپ/تاکسی',
            'food' => 'غذا',
            'office' => 'لوازم اداری',
            'utility' => 'قبوض',
            'other' => 'سایر',
        ];

        return view('expenses.edit', compact('expense', 'cars', 'accounts', 'paymentMethods', 'categories'));
    }

    /**
     * بروزرسانی هزینه
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1000',
            'expense_date' => 'required|date',
            'category' => 'required|string',
            'car_id' => 'nullable|exists:cars,id',
            'account_id' => 'nullable|exists:assets,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string',
        ]);

        // مدیریت موجودی حساب در صورت تغییر
        if ($expense->account_id != $validated['account_id'] || $expense->amount != $validated['amount']) {
            // برگردوندن پول به حساب قبلی
            if ($expense->account_id) {
                $expense->account->increment('amount', $expense->amount);
            }
            
            // کم کردن از حساب جدید
            if (isset($validated['account_id'])) {
                $account = Asset::find($validated['account_id']);
                if ($account->amount < $validated['amount']) {
                    return back()->withErrors([
                        'amount' => 'موجودی حساب کافی نیست.'
                    ])->withInput();
                }
                $account->decrement('amount', $validated['amount']);
            }
        }

        // آپلود تصویر جدید
        if ($request->hasFile('receipt_image')) {
            // حذف تصویر قبلی
            if ($expense->receipt_image) {
                Storage::disk('public')->delete($expense->receipt_image);
            }
            $path = $request->file('receipt_image')->store('expenses', 'public');
            $validated['receipt_image'] = $path;
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'هزینه با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف هزینه
     */
    public function destroy(Expense $expense)
    {
        // برگردوندن پول به حساب
        if ($expense->account_id) {
            $expense->account->increment('amount', $expense->amount);
        }

        // حذف تصویر
        if ($expense->receipt_image) {
            Storage::disk('public')->delete($expense->receipt_image);
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'هزینه با موفقیت حذف شد.');
    }
}