{{-- resources/views/car-sales/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">جزئیات فروش خودرو</h2>
                    <div class="flex gap-2">
                        @can('edit sales')
                            <a href="{{ route('car-sales.edit', $carSale) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                ویرایش
                            </a>
                        @endcan
                        <a href="{{ route('car-sales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                <!-- اطلاعات فروش -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-purple-800">اطلاعات فروش</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">قیمت فروش:</span>
                                <div class="text-xl font-bold text-purple-700">{{ fa_currency($carSale->selling_price) }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">سود کل:</span>
                                <div class="text-xl font-bold text-green-600">{{ fa_currency($carSale->total_profit) }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ فروش:</span>
                                <div class="text-lg">{{ jalali_date($carSale->sale_date) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-blue-800">اطلاعات خودرو</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">خودرو:</span>
                                <div class="text-lg font-medium">
                                    <a href="{{ route('cars.show', $carSale->car) }}" class="text-blue-600 hover:underline">
                                        {{ $carSale->car->title }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">برند/مدل:</span>
                                <div class="text-lg">{{ $carSale->car->brand }} - {{ $carSale->car->model }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">قیمت خرید:</span>
                                <div class="text-lg font-bold text-blue-700">{{ fa_currency($carSale->car->purchase_price) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-green-800">اطلاعات خریدار</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">نام خریدار:</span>
                                <div class="text-lg">{{ $carSale->buyer_name }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تلفن خریدار:</span>
                                <div class="text-lg">{{ $carSale->buyer_phone }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- لینک گزارش سود سرمایه‌گذاران -->
                    <div class="bg-indigo-50 p-6 rounded-lg flex items-center justify-center">
                        <a href="{{ route('car-sales.profits', $carSale) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition inline-flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            مشاهده گزارش سود سرمایه‌گذاران
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection