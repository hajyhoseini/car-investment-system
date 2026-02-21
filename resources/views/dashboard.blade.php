@extends('layouts.app')

@section('content')
<div class="py-10 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 md:space-y-10">

        <!-- خوش‌آمدگویی -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-2xl p-6 md:p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-2">
                        خوش آمدید{{ Auth::user()->name ? '، ' . Auth::user()->name : '' }} 👋
                    </h1>
                    <p class="text-base md:text-lg opacity-90 max-w-2xl">
                        به سیستم مدیریت سرمایه‌گذاری خودرو خوش آمدید.
                        در ادامه خلاصه‌ای از وضعیت فعلی سیستم و فعالیت‌های اخیر را مشاهده می‌کنید.
                    </p>
                </div>

                @if(Auth::check())
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-5 text-center min-w-[180px] border border-white/30">
                        <div class="text-sm opacity-90 mb-1">سطح دسترسی شما</div>
                        <div class="text-xl md:text-2xl font-bold">
                            {{ Auth::user()->getRoleNames()->implode('، ') ?: 'کاربر' }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- کارت‌های آمار سریع – اعداد کوچکتر + جلوگیری از بیرون زدگی -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
            <!-- خودروها -->
            @can('view cars')
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs text-gray-600 font-medium">خودروها</div>
                            <div class="text-2xl md:text-3xl font-bold text-gray-900 mt-1 truncate">{{ $totalCars ?? 0 }}</div>
                            <div class="flex flex-wrap justify-between text-xs mt-1 gap-1">
                                <span class="text-green-600 font-medium">{{ $availableCars ?? 0 }} موجود</span>
                                <span class="text-red-600 font-medium">{{ $soldCars ?? 0 }} فروخته</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2.5 text-xs text-gray-600 border-t">
                    <a href="{{ route('cars.index') }}" class="hover:text-blue-600 font-medium flex items-center gap-1">
                        مشاهده همه خودروها
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            @endcan

            <!-- سرمایه‌گذاران -->
            @can('view investors')
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                            <svg class="h-7 w-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs text-gray-600 font-medium">سرمایه‌گذاران</div>
                            <div class="text-2xl md:text-3xl font-bold text-gray-900 mt-1 truncate">{{ $totalInvestors ?? 0 }}</div>
                            <div class="text-xs text-gray-600 mt-1 truncate">
                                کل سرمایه: {{ number_format($totalInvested ?? 0) }} ریال
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2.5 text-xs text-gray-600 border-t">
                    <a href="{{ route('investors.index') }}" class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                        مشاهده همه سرمایه‌گذاران
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            @endcan

            <!-- دارایی‌ها -->
            @can('view assets')
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 bg-amber-100 rounded-full p-3">
                            <svg class="h-7 w-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs text-gray-600 font-medium">کل دارایی‌ها</div>
                            <div class="text-2xl md:text-3xl font-bold text-gray-900 mt-1 truncate">
                                {{ number_format($totalAssets ?? 0) }} ریال
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2.5 text-xs text-gray-600 border-t">
                    <a href="{{ route('assets.index') }}" class="text-amber-600 hover:text-amber-800 font-medium flex items-center gap-1">
                        مدیریت دارایی‌ها
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            @endcan

            <!-- خالص دارایی -->
            @can('view dashboard')
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 bg-purple-100 rounded-full p-3">
                            <svg class="h-7 w-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs text-gray-600 font-medium">خالص دارایی</div>
                            <div class="text-2xl md:text-3xl font-bold text-gray-900 mt-1 truncate">
                                {{ number_format($netWorth ?? 0) }} ریال
                            </div>
                            <div class="text-xs text-gray-600 mt-1 truncate">
                                بدهی‌ها: {{ number_format($totalLiabilities ?? 0) }} ریال
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2.5 text-xs text-gray-600 border-t">
                    <span class="font-medium">وضعیت مالی کلی</span>
                </div>
            </div>
            @endcan
        </div>

        <!-- بخش نمودارها و وضعیت‌ها -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            <!-- وضعیت خودروها -->
            @can('view cars')
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-semibold mb-5 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <span>وضعیت خودروها</span>
                </h3>
                <div class="space-y-4">
                    @php $totalCarsForCalc = max($totalCars ?? 1, 1); @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>موجود</span>
                            <span class="font-medium">{{ $availableCars ?? 0 }} خودرو</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-green-500 h-full transition-all" style="width: {{ (($availableCars ?? 0) / $totalCarsForCalc) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>فروخته شده</span>
                            <span class="font-medium">{{ $soldCars ?? 0 }} خودرو</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-red-500 h-full" style="width: {{ (($soldCars ?? 0) / $totalCarsForCalc) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>رزرو</span>
                            <span class="font-medium">{{ $reservedCars ?? 0 }} خودرو</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-amber-500 h-full" style="width: {{ (($reservedCars ?? 0) / $totalCarsForCalc) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-200 text-sm font-medium flex justify-between">
                    <span>ارزش کل خودروها:</span>
                    <span class="text-blue-700">{{ number_format($totalCarValue ?? 0) }} ریال</span>
                </div>
            </div>
            @endcan

            <!-- ترکیب دارایی‌ها -->
            @can('view assets')
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-semibold mb-5 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span>ترکیب دارایی‌ها</span>
                </h3>
                <div class="space-y-4">
                    @php $totalAssetsForCalc = max($totalAssets ?? 1, 1); @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>حساب بانکی</span>
                            <span class="font-medium truncate">{{ number_format($bankAssets ?? 0) }} ریال</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-blue-500 h-full" style="width: {{ (($bankAssets ?? 0) / $totalAssetsForCalc) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>دلار</span>
                            <span class="font-medium truncate">{{ number_format($dollarAssets ?? 0) }} ریال</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-emerald-500 h-full" style="width: {{ (($dollarAssets ?? 0) / $totalAssetsForCalc) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>طلا</span>
                            <span class="font-medium truncate">{{ number_format($goldAssets ?? 0) }} ریال</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-yellow-500 h-full" style="width: {{ (($goldAssets ?? 0) / $totalAssetsForCalc) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

            <!-- وضعیت تعهدات -->
            @can('view liabilities')
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-semibold mb-5 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span>وضعیت تعهدات</span>
                </h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-700">بدهی‌ها:</span>
                        <span class="font-bold text-red-700 truncate">{{ number_format($debtLiabilities ?? 0) }} ریال</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-700">چک‌ها:</span>
                        <span class="font-bold text-blue-700 truncate">{{ number_format($checkLiabilities ?? 0) }} ریال</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-700">اقساط:</span>
                        <span class="font-bold text-green-700 truncate">{{ number_format($installmentLiabilities ?? 0) }} ریال</span>
                    </div>

                    <div class="pt-4 mt-2 border-t-2 border-gray-200 flex justify-between font-bold text-base">
                        <span>سررسید گذشته:</span>
                        <span class="text-red-700">{{ $overdueLiabilities ?? 0 }} مورد</span>
                    </div>
                </div>
            </div>
            @endcan
        </div>

        <!-- لیست‌های اخیر -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
            <!-- آخرین خودروها -->
            @can('view cars')
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        آخرین خودروهای اضافه شده
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentCars as $car)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0 gap-2">
                                <div class="min-w-0">
                                    <a href="{{ route('cars.show', $car) }}" class="font-medium text-gray-900 hover:text-blue-600 transition block truncate">
                                        {{ $car->title }}
                                    </a>
                                    <div class="text-sm text-gray-600 truncate">
                                        {{ $car->brand }} • {{ $car->model }} • {{ $car->year }}
                                    </div>
                                </div>
                                <div class="text-right whitespace-nowrap">
                                    <div class="font-medium text-blue-700">{{ number_format($car->purchase_price ?? 0) }} ریال</div>
                                    <div class="text-xs mt-1">
                                        @if($car->status == 'available')
                                            <span class="text-green-600 font-medium">موجود</span>
                                        @elseif($car->status == 'sold')
                                            <span class="text-red-600 font-medium">فروخته شده</span>
                                        @else
                                            <span class="text-amber-600 font-medium">رزرو</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">هیچ خودرویی ثبت نشده است.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('cars.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                            مشاهده همه خودروها
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            <!-- آخرین سرمایه‌گذاران -->
            @can('view investors')
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        آخرین سرمایه‌گذاران
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentInvestors as $investor)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0 gap-2">
                                <div class="min-w-0">
                                    <a href="{{ route('investors.show', $investor) }}" class="font-medium text-gray-900 hover:text-green-600 transition block truncate">
                                        {{ $investor->full_name }}
                                    </a>
                                    <div class="text-sm text-gray-600 truncate">{{ $investor->phone ?? '—' }}</div>
                                </div>
                                <div class="text-right whitespace-nowrap">
                                    <div class="font-medium text-green-700">{{ number_format($investor->total_invested ?? 0) }} ریال</div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        {{ $investor->investments_count ?? 0 }} سرمایه‌گذاری
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">هیچ سرمایه‌گذاری ثبت نشده است.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('investors.index') }}" class="text-sm text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                            مشاهده همه سرمایه‌گذاران
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endcan
        </div>

        <!-- آخرین فروش‌ها و تعهدات در انتظار -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
            <!-- آخرین فروش‌ها -->
            @can('view car-sales')
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                        آخرین فروش‌ها
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentSales as $sale)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0 gap-2">
                                <div class="min-w-0">
                                    <a href="{{ route('cars.show', $sale->car) }}" class="font-medium text-gray-900 hover:text-purple-600 transition block truncate">
                                        {{ $sale->car->title ?? '—' }}
                                    </a>
                                    <div class="text-sm text-gray-600 truncate">خریدار: {{ $sale->buyer_name ?? '—' }}</div>
                                </div>
                                <div class="text-right whitespace-nowrap">
                                    <div class="font-medium text-purple-700">{{ number_format($sale->selling_price ?? 0) }} ریال</div>
                                    <div class="text-xs text-green-600 mt-1">
                                        سود: {{ number_format($sale->total_profit ?? 0) }} ریال
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">هیچ فروشی ثبت نشده است.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('car-sales.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium flex items-center gap-1">
                            مشاهده همه فروش‌ها
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            <!-- تعهدات در انتظار -->
            @can('view liabilities')
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-red-600 to-red-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        تعهدات در انتظار پرداخت
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($pendingLiabilities as $liability)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0 gap-2">
                                <div class="min-w-0">
                                    <div class="font-medium text-gray-900 truncate">{{ $liability->creditor_name }}</div>
                                    <div class="text-sm text-gray-600">
                                        @switch($liability->type)
                                            @case('debt') بدهی @break
                                            @case('check') چک @break
                                            @case('installment') قسط @break
                                            @default {{ $liability->type }}
                                        @endswitch
                                    </div>
                                </div>
                                <div class="text-right whitespace-nowrap">
                                    <div class="font-bold text-red-700">{{ number_format($liability->remaining_amount ?? 0) }} ریال</div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        سررسید: {{ $liability->due_date ? \Carbon\Carbon::parse($liability->due_date)->format('Y/m/d') : '—' }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">تعهد در انتظاری وجود ندارد.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('liabilities.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center gap-1">
                            مشاهده همه تعهدات
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endcan
        </div>

    </div>
</div>
@endsection