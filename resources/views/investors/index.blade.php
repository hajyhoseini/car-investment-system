@extends('layouts.app')

@section('content')
<div class="py-10 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            <div class="p-6 md:p-8">
                <!-- Header + دکمه ایجاد -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">مدیریت سرمایه‌گذاران</h2>

                    @can('create investors')
                        <a href="{{ route('investors.create') }}"
                           class="inline-flex items-center gap-x-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            سرمایه‌گذار جدید
                        </a>
                    @endcan
                </div>

                <!-- کارت‌های آماری -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-blue-700 font-medium mb-1">تعداد سرمایه‌گذاران</div>
                        <div class="text-3xl font-bold text-blue-800">{{ $investors->total() }}</div>
                    </div>

                    <div class="bg-green-50 border border-green-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-green-700 font-medium mb-1">کل سرمایه‌گذاری</div>
                        <div class="text-3xl font-bold text-green-800">
                            {{ fa_currency($investors->sum('total_invested')) }} <span class="text-xl">ریال</span>
                        </div>
                    </div>

                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-purple-700 font-medium mb-1">میانگین سرمایه هر نفر</div>
                        <div class="text-3xl font-bold text-purple-800">
                            {{ fa_currency($investors->avg('total_invested') ?? 0) }} <span class="text-xl">ریال</span>
                        </div>
                    </div>
                </div>

                <!-- جدول -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">نام و نام خانوادگی</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">کد ملی</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">تلفن</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700 hidden md:table-cell">ایمیل</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">کل سرمایه</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">تعداد سرمایه‌گذاری</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($investors as $investor)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $investor->full_name }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $investor->national_code }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $investor->phone }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600 hidden md:table-cell">{{ $investor->email ?? '—' }}</td>
                                    <td class="px-4 py-4 text-sm font-bold text-green-700">
                                        {{ fa_currency($investor->total_invested ?? 0) }} ریال
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $investor->investments_count ?? $investor->investments->count() }} مورد
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium">
                                        <div class="flex items-center gap-x-3">
                                            @can('view investors')
                                                <a href="{{ route('investors.show', $investor) }}" class="text-blue-600 hover:text-blue-800 transition" title="نمایش جزئیات">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @endcan

                                            @can('edit investors')
                                                <a href="{{ route('investors.edit', $investor) }}" class="text-green-600 hover:text-green-800 transition" title="ویرایش">
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
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4a2 2 0 00-2 2v2m-4-6H4" />
                                        </svg>
                                        <p class="mt-2 text-sm">هیچ سرمایه‌گذاری ثبت نشده است.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $investors->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection