<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Models\Person;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function index(Request $request) // اضافه کردن Request به پارامترها
    {
        $query = Investor::with('person', 'user');
        
        // فیلتر جستجو بر اساس نام، کد ملی یا تلفن
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('person', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('national_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // فیلتر بر اساس حداقل سرمایه
        if ($request->filled('min_investment')) {
            $minInvestment = str_replace(',', '', $request->min_investment);
            $query->where('total_invested', '>=', $minInvestment);
        }
        
        // فیلتر بر اساس حداکثر سرمایه
        if ($request->filled('max_investment')) {
            $maxInvestment = str_replace(',', '', $request->max_investment);
            $query->where('total_invested', '<=', $maxInvestment);
        }
        
        // فیلتر بر اساس وضعیت (فعال/غیرفعال)
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->whereNotNull('user_id');
            } elseif ($request->status == 'inactive') {
                $query->whereNull('user_id');
            }
        }
        
        // فیلتر بر اساس نوع شخص (حقیقی/حقوقی)
        if ($request->filled('person_type')) {
            $query->whereHas('person', function($q) use ($request) {
                $q->where('type', $request->person_type);
            });
        }
        
        $investors = $query->latest()->paginate(20);
        
        // محاسبه آمار با در نظر گرفتن فیلترها
        $totalInvested = $investors->sum('total_invested');
        $averageInvested = $investors->avg('total_invested') ?? 0;
        
        // آمار تعداد سرمایه‌گذاران فعال و غیرفعال
        $activeCount = Investor::whereNotNull('user_id')->count();
        $inactiveCount = Investor::whereNull('user_id')->count();
        
        return view('investors.index', compact(
            'investors', 
            'totalInvested', 
            'averageInvested',
            'activeCount',
            'inactiveCount'
        ));
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
                'subtext' => $person->type_label . ($person->phone ? ' - ' . $person->phone : ''),
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
                'subtext' => $person->type_label . ($person->phone ? ' - ' . $person->phone : ''),
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