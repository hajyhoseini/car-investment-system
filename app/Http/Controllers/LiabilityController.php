<?php

namespace App\Http\Controllers;

use App\Models\Liability;
use Illuminate\Http\Request;
use App\Traits\JalaliDateTrait;

class LiabilityController extends Controller
{
    use JalaliDateTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $liabilities = Liability::latest()->get();
        
        // تبدیل تاریخ‌ها به شمسی برای نمایش
        foreach ($liabilities as $liability) {
            $liability->jalali_due_date = $this->toJalali($liability->due_date);
        }
        
        return view('liabilities.index', compact('liabilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $todayJalali = $this->nowJalali();
        return view('liabilities.create', compact('todayJalali'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:debt,check,installment',
            'creditor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'due_date' => 'required|string', // تاریخ به صورت شمسی دریافت می‌شود
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

        // تبدیل تاریخ شمسی به میلادی برای ذخیره در دیتابیس
        $validated['due_date'] = $this->toGregorian($request->due_date);

        Liability::create($validated);

        return redirect()->route('liabilities.index')
            ->with('success', 'تعهد با موفقیت ثبت شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Liability $liability)
    {
        $liability->jalali_due_date = $this->toJalali($liability->due_date);
        return view('liabilities.show', compact('liability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Liability $liability)
    {
        $liability->jalali_due_date = $this->toJalali($liability->due_date);
        return view('liabilities.edit', compact('liability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Liability $liability)
    {
        $validated = $request->validate([
            'type' => 'required|in:debt,check,installment',
            'creditor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'due_date' => 'required|string', // تاریخ به صورت شمسی دریافت می‌شود
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

        // تبدیل تاریخ شمسی به میلادی برای ذخیره در دیتابیس
        $validated['due_date'] = $this->toGregorian($request->due_date);

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
        $liabilities = Liability::where('status', $status)
            ->latest()
            ->get();

        foreach ($liabilities as $liability) {
            $liability->jalali_due_date = $this->toJalali($liability->due_date);
        }

        return view('liabilities.index', compact('liabilities'));
    }

    /**
     * متد کمکی برای دریافت تعهدات سررسید شده
     */
    public function overdue()
    {
        $liabilities = Liability::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->latest()
            ->get();

        foreach ($liabilities as $liability) {
            $liability->jalali_due_date = $this->toJalali($liability->due_date);
        }

        return view('liabilities.overdue', compact('liabilities'));
    }

    /**
     * متد کمکی برای دریافت تعهدات امروز
     */
    public function today()
    {
        $liabilities = Liability::whereDate('due_date', now()->toDateString())
            ->latest()
            ->get();

        foreach ($liabilities as $liability) {
            $liability->jalali_due_date = $this->toJalali($liability->due_date);
        }

        return view('liabilities.today', compact('liabilities'));
    }
}