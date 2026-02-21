<?php

namespace App\Http\Controllers;

use App\Models\Liability;
use Illuminate\Http\Request;
use App\Traits\JalaliDateTrait;

class LiabilityController extends Controller
{
    use JalaliDateTrait;

    public function index()
    {
        $liabilities = Liability::latest()->get();
        
        // تبدیل تاریخ‌ها به شمسی برای نمایش
        foreach ($liabilities as $liability) {
            $liability->jalali_due_date = $this->convertToJalali($liability->due_date);
        }
        
        return view('liabilities.index', compact('liabilities'));
    }

    public function create()
    {
        $todayJalali = $this->nowJalali();
        return view('liabilities.create', compact('todayJalali'));
    }

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
        $validated['due_date'] = $this->convertToGregorian($request->due_date);

        Liability::create($validated);

        return redirect()->route('liabilities.index')->with('success', 'تعهد با موفقیت ثبت شد.');
    }

    public function show(Liability $liability)
    {
        $liability->jalali_due_date = $this->convertToJalali($liability->due_date);
        return view('liabilities.show', compact('liability'));
    }

    public function edit(Liability $liability)
    {
        $liability->jalali_due_date = $this->convertToJalali($liability->due_date);
        return view('liabilities.edit', compact('liability'));
    }

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
        $validated['due_date'] = $this->convertToGregorian($request->due_date);

        $liability->update($validated);

        return redirect()->route('liabilities.index')->with('success', 'تعهد با موفقیت ویرایش شد.');
    }

    public function destroy(Liability $liability)
    {
        $liability->delete();
        return redirect()->route('liabilities.index')->with('success', 'تعهد با موفقیت حذف شد.');
    }
}