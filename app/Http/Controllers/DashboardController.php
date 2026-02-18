<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Investor;
use App\Models\CarSale;
use App\Models\Asset;
use App\Models\Liability;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // آمار خودروها
        $totalCars = Car::count();
        $availableCars = Car::where('status', 'available')->count();
        $soldCars = Car::where('status', 'sold')->count();
        $reservedCars = Car::where('status', 'reserved')->count();
        $totalCarValue = Car::sum('purchase_price');
        
        // آمار سرمایه‌گذاران
        $totalInvestors = Investor::count();
        $totalInvestments = Investor::sum('total_invested');
        $recentInvestors = Investor::withCount('investments')->latest()->take(5)->get();
        
        // آمار فروش
        $recentSales = CarSale::with('car')->latest()->take(5)->get();
        
        // آمار دارایی‌ها
        $assets = Asset::all();
        $totalAssets = $assets->sum(function($asset) {
            return $asset->value ?? $asset->amount;
        });
        $bankAssets = $assets->where('type', 'bank')->sum('amount');
        $dollarAssets = $assets->where('type', 'dollar')->sum('value');
        $goldAssets = $assets->where('type', 'gold')->sum('value');
        
        // آمار تعهدات
        $liabilities = Liability::all();
        $totalLiabilities = $liabilities->sum('remaining_amount');
        $debtLiabilities = $liabilities->where('type', 'debt')->sum('remaining_amount');
        $checkLiabilities = $liabilities->where('type', 'check')->sum('remaining_amount');
        $installmentLiabilities = $liabilities->where('type', 'installment')->sum('remaining_amount');
        $overdueLiabilities = $liabilities->filter(function($item) {
            return $item->isOverdue();
        })->count();
        $pendingLiabilities = Liability::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
        
        // خالص دارایی
        $netWorth = $totalAssets - $totalLiabilities;
        
        // خودروهای اخیر
        $recentCars = Car::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalCars', 'availableCars', 'soldCars', 'reservedCars', 'totalCarValue',
            'totalInvestors', 'totalInvestments', 'recentInvestors',
            'recentSales',
            'totalAssets', 'bankAssets', 'dollarAssets', 'goldAssets',
            'totalLiabilities', 'debtLiabilities', 'checkLiabilities', 
            'installmentLiabilities', 'overdueLiabilities', 'pendingLiabilities',
            'netWorth', 'recentCars'
        ));
    }
}