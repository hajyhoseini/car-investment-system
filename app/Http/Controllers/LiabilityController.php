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
    public function index()
    {
        $liabilities = Liability::with('person')->latest()->get();
        
        return view('liabilities.index', compact('liabilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $people = Person::orderBy('full_name')->get();
        
        // تبدیل people به فرمت مناسب کامپوننت
        $formattedPeople = $people->map(function($person) {
            $text = $person->full_name;
            $subtext = $person->type_label . ($person->mobile ? ' - ' . $person->mobile : '');
            return [
                'id' => $person->id,
                'text' => $text,
                'subtext' => $subtext
            ];
        })->toArray();
        
        $todayJalali = now_jalali('Y/m/d');
        
        return view('liabilities.create', compact('formattedPeople', 'todayJalali'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:debt,check,installment',
            'person_id' => 'nullable|exists:people,id',
            'creditor_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'due_date' => 'required|string',
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

        // تبدیل تاریخ شمسی به میلادی
        $validated['due_date'] = jalali_to_gregorian($request->due_date);

        // اگه person_id انتخاب شده، creditor_name رو خالی کن
        if (!empty($validated['person_id'])) {
            $validated['creditor_name'] = null;
        }

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
        $subtext = $person->type_label . ($person->phone ? ' - ' . $person->phone : '');
        return [
            'id' => $person->id,
            'text' => $text,
            'subtext' => $subtext
        ];
    })->toArray();
    
    // اضافه کردن تاریخ شمسی به liability
    $liability->jalali_due_date = $liability->jalali_due_date;
    
    return view('liabilities.edit', compact('liability', 'formattedPeople'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Liability $liability)
    {
        $validated = $request->validate([
            'type' => 'required|in:debt,check,installment',
            'person_id' => 'nullable|exists:people,id',
            'creditor_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'due_date' => 'required|string',
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

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
            ->get();

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
            ->get();

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
            ->get();

        return view('liabilities.today', compact('liabilities'));
    }
}