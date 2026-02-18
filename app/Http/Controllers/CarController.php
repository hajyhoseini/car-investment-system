<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::latest()->paginate(10);
        return view('cars.index', compact('cars'));
    }

    public function create()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1300|max:1405',
            'kilometers' => 'required|integer|min:0',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        Car::create($validated);

        return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت اضافه شد.');
    }

    public function show(Car $car)
    {
        return view('cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1300|max:1405',
            'kilometers' => 'required|integer|min:0',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'status' => 'required|in:available,sold,reserved',
        ]);

        $car->update($validated);

        return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت ویرایش شد.');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('cars.index')->with('success', 'خودرو با موفقیت حذف شد.');
    }
}