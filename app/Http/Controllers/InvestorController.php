<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function index()
    {
        $investors = Investor::with('investments')->latest()->paginate(10);
        return view('investors.index', compact('investors'));
    }

    public function create()
    {
        return view('investors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'national_code' => 'required|string|unique:investors|max:10',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Investor::create($validated);

        return redirect()->route('investors.index')->with('success', 'سرمایه‌گذار با موفقیت اضافه شد.');
    }

    public function show(Investor $investor)
    {
        $investor->load('investments.car');
        return view('investors.show', compact('investor'));
    }

    public function edit(Investor $investor)
    {
        return view('investors.edit', compact('investor'));
    }

    public function update(Request $request, Investor $investor)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'national_code' => 'required|string|unique:investors,national_code,' . $investor->id,
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $investor->update($validated);

        return redirect()->route('investors.index')->with('success', 'سرمایه‌گذار با موفقیت ویرایش شد.');
    }

    public function destroy(Investor $investor)
    {
        $investor->delete();
        return redirect()->route('investors.index')->with('success', 'سرمایه‌گذار با موفقیت حذف شد.');
    }
}