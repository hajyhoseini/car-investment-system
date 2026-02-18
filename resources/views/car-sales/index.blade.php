@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">مدیریت فروش خودروها</h2>
                </div>

                <!-- آمار فروش -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">تعداد فروش</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $sales->count() }}</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل فروش</div>
                        <div class="text-2xl font-bold text-green-600">{{ number_format($sales->sum('selling_price')) }} ریال</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل سود</div>
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($sales->sum('total_profit')) }} ریال</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">میانگین سود</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ number_format($sales->avg('total_profit')) }} ریال</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">خودرو</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قیمت خرید</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قیمت فروش</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سود کل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">درصد سود</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ فروش</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">خریدار</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sales as $sale)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('cars.show', $sale->car) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $sale->car->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($sale->car->purchase_price) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600">{{ number_format($sale->selling_price) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-purple-600">{{ number_format($sale->total_profit) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ number_format(($sale->total_profit / $sale->car->purchase_price) * 100, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $sale->sale_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $sale->buyer_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('car-sales.profits', $sale) }}" class="text-indigo-600 hover:text-indigo-900 ml-2" title="گزارش سود سرمایه‌گذاران">
                                        <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection