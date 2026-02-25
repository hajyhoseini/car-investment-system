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
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PersonController;
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
    // منابع اصلی با سطح دسترسی
    // -----------------------------------------------------------------
    Route::resource('cars', CarController::class);
    Route::resource('investors', InvestorController::class);
    Route::resource('investments', InvestmentController::class);
    Route::resource('car-sales', CarSaleController::class);
    Route::resource('assets', AssetController::class); // بدون except
    Route::resource('liabilities', LiabilityController::class);
    Route::resource('people', PersonController::class);
    
    // -----------------------------------------------------------------
    // مسیرهای مدیریت تصاویر خودرو
    // -----------------------------------------------------------------
    Route::get('/cars/{car}/images', [App\Http\Controllers\CarImageController::class, 'index'])->name('cars.images');
    Route::post('/cars/{car}/images', [App\Http\Controllers\CarImageController::class, 'store'])->name('cars.images.store');
    Route::post('/cars/{car}/images/{image}/primary', [App\Http\Controllers\CarImageController::class, 'setPrimary'])->name('cars.images.primary');
    Route::delete('/cars/{car}/images/{image}', [App\Http\Controllers\CarImageController::class, 'destroy'])->name('cars.images.destroy');
    
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
    // مسیرهای تراکنش و حساب
    // -----------------------------------------------------------------
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/daily/report', [TransactionController::class, 'dailyReport'])->name('transactions.daily');
    Route::resource('accounts', AccountController::class);
    
    // -----------------------------------------------------------------
    // مسیرهای روش پرداخت (برای ادمین)
    // -----------------------------------------------------------------
    Route::resource('payment-methods', PaymentMethodController::class)->except(['show']);
    
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
    // مسیرهای مخصوص ادمین (حذف و مدیریت) - این بخش رو می‌تونیم حذف کنیم
    // چون routeهای resource خودشون متد destroy رو دارند
    // -----------------------------------------------------------------
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // مدیریت کاربران
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });
    
    // -----------------------------------------------------------------
    // مسیر جستجوی اشخاص
    // -----------------------------------------------------------------
    Route::get('/people/search', [PersonController::class, 'search'])->name('people.search');
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