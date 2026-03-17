<?php
// app/Http/Controllers/ExpenseController.php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Car;
use App\Models\Asset;
use App\Models\Person;
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
    $query = Expense::with(['car', 'account', 'paymentMethod', 'creator', 'person'])
        ->latest('expense_date');

    // فیلتر بر اساس خودرو
    if ($request->filled('car_id')) {
        $query->where('car_id', $request->car_id);
    }

    // فیلتر بر اساس شخص
    if ($request->filled('person_id')) {
        $query->where('person_id', $request->person_id);
    }

    // فیلتر بر اساس دسته‌بندی
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // فیلتر بر اساس بازه زمانی (تبدیل تاریخ شمسی به میلادی)
    if ($request->filled('start_date')) {
        $startDate = jalali_to_gregorian($request->start_date);
        $query->whereDate('expense_date', '>=', $startDate);
    }
    if ($request->filled('end_date')) {
        $endDate = jalali_to_gregorian($request->end_date);
        $query->whereDate('expense_date', '<=', $endDate);
    }

    $expenses = $query->paginate(20);
    
    // آمار هزینه‌ها
    $totalExpenses = $expenses->sum('amount');
    $carExpenses = $expenses->whereNotNull('car_id')->sum('amount');
    $personExpenses = $expenses->whereNotNull('person_id')->sum('amount');
    $generalExpenses = $expenses->whereNull('car_id')->whereNull('person_id')->sum('amount');

    // تهیه لیست اشخاص برای کامپوننت فیلتر
    $people = Person::orderBy('full_name')->get();
    $personOptions = $people->map(function($person) {
        $text = $person->full_name;
        if ($person->company_name) {
            $text .= ' (' . $person->company_name . ')';
        }
        return [
            'id' => $person->id,
            'text' => $text
        ];
    })->prepend(['id' => '', 'text' => 'همه اشخاص'])->values()->toArray();

    return view('expenses.index', compact(
        'expenses',
        'totalExpenses',
        'carExpenses',
        'personExpenses',
        'generalExpenses',
        'personOptions' // اضافه کردن این متغیر
    ));
}
    /**
     * فرم ایجاد هزینه جدید
     */
    public function create()
    {
        $cars = Car::where('status', '!=', 'sold')->get();
        $people = Person::orderBy('full_name')->get();
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

        // تبدیل به فرمت مناسب کامپوننت searchable-select
        $categoryOptions = collect($categories)->map(function($label, $value) {
            return [
                'id' => $value,
                'text' => $label
            ];
        })->values()->toArray();

        $carOptions = $cars->map(function($car) {
            return [
                'id' => $car->id,
                'text' => $car->title . ' - ' . $car->brand . ' ' . $car->model
            ];
        })->prepend(['id' => '', 'text' => 'بدون خودرو (هزینه عمومی)'])->values()->toArray();

        $personOptions = $people->map(function($person) {
            $text = $person->full_name;
            if ($person->company_name) {
                $text .= ' (' . $person->company_name . ')';
            }
            return [
                'id' => $person->id,
                'text' => $text
            ];
        })->prepend(['id' => '', 'text' => 'بدون شخص'])->values()->toArray();

        $accountOptions = $accounts->map(function($account) {
            return [
                'id' => $account->id,
                'text' => $account->name . ' (موجودی: ' . number_format($account->amount) . ' ریال)'
            ];
        })->prepend(['id' => '', 'text' => 'بدون کسر از حساب'])->values()->toArray();

        $paymentMethodOptions = $paymentMethods->map(function($method) {
            return [
                'id' => $method->id,
                'text' => $method->name
            ];
        })->prepend(['id' => '', 'text' => 'انتخاب کنید...'])->values()->toArray();

        return view('expenses.create', compact(
            'cars', 
            'people', 
            'accounts', 
            'paymentMethods', 
            'categories',
            'categoryOptions',
            'carOptions',
            'personOptions',
            'accountOptions',
            'paymentMethodOptions'
        ));
    }

    /**
     * ذخیره هزینه جدید
     */
    public function store(Request $request)
    {
        // حذف کاما از مبلغ
        if ($request->has('amount')) {
            $amount = str_replace(',', '', $request->amount);
            $request->merge(['amount' => $amount]);
        }
        
        // تبدیل تاریخ شمسی به میلادی
        if ($request->filled('expense_date')) {
            $gregorianDate = jalali_to_gregorian($request->expense_date);
            $request->merge(['expense_date' => $gregorianDate]);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1000',
            'expense_date' => 'required|date',
            'category' => 'required|string',
            'car_id' => 'nullable|exists:cars,id',
            'person_id' => 'nullable|exists:people,id',
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
        $expense->load(['car', 'person', 'account', 'paymentMethod', 'creator']);
        return view('expenses.show', compact('expense'));
    }

    /**
     * فرم ویرایش هزینه
     */
    public function edit(Expense $expense)
    {
        $cars = Car::where('status', '!=', 'sold')->get();
        $people = Person::orderBy('full_name')->get();
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

        // تبدیل به فرمت مناسب کامپوننت searchable-select
        $categoryOptions = collect($categories)->map(function($label, $value) {
            return [
                'id' => $value,
                'text' => $label
            ];
        })->values()->toArray();

        $carOptions = $cars->map(function($car) {
            return [
                'id' => $car->id,
                'text' => $car->title . ' - ' . $car->brand . ' ' . $car->model
            ];
        })->prepend(['id' => '', 'text' => 'بدون خودرو (هزینه عمومی)'])->values()->toArray();

        $personOptions = $people->map(function($person) {
            $text = $person->full_name;
            if ($person->company_name) {
                $text .= ' (' . $person->company_name . ')';
            }
            return [
                'id' => $person->id,
                'text' => $text
            ];
        })->prepend(['id' => '', 'text' => 'بدون شخص'])->values()->toArray();

        $accountOptions = $accounts->map(function($account) {
            return [
                'id' => $account->id,
                'text' => $account->name . ' (موجودی: ' . number_format($account->amount) . ' ریال)'
            ];
        })->prepend(['id' => '', 'text' => 'بدون کسر از حساب'])->values()->toArray();

        $paymentMethodOptions = $paymentMethods->map(function($method) {
            return [
                'id' => $method->id,
                'text' => $method->name
            ];
        })->prepend(['id' => '', 'text' => 'انتخاب کنید...'])->values()->toArray();

        // اضافه کردن تاریخ شمسی به expense
        $expense->jalali_expense_date = $expense->expense_date_jalali;

        return view('expenses.edit', compact(
            'expense', 
            'cars', 
            'people', 
            'accounts', 
            'paymentMethods', 
            'categories',
            'categoryOptions',
            'carOptions',
            'personOptions',
            'accountOptions',
            'paymentMethodOptions'
        ));
    }

    /**
     * بروزرسانی هزینه
     */
    public function update(Request $request, Expense $expense)
    {
        // حذف کاما از مبلغ
        if ($request->has('amount')) {
            $amount = str_replace(',', '', $request->amount);
            $request->merge(['amount' => $amount]);
        }
        
        // تبدیل تاریخ شمسی به میلادی
        if ($request->filled('expense_date')) {
            $gregorianDate = jalali_to_gregorian($request->expense_date);
            $request->merge(['expense_date' => $gregorianDate]);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1000',
            'expense_date' => 'required|date',
            'category' => 'required|string',
            'car_id' => 'nullable|exists:cars,id',
            'person_id' => 'nullable|exists:people,id',
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