<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PersonController extends Controller implements HasMiddleware
{
    /**
     * تعریف middlewareها
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            // برای لاراول ۱۱/۱۲، authorizeResource به این شکل استفاده میشه
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $people = Person::latest()->paginate(15);
        return view('people.index', compact('people'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('people.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'national_code' => 'nullable|string|size:10|unique:people',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'type' => 'required|in:buyer,seller,creditor,debtor,other',
            'description' => 'nullable|string',
            'is_legal' => 'sometimes|boolean',
            'company_name' => 'nullable|string|max:255',
            'economic_code' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|size:10',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // مقدار پیش‌فرض برای is_legal
        $validated['is_legal'] = $request->has('is_legal');

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('people', 'public');
            $validated['avatar'] = basename($path);
        }

        Person::create($validated);

        return redirect()->route('people.index')
            ->with('success', 'شخص با موفقیت اضافه شد.');
    }

    /**
     * Display the specified resource.
     */
public function show(Person $person)
{
    // بارگذاری روابط
    $person->load([
        'purchases.car', 
        'liabilities'
    ]);
    
    return view('people.show', compact('person'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        return view('people.edit', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'national_code' => 'nullable|string|size:10|unique:people,national_code,' . $person->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'type' => 'required|in:buyer,seller,creditor,debtor,other',
            'description' => 'nullable|string',
            'is_legal' => 'sometimes|boolean',
            'company_name' => 'nullable|string|max:255',
            'economic_code' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|size:10',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $validated['is_legal'] = $request->has('is_legal');

        if ($request->hasFile('avatar')) {
            // حذف عکس قبلی
            if ($person->avatar) {
                Storage::disk('public')->delete('people/' . $person->avatar);
            }
            $path = $request->file('avatar')->store('people', 'public');
            $validated['avatar'] = basename($path);
        }

        $person->update($validated);

        return redirect()->route('people.index')
            ->with('success', 'شخص با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        // حذف عکس
        if ($person->avatar) {
            Storage::disk('public')->delete('people/' . $person->avatar);
        }
        
        $person->delete();

        return redirect()->route('people.index')
            ->with('success', 'شخص با موفقیت حذف شد.');
    }

    /**
     * جستجوی اشخاص برای استفاده در select2
     */
    public function search(Request $request)
    {
        $term = $request->get('q');
        $type = $request->get('type');
        
        $query = Person::query();
        
        if ($type) {
            $query->where('type', $type);
        }
        
        if ($term) {
            $query->where(function($q) use ($term) {
                $q->where('full_name', 'like', "%{$term}%")
                  ->orWhere('company_name', 'like', "%{$term}%")
                  ->orWhere('national_code', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            });
        }
        
        $people = $query->limit(20)->get();
        
        return response()->json($people);
    }
}