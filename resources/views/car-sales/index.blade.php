@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">مدیریت فروش خودروها</h2>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- آمار فروش -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">تعداد فروش</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $sales->total() }}</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل فروش</div>
                        <div class="text-2xl font-bold text-green-600">{{ fa_currency($sales->sum('selling_price')) }}</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل سود</div>
                        <div class="text-2xl font-bold text-purple-600">{{ fa_currency($sales->sum('total_profit')) }}</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">میانگین سود</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ fa_currency($sales->avg('total_profit')) }}</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
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
                            @forelse($sales as $index => $sale)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sales->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('cars.show', $sale->car) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $sale->car->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ fa_currency($sale->car->purchase_price) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600">{{ fa_currency($sale->selling_price) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-purple-600">{{ fa_currency($sale->total_profit) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ fa_number(($sale->total_profit / $sale->car->purchase_price) * 100, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ jalali_date($sale->sale_date) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $sale->buyer_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <!-- دکمه گزارش سود سرمایه‌گذاران -->
                                        <a href="{{ route('car-sales.profits', $sale) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition" 
                                           title="گزارش سود سرمایه‌گذاران">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </a>

                                        <!-- دکمه ویرایش (فقط برای کاربران مجاز) -->
                                        @can('edit sales')
                                            <a href="{{ route('car-sales.edit', $sale) }}" 
                                               class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition" 
                                               title="ویرایش">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endcan

                                        <!-- دکمه حذف (فقط برای ادمین) -->
                                        @can('delete sales')
                                            <form action="{{ route('car-sales.destroy', $sale) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('آیا از حذف این فروش اطمینان دارید؟ با حذف فروش، وضعیت خودرو به "موجود" تغییر خواهد کرد.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition" 
                                                        title="حذف">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm">هیچ فروشی ثبت نشده است.</p>
                                </td>
                            </tr>
                            @endforelse
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