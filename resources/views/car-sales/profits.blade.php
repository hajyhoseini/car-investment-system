@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">گزارش سود سرمایه‌گذاران</h2>
                    <a href="{{ route('cars.show', $carSale->car) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        بازگشت به خودرو
                    </a>
                </div>

                <!-- اطلاعات فروش -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="text-sm text-gray-600">خودرو</div>
                        <div class="text-lg font-bold">{{ $carSale->car->title }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">قیمت فروش</div>
                        <div class="text-lg font-bold text-green-600">{{ number_format($carSale->selling_price) }} ریال</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">سود کل</div>
                        <div class="text-lg font-bold text-purple-600">{{ number_format($carSale->total_profit) }} ریال</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">تاریخ فروش</div>
                        <div class="text-lg font-bold">{{ $carSale->sale_date }}</div>
                    </div>
                </div>

                <!-- جدول سود سرمایه‌گذاران -->
                <h3 class="text-xl font-semibold mb-4">توزیع سود بین سرمایه‌گذاران</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ردیف</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سرمایه‌گذار</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مبلغ سرمایه‌گذاری</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">درصد مشارکت</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سود</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مجموع دریافتی</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($profits as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('investors.show', $item['investor']) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $item['investor']->full_name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item['invested_amount']) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        {{ $item['percentage'] }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600">
                                    {{ number_format($item['profit']) }} ریال
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-purple-600">
                                    {{ number_format($item['total_return']) }} ریال
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-left font-bold">جمع کل:</td>
                                <td class="px-6 py-4 font-bold">{{ number_format(collect($profits)->sum('invested_amount')) }} ریال</td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4 font-bold text-green-600">{{ number_format(collect($profits)->sum('profit')) }} ریال</td>
                                <td class="px-6 py-4 font-bold text-purple-600">{{ number_format(collect($profits)->sum('total_return')) }} ریال</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- دکمه چاپ گزارش -->
                <div class="flex justify-end mt-6">
                    <button onclick="window.print()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                        <svg class="h-5 w-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        چاپ گزارش
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection