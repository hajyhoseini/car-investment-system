<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\CarSaleController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LiabilityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// صفحه اصلی
Route::get('/', function () {
    return view('welcome');
});

// =============================================
// مسیرهای نیازمند احراز هویت و تایید ایمیل
// =============================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // -----------------------------------------------------------------
    // داشبوردهای اصلی
    // -----------------------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    
    // -----------------------------------------------------------------
    // منابع اصلی با سطح دسترسی (بدون حذف)
    // -----------------------------------------------------------------
    Route::resource('cars', CarController::class);
    Route::resource('investors', InvestorController::class)->except(['destroy']);
    Route::resource('investments', InvestmentController::class)->except(['destroy']);
    Route::resource('car-sales', CarSaleController::class);
    Route::resource('assets', AssetController::class)->except(['destroy']);
    Route::resource('liabilities', LiabilityController::class)->except(['destroy']);
    
    // -----------------------------------------------------------------
    // مسیرهای اختصاصی برای نمایش جزئیات با بررسی مالکیت
    // -----------------------------------------------------------------
    Route::middleware(['owner:investor'])->group(function () {
        Route::get('/investors/{investor}', [InvestorController::class, 'show'])->name('investors.show');
    });
    
    Route::middleware(['owner:investment'])->group(function () {
        Route::get('/investments/{investment}', [InvestmentController::class, 'show'])->name('investments.show');
        Route::get('/investments/{investment}/edit', [InvestmentController::class, 'edit'])->name('investments.edit');
        Route::put('/investments/{investment}', [InvestmentController::class, 'update'])->name('investments.update');
    });
    
    // -----------------------------------------------------------------
    // مسیرهای ویژه فروش خودرو
    // -----------------------------------------------------------------
    Route::get('/cars/{car}/sell', [CarSaleController::class, 'create'])->name('cars.sell');
    Route::post('/cars/{car}/sell', [CarSaleController::class, 'store'])->name('cars.sell.store');
    
    // -----------------------------------------------------------------
    // گزارش سود سرمایه‌گذاران
    // -----------------------------------------------------------------
    Route::get('/car-sales/{carSale}/profits', [CarSaleController::class, 'investorProfits'])->name('car-sales.profits');
    
    // -----------------------------------------------------------------
    // مسیرهای مخصوص ادمین (حذف و مدیریت)
    // -----------------------------------------------------------------
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // مجوزهای حذف
        Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
        Route::delete('/investors/{investor}', [InvestorController::class, 'destroy'])->name('investors.destroy');
        Route::delete('/investments/{investment}', [InvestmentController::class, 'destroy'])->name('investments.destroy');
        Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
        Route::delete('/liabilities/{liability}', [LiabilityController::class, 'destroy'])->name('liabilities.destroy');
        
        // مدیریت کاربران
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });
});

// =============================================
// مسیرهای پروفایل (نیازمند احراز هویت)
// =============================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

// مسیرهای احراز هویت
require __DIR__.'/auth.php';