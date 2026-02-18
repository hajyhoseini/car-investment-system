@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">مدیریت سرمایه‌گذاری‌ها</h2>
                    <a href="{{ route('investments.create') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                        <svg class="h-5 w-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        سرمایه‌گذاری جدید
                    </a>
                </div>

                <!-- آمار سرمایه‌گذاری‌ها -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل سرمایه‌گذاری‌ها</div>
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($investments->sum('amount')) }} ریال</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">تعداد سرمایه‌گذاری‌ها</div>
                        <div class="text-2xl font-bold text-green-600">{{ $investments->count() }}</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">میانگین هر سرمایه‌گذاری</div>
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($investments->avg('amount')) }} ریال</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">خودرو</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سرمایه‌گذار</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مبلغ سرمایه‌گذاری</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">درصد مشارکت</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($investments as $investment)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('cars.show', $investment->car) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $investment->car->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('investors.show', $investment->investor) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $investment->investor->full_name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600">{{ number_format($investment->amount) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        {{ $investment->percentage }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $investment->investment_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('investments.edit', $investment) }}" class="text-green-600 hover:text-green-900 ml-2" title="ویرایش">
                                        <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $investments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection