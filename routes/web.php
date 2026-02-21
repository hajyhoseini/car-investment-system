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
    // داشبورد - همه کاربران لاگین کرده می‌تونن ببینن
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routeهای مخصوص admin
    Route::middleware(['role:admin'])->group(function () {
        // مدیریت کاربران (اگه بخوای بعداً اضافه کنی)
        // Route::resource('users', UserController::class);
        
        // مجوزهای حساس (حذف)
        Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
        Route::delete('/investors/{investor}', [InvestorController::class, 'destroy'])->name('investors.destroy');
        Route::delete('/investments/{investment}', [InvestmentController::class, 'destroy'])->name('investments.destroy');
        Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
        Route::delete('/liabilities/{liability}', [LiabilityController::class, 'destroy'])->name('liabilities.destroy');
    });
    
    // منابع اصلی با سطح دسترسی
    Route::resource('cars', CarController::class)->except(['destroy']);
    Route::resource('investors', InvestorController::class)->except(['destroy']);
    Route::resource('investments', InvestmentController::class)->except(['destroy']);
    Route::resource('car-sales', CarSaleController::class);
    Route::resource('assets', AssetController::class)->except(['destroy']);
    Route::resource('liabilities', LiabilityController::class)->except(['destroy']);
    
    // مسیرهای ویژه برای فروش خودرو
    Route::get('/cars/{car}/sell', [CarSaleController::class, 'create'])->name('cars.sell');
    Route::post('/cars/{car}/sell', [CarSaleController::class, 'store'])->name('cars.sell.store');
    
    // گزارش سود سرمایه‌گذاران
    Route::get('/car-sales/{carSale}/profits', [CarSaleController::class, 'investorProfits'])->name('car-sales.profits');
});

// مسیرهای پروفایل
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routeهای مدیریت کاربران (فقط ادمین)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)
        ->middleware(['auth', 'role:admin']); // middleware اینجا اعمال میشه
});

require __DIR__.'/auth.php';