<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::latest()->get();
        $totalValue = $assets->sum(function($asset) {
            return $asset->value ?? $asset->amount;
        });
        
        return view('assets.index', compact('assets', 'totalValue'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:bank,dollar,gold',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // برای حساب بانکی، value رو برابر amount قرار می‌دیم
        if ($validated['type'] === 'bank') {
            $validated['value'] = $validated['amount'];
        }

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'دارایی با موفقیت اضافه شد.');
    }

    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'type' => 'required|in:bank,dollar,gold',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validated['type'] === 'bank') {
            $validated['value'] = $validated['amount'];
        }

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'دارایی با موفقیت ویرایش شد.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'دارایی با موفقیت حذف شد.');
    }
}