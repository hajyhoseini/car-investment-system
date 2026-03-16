<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Models\Person;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function index()
    {
        $investors = Investor::with('person', 'user')
            ->whereNotNull('user_id')
            ->orWhereNull('user_id')
            ->latest()
            ->paginate(10);
        
        return view('investors.index', compact('investors'));
    }

    public function create()
    {
        $people = Person::whereNotIn('id', Investor::pluck('person_id')->filter())
            ->orderBy('full_name')
            ->get();
        
        // فرمت کردن اشخاص برای کامپوننت searchable-select
        $formattedPeople = $people->map(function($person) {
            return [
                'id' => $person->id,
                'text' => $person->display_name . ($person->national_code ? ' (کد ملی: ' . $person->national_code . ')' : ''),
                'data' => [
                    'national-code' => $person->national_code,
                    'phone' => $person->phone,
                    'type-label' => $person->type_label,
                    'email' => $person->email,
                    'address' => $person->address
                ]
            ];
        })->toArray();
            
        return view('investors.create', compact('formattedPeople'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id|unique:investors,person_id',
            'description' => 'nullable|string'
        ]);

        Investor::create($validated);

        return redirect()->route('investors.index')->with('success', 'سرمایه‌گذار با موفقیت اضافه شد.');
    }

    public function show(Investor $investor)
    {
        $investor->load(['person', 'investments.car']);
        return view('investors.show', compact('investor'));
    }

    public function edit(Investor $investor)
    {
        $people = Person::whereNotIn('id', Investor::where('id', '!=', $investor->id)->pluck('person_id')->filter())
            ->orWhere('id', $investor->person_id)
            ->orderBy('full_name')
            ->get();
        
        // فرمت کردن اشخاص برای کامپوننت searchable-select
        $formattedPeople = $people->map(function($person) {
            return [
                'id' => $person->id,
                'text' => $person->display_name . ($person->national_code ? ' (کد ملی: ' . $person->national_code . ')' : ''),
                'data' => [
                    'national-code' => $person->national_code,
                    'phone' => $person->phone,
                    'type-label' => $person->type_label,
                    'email' => $person->email,
                    'address' => $person->address
                ]
            ];
        })->toArray();
            
        return view('investors.edit', compact('investor', 'formattedPeople'));
    }

    public function update(Request $request, Investor $investor)
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id|unique:investors,person_id,' . $investor->id,
            'description' => 'nullable|string'
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