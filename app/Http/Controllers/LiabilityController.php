<?php
// app/Http/Controllers/LiabilityController.php

namespace App\Http\Controllers;

use App\Models\Liability;
use App\Models\Person;
use Illuminate\Http\Request;

class LiabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
/**
 * Display a listing of the resource.
 */
public function index(Request $request) // اضافه کردن Request به پارامتر
{
    $query = Liability::with('person')->latest();

    // اعمال فیلتر بر اساس نوع تعهد
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // اعمال فیلتر بر اساس وضعیت
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // اعمال فیلتر بر اساس شخص
    if ($request->filled('person_id')) {
        $query->where('person_id', $request->person_id);
    }

    // اعمال فیلتر بر اساس بازه تاریخ (تبدیل تاریخ شمسی به میلادی)
    if ($request->filled('start_date')) {
        $startDate = jalali_to_gregorian($request->start_date);
        $query->whereDate('due_date', '>=', $startDate);
    }
    
    if ($request->filled('end_date')) {
        $endDate = jalali_to_gregorian($request->end_date);
        $query->whereDate('due_date', '<=', $endDate);
    }

    $liabilities = $query->paginate(20);
    
    // محاسبه آمار با در نظر گرفتن فیلترها
    $totalAmount = $liabilities->sum('amount');
    $totalRemaining = $liabilities->sum('remaining_amount');
    $totalCount = $liabilities->total(); // تعداد کل با در نظر گرفتن صفحه‌بندی
    
    // محاسبه تعداد سررسید گذشته با در نظر گرفتن فیلترها
    $overdueCount = $liabilities->filter(function($liability) {
        return $liability->isOverdue();
    })->count();
    
    // تهیه لیست اشخاص برای کامپوننت فیلتر
    $people = Person::orderBy('full_name')->get();
    $personOptions = $people->map(function($person) {
        $text = $person->full_name;
        if ($person->company_name) {
            $text .= ' (' . $person->company_name . ')';
        }
        $subtext = $person->type_label;
        if ($person->mobile) {
            $subtext .= ' - ' . $person->mobile;
        } elseif ($person->phone) {
            $subtext .= ' - ' . $person->phone;
        }
        return [
            'id' => $person->id,
            'text' => $text,
            'subtext' => $subtext
        ];
    })->prepend(['id' => '', 'text' => 'همه اشخاص'])->values()->toArray();

    return view('liabilities.index', compact(
        'liabilities', 
        'totalAmount', 
        'totalRemaining', 
        'totalCount', 
        'overdueCount',
        'personOptions'
    ));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $people = Person::orderBy('full_name')->get();
        
        // تبدیل people به فرمت مناسب کامپوننت searchable-select
        $formattedPeople = $people->map(function($person) {
            $text = $person->full_name;
            if ($person->company_name) {
                $text .= ' (' . $person->company_name . ')';
            }
            $subtext = $person->type_label;
            if ($person->mobile) {
                $subtext .= ' - ' . $person->mobile;
            } elseif ($person->phone) {
                $subtext .= ' - ' . $person->phone;
            }
            return [
                'id' => $person->id,
                'text' => $text,
                'subtext' => $subtext
            ];
        })->prepend(['id' => '', 'text' => 'انتخاب کنید...'])->values()->toArray();
        
        $todayJalali = now_jalali('Y/m/d');
        
        return view('liabilities.create', compact('formattedPeople', 'todayJalali'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // حذف کاما از مبلغ‌ها
        if ($request->has('amount')) {
            $amount = str_replace(',', '', $request->amount);
            $request->merge(['amount' => $amount]);
        }
        
        if ($request->has('remaining_amount')) {
            $remainingAmount = str_replace(',', '', $request->remaining_amount);
            $request->merge(['remaining_amount' => $remainingAmount]);
        }

        $validated = $request->validate([
            'type' => 'required|in:debt,check,installment',
            'person_id' => 'nullable|exists:people,id',
            'creditor_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'remaining_amount' => 'required|numeric|min:0',
            'due_date' => 'required|string',
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

        // اعتبارسنجی اضافی: مبلغ باقی‌مانده نباید از مبلغ کل بیشتر باشد
        if ($validated['remaining_amount'] > $validated['amount']) {
            return back()->withErrors([
                'remaining_amount' => 'مبلغ باقی‌مانده نمی‌تواند از مبلغ کل بیشتر باشد.'
            ])->withInput();
        }

        // تبدیل تاریخ شمسی به میلادی
        $validated['due_date'] = jalali_to_gregorian($request->due_date);

        // اگه person_id انتخاب شده، creditor_name رو خالی کن
        if (!empty($validated['person_id'])) {
            $validated['creditor_name'] = null;
        }

        // اضافه کردن created_by
        $validated['created_by'] = auth()->id();

        Liability::create($validated);

        return redirect()->route('liabilities.index')
            ->with('success', 'تعهد با موفقیت ثبت شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Liability $liability)
    {
        $liability->load('person');
        return view('liabilities.show', compact('liability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Liability $liability)
    {
        $people = Person::orderBy('full_name')->get();
        
        // تبدیل people به فرمت مناسب کامپوننت
        $formattedPeople = $people->map(function($person) {
            $text = $person->full_name;
            if ($person->company_name) {
                $text .= ' (' . $person->company_name . ')';
            }
            $subtext = $person->type_label;
            if ($person->mobile) {
                $subtext .= ' - ' . $person->mobile;
            } elseif ($person->phone) {
                $subtext .= ' - ' . $person->phone;
            }
            return [
                'id' => $person->id,
                'text' => $text,
                'subtext' => $subtext
            ];
        })->prepend(['id' => '', 'text' => 'انتخاب کنید...'])->values()->toArray();
        
        // اضافه کردن تاریخ شمسی به liability
        $liability->jalali_due_date = $liability->jalali_due_date;
        
        return view('liabilities.edit', compact('liability', 'formattedPeople'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Liability $liability)
    {
        // حذف کاما از مبلغ‌ها
        if ($request->has('amount')) {
            $amount = str_replace(',', '', $request->amount);
            $request->merge(['amount' => $amount]);
        }
        
        if ($request->has('remaining_amount')) {
            $remainingAmount = str_replace(',', '', $request->remaining_amount);
            $request->merge(['remaining_amount' => $remainingAmount]);
        }

        $validated = $request->validate([
            'type' => 'required|in:debt,check,installment',
            'person_id' => 'nullable|exists:people,id',
            'creditor_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'remaining_amount' => 'required|numeric|min:0',
            'due_date' => 'required|string',
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

        // اعتبارسنجی اضافی: مبلغ باقی‌مانده نباید از مبلغ کل بیشتر باشد
        if ($validated['remaining_amount'] > $validated['amount']) {
            return back()->withErrors([
                'remaining_amount' => 'مبلغ باقی‌مانده نمی‌تواند از مبلغ کل بیشتر باشد.'
            ])->withInput();
        }

        $validated['due_date'] = jalali_to_gregorian($request->due_date);

        // اگه person_id انتخاب شده، creditor_name رو null کن
        if (!empty($validated['person_id'])) {
            $validated['creditor_name'] = null;
        }

        $liability->update($validated);

        return redirect()->route('liabilities.index')
            ->with('success', 'تعهد با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Liability $liability)
    {
        $liability->delete();

        return redirect()->route('liabilities.index')
            ->with('success', 'تعهد با موفقیت حذف شد.');
    }

    /**
     * متد کمکی برای دریافت لیست تعهدات بر اساس وضعیت
     */
    public function getByStatus($status)
    {
        $liabilities = Liability::with('person')
            ->where('status', $status)
            ->latest()
            ->paginate(20);

        return view('liabilities.index', compact('liabilities'));
    }

    /**
     * متد کمکی برای دریافت تعهدات سررسید شده
     */
    public function overdue()
    {
        $liabilities = Liability::with('person')
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->latest()
            ->paginate(20);

        return view('liabilities.overdue', compact('liabilities'));
    }

    /**
     * متد کمکی برای دریافت تعهدات امروز
     */
    public function today()
    {
        $liabilities = Liability::with('person')
            ->whereDate('due_date', now()->toDateString())
            ->latest()
            ->paginate(20);

        return view('liabilities.today', compact('liabilities'));
    }
}