@extends('layouts.app')

@section('content')
<div class="py-10 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            <div class="p-6 md:p-8">
                <!-- Header + دکمه ایجاد -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">مدیریت سرمایه‌گذاری‌ها</h2>

                    @can('create investments')
                        <a href="{{ route('investments.create') }}"
                           class="inline-flex items-center gap-x-2 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            سرمایه‌گذاری جدید
                        </a>
                    @endcan
                </div>

                <!-- کارت‌های آماری -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-blue-700 font-medium mb-1">کل مبلغ سرمایه‌گذاری شده</div>
                        <div class="text-3xl font-bold text-blue-800">
                            {{ number_format($investments->sum('amount')) }} <span class="text-xl">ریال</span>
                        </div>
                    </div>

                    <div class="bg-green-50 border border-green-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-green-700 font-medium mb-1">تعداد سرمایه‌گذاری‌ها</div>
                        <div class="text-3xl font-bold text-green-800">{{ $investments->count() }}</div>
                    </div>

                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-purple-700 font-medium mb-1">میانگین هر سرمایه‌گذاری</div>
                        <div class="text-3xl font-bold text-purple-800">
                            {{ number_format($investments->avg('amount') ?? 0) }} <span class="text-xl">ریال</span>
                        </div>
                    </div>
                </div>

                <!-- جدول -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">خودرو</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">سرمایه‌گذار</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">مبلغ سرمایه‌گذاری</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">درصد مشارکت</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700 hidden md:table-cell">تاریخ سرمایه‌گذاری</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($investments as $investment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-sm">
                                        <a href="{{ route('cars.show', $investment->car) }}"
                                           class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                            {{ $investment->car->title ?? '—' }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <a href="{{ route('investors.show', $investment->investor) }}"
                                           class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                            {{ $investment->investor->full_name ?? '—' }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold text-green-700">
                                        {{ number_format($investment->amount) }} ریال
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ number_format($investment->percentage ?? 0, 2) }}%
                                        </span>
                                    </td>
                                    {{-- در بخش نمایش تاریخ --}}
<td class="px-6 py-4 whitespace-nowrap">
    {{ $investment->jalali_date ?? $investment->investment_date }}
</td>
                                    <td class="px-4 py-4 text-sm font-medium">
                                        <div class="flex items-center gap-x-3">
                                            @can('view investments')
                                                <a href="{{ route('investments.show', $investment) }}"
                                                   class="text-indigo-600 hover:text-indigo-800 transition" title="نمایش جزئیات">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @endcan

                                            @can('edit investments')
                                                <a href="{{ route('investments.edit', $investment) }}"
                                                   class="text-green-600 hover:text-green-800 transition" title="ویرایش">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm">هنوز هیچ سرمایه‌گذاری ثبت نشده است.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $investments->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection