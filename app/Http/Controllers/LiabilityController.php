<?php

namespace App\Http\Controllers;

use App\Models\Liability;
use Illuminate\Http\Request;

class LiabilityController extends Controller
{
    public function index()
    {
        $liabilities = Liability::latest()->get();
        return view('liabilities.index', compact('liabilities'));
    }

    public function create()
    {
        return view('liabilities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:debt,check,installment',
            'creditor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

        Liability::create($validated);

        return redirect()->route('liabilities.index')->with('success', 'تعهد با موفقیت ثبت شد.');
    }

    public function show(Liability $liability)
    {
        return view('liabilities.show', compact('liability'));
    }

    public function edit(Liability $liability)
    {
        return view('liabilities.edit', compact('liability'));
    }

    public function update(Request $request, Liability $liability)
    {
        $validated = $request->validate([
            'type' => 'required|in:debt,check,installment',
            'creditor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

        $liability->update($validated);

        return redirect()->route('liabilities.index')->with('success', 'تعهد با موفقیت ویرایش شد.');
    }

    public function destroy(Liability $liability)
    {
        $liability->delete();
        return redirect()->route('liabilities.index')->with('success', 'تعهد با موفقیت حذف شد.');
    }
}