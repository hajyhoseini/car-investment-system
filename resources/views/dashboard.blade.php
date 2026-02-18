@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- خوش‌آمدگویی -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg mb-6 p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">خوش آمدید، {{ Auth::user()->name }} 👋</h1>
            <p class="opacity-90">به سیستم مدیریت سرمایه‌گذاری خودرو خوش آمدید. در زیر خلاصه‌ای از فعالیت‌ها و وضعیت مالی خود را مشاهده می‌کنید.</p>
        </div>

        <!-- کارت‌های آمار سریع -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- خودروها -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div class="mr-4 flex-1">
                            <div class="text-sm text-gray-500">خودروها</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $totalCars }}</div>
                            <div class="flex justify-between text-xs mt-1">
                                <span class="text-green-600">{{ $availableCars }} موجود</span>
                                <span class="text-red-600">{{ $soldCars }} فروخته</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2 text-xs text-gray-500">
                    <a href="{{ route('cars.index') }}" class="hover:text-blue-600">مشاهده همه خودروها →</a>
                </div>
            </div>

            <!-- سرمایه‌گذاران -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="mr-4 flex-1">
                            <div class="text-sm text-gray-500">سرمایه‌گذاران</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $totalInvestors }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                کل سرمایه: {{ number_format($totalInvestments) }} ریال
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2 text-xs text-gray-500">
                    <a href="{{ route('investors.index') }}" class="hover:text-green-600">مشاهده همه سرمایه‌گذاران →</a>
                </div>
            </div>

            <!-- دارایی‌ها -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="mr-4 flex-1">
                            <div class="text-sm text-gray-500">کل دارایی‌ها</div>
                            <div class="text-2xl font-bold text-gray-800">{{ number_format($totalAssets) }}</div>
                            <div class="text-xs text-gray-500 mt-1">ریال</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2 text-xs text-gray-500">
                    <a href="{{ route('assets.index') }}" class="hover:text-yellow-600">مدیریت دارایی‌ها →</a>
                </div>
            </div>

            <!-- خالص دارایی -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="mr-4 flex-1">
                            <div class="text-sm text-gray-500">خالص دارایی</div>
                            <div class="text-2xl font-bold text-gray-800">{{ number_format($netWorth) }}</div>
                            <div class="text-xs text-gray-500 mt-1">ریال</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2 text-xs text-gray-500">
                    <span class="text-gray-500">بدهی‌ها: {{ number_format($totalLiabilities) }} ریال</span>
                </div>
            </div>
        </div>

        <!-- نمودار و آمار پیشرفته -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- وضعیت خودروها -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="h-5 w-5 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    وضعیت خودروها
                </h3>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>موجود</span>
                            <span class="font-bold">{{ $availableCars }} خودرو</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalCars > 0 ? ($availableCars/$totalCars)*100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>فروخته شده</span>
                            <span class="font-bold">{{ $soldCars }} خودرو</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalCars > 0 ? ($soldCars/$totalCars)*100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>رزرو</span>
                            <span class="font-bold">{{ $reservedCars }} خودرو</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalCars > 0 ? ($reservedCars/$totalCars)*100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">مجموع ارزش خودروها:</span>
                        <span class="font-bold text-blue-600">{{ number_format($totalCarValue) }} ریال</span>
                    </div>
                </div>
            </div>

            <!-- ترکیب دارایی‌ها -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="h-5 w-5 ml-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ترکیب دارایی‌ها
                </h3>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>حساب بانکی</span>
                            <span class="font-bold">{{ number_format($bankAssets) }} ریال</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalAssets > 0 ? ($bankAssets/$totalAssets)*100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>دلار</span>
                            <span class="font-bold">{{ number_format($dollarAssets) }} ریال</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalAssets > 0 ? ($dollarAssets/$totalAssets)*100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>طلا</span>
                            <span class="font-bold">{{ number_format($goldAssets) }} ریال</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalAssets > 0 ? ($goldAssets/$totalAssets)*100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- وضعیت تعهدات -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="h-5 w-5 ml-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    وضعیت تعهدات
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">بدهی‌ها:</span>
                        <span class="font-bold text-red-600">{{ number_format($debtLiabilities) }} ریال</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">چک‌ها:</span>
                        <span class="font-bold text-blue-600">{{ number_format($checkLiabilities) }} ریال</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">اقساط:</span>
                        <span class="font-bold text-green-600">{{ number_format($installmentLiabilities) }} ریال</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between">
                            <span class="font-semibold">سررسید گذشته:</span>
                            <span class="font-bold text-red-600">{{ $overdueLiabilities }} مورد</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- لیست‌های اخیر -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- خودروهای اخیر -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-gradient-to-l from-blue-500 to-blue-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        آخرین خودروهای اضافه شده
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentCars as $car)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div>
                                <a href="{{ route('cars.show', $car) }}" class="font-medium text-gray-800 hover:text-blue-600">
                                    {{ $car->title }}
                                </a>
                                <div class="text-sm text-gray-500">{{ $car->brand }} - {{ $car->model }} ({{ $car->year }})</div>
                            </div>
                            <div class="text-left">
                                <div class="font-bold text-blue-600">{{ number_format($car->purchase_price) }} ریال</div>
                                <div class="text-xs">
                                    @if($car->status == 'available')
                                        <span class="text-green-600">موجود</span>
                                    @elseif($car->status == 'sold')
                                        <span class="text-red-600">فروخته شده</span>
                                    @else
                                        <span class="text-yellow-600">رزرو</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center">هیچ خودرویی ثبت نشده است.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 text-left">
                        <a href="{{ route('cars.index') }}" class="text-sm text-blue-600 hover:text-blue-800">مشاهده همه خودروها →</a>
                    </div>
                </div>
            </div>

            <!-- سرمایه‌گذاران اخیر -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-gradient-to-l from-green-500 to-green-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        آخرین سرمایه‌گذاران
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentInvestors as $investor)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div>
                                <a href="{{ route('investors.show', $investor) }}" class="font-medium text-gray-800 hover:text-green-600">
                                    {{ $investor->full_name }}
                                </a>
                                <div class="text-sm text-gray-500">{{ $investor->phone }}</div>
                            </div>
                            <div class="text-left">
                                <div class="font-bold text-green-600">{{ number_format($investor->total_invested) }} ریال</div>
                                <div class="text-xs text-gray-500">{{ $investor->investments_count ?? 0 }} سرمایه‌گذاری</div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center">هیچ سرمایه‌گذاری ثبت نشده است.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 text-left">
                        <a href="{{ route('investors.index') }}" class="text-sm text-green-600 hover:text-green-800">مشاهده همه سرمایه‌گذاران →</a>
                    </div>
                </div>
            </div>

            <!-- آخرین فروش‌ها -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-gradient-to-l from-purple-500 to-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                        </svg>
                        آخرین فروش‌ها
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentSales as $sale)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div>
                                <a href="{{ route('cars.show', $sale->car) }}" class="font-medium text-gray-800 hover:text-purple-600">
                                    {{ $sale->car->title }}
                                </a>
                                <div class="text-sm text-gray-500">خریدار: {{ $sale->buyer_name }}</div>
                            </div>
                            <div class="text-left">
                                <div class="font-bold text-purple-600">{{ number_format($sale->selling_price) }} ریال</div>
                                <div class="text-xs text-green-600">سود: {{ number_format($sale->total_profit) }} ریال</div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center">هیچ فروشی ثبت نشده است.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 text-left">
                        <a href="{{ route('car-sales.index') }}" class="text-sm text-purple-600 hover:text-purple-800">مشاهده همه فروش‌ها →</a>
                    </div>
                </div>
            </div>

            <!-- تعهدات در انتظار -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-gradient-to-l from-red-500 to-red-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        تعهدات در انتظار پرداخت
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($pendingLiabilities as $liability)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div>
                                <div class="font-medium text-gray-800">{{ $liability->creditor_name }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($liability->type == 'debt') بدهی
                                    @elseif($liability->type == 'check') چک
                                    @else قسط
                                    @endif
                                </div>
                            </div>
                            <div class="text-left">
                                <div class="font-bold text-red-600">{{ number_format($liability->remaining_amount) }} ریال</div>
                                <div class="text-xs text-gray-500">سررسید: {{ $liability->due_date }}</div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center">تعهد در انتظاری وجود ندارد.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 text-left">
                        <a href="{{ route('liabilities.index') }}" class="text-sm text-red-600 hover:text-red-800">مشاهده همه تعهدات →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection