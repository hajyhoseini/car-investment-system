<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Investor;
use App\Models\Investment;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::with(['car', 'investor'])->latest()->paginate(10);
        return view('investments.index', compact('investments'));
    }

    public function create()
    {
        $cars = Car::where('status', 'available')->get();
        $investors = Investor::all();
        return view('investments.create', compact('cars', 'investors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'investor_id' => 'required|exists:investors,id',
            'amount' => 'required|numeric|min:1000',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'investment_date' => 'required|date',
        ]);

        $car = Car::find($validated['car_id']);
        
        // محاسبه مجموع سرمایه‌گذاری‌های قبلی
        $totalInvested = $car->investments()->sum('amount');
        
        // بررسی اینکه مجموع سرمایه‌گذاری‌ها از قیمت خودرو بیشتر نشه
        if (($totalInvested + $validated['amount']) > $car->purchase_price) {
            return back()->withErrors(['amount' => 'مجموع سرمایه‌گذاری‌ها نمی‌تواند از قیمت خودرو بیشتر باشد.'])->withInput();
        }

        $investment = Investment::create($validated);
        
        // به‌روزرسانی کل سرمایه‌گذاری سرمایه‌گذار
        $investment->investor->updateTotalInvested();

        return redirect()->route('investments.index')->with('success', 'سرمایه‌گذاری با موفقیت ثبت شد.');
    }

    public function show(Investment $investment)
    {
        $investment->load(['car', 'investor']);
        return view('investments.show', compact('investment'));
    }

    public function edit(Investment $investment)
    {
        $cars = Car::where('status', 'available')->get();
        $investors = Investor::all();
        return view('investments.edit', compact('investment', 'cars', 'investors'));
    }

    public function update(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'investor_id' => 'required|exists:investors,id',
            'amount' => 'required|numeric|min:1000',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'investment_date' => 'required|date',
        ]);

        $car = Car::find($validated['car_id']);
        
        // محاسبه مجموع سرمایه‌گذاری‌های قبلی به جز این سرمایه‌گذاری
        $totalInvested = $car->investments()->where('id', '!=', $investment->id)->sum('amount');
        
        if (($totalInvested + $validated['amount']) > $car->purchase_price) {
            return back()->withErrors(['amount' => 'مجموع سرمایه‌گذاری‌ها نمی‌تواند از قیمت خودرو بیشتر باشد.'])->withInput();
        }

        $investment->update($validated);
        
        // به‌روزرسانی کل سرمایه‌گذاری سرمایه‌گذار قبلی و جدید
        $investment->investor->updateTotalInvested();
        if ($investment->investor_id != $validated['investor_id']) {
            Investor::find($validated['investor_id'])->updateTotalInvested();
        }

        return redirect()->route('investments.index')->with('success', 'سرمایه‌گذاری با موفقیت ویرایش شد.');
    }

    public function destroy(Investment $investment)
    {
        $investor = $investment->investor;
        $investment->delete();
        $investor->updateTotalInvested();
        
        return redirect()->route('investments.index')->with('success', 'سرمایه‌گذاری با موفقیت حذف شد.');
    }
}