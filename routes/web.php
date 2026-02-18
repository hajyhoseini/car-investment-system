<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\CarSaleController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LiabilityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // داشبورد
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // منابع اصلی (Resource Controllers)
    Route::resource('cars', CarController::class);
    Route::resource('investors', InvestorController::class);
    Route::resource('investments', InvestmentController::class);
    Route::resource('car-sales', CarSaleController::class);
    Route::resource('assets', AssetController::class);
    Route::resource('liabilities', LiabilityController::class);
    
    // مسیرهای ویژه برای فروش خودرو
    Route::get('/cars/{car}/sell', [CarSaleController::class, 'create'])->name('cars.sell');
    Route::post('/cars/{car}/sell', [CarSaleController::class, 'store'])->name('cars.sell.store');
    
    // گزارش سود سرمایه‌گذاران
    Route::get('/car-sales/{carSale}/profits', [CarSaleController::class, 'investorProfits'])->name('car-sales.profits');
});

// مسیرهای پروفایل (نیاز به احراز هویت)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';