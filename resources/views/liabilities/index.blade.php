@extends('layouts.app')

@section('content')
<div class="py-10 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            <div class="p-6 md:p-8">
                <!-- هدر + دکمه ایجاد -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">مدیریت تعهدات</h2>

                    @can('create liabilities')
                        <a href="{{ route('liabilities.create') }}"
                           class="inline-flex items-center gap-x-2 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            ثبت تعهد جدید
                        </a>
                    @endcan
                </div>

                <!-- کارت‌های خلاصه -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
                    <div class="bg-red-50 border border-red-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-red-700 font-medium mb-1">جمع کل تعهدات</div>
                        <div class="text-3xl font-bold text-red-800">
                            {{ number_format($liabilities->sum('amount')) }}
                            <span class="text-xl">ریال</span>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-amber-700 font-medium mb-1">باقیمانده پرداخت‌نشده</div>
                        <div class="text-3xl font-bold text-amber-800">
                            {{ number_format($liabilities->sum('remaining_amount')) }}
                            <span class="text-xl">ریال</span>
                        </div>
                    </div>

                    <div class="bg-orange-50 border border-orange-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-orange-700 font-medium mb-1">تعداد کل تعهدات</div>
                        <div class="text-3xl font-bold text-orange-800">{{ $liabilities->count() }}</div>
                    </div>

                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-purple-700 font-medium mb-1">سررسید گذشته</div>
                        <div class="text-3xl font-bold text-purple-800">
                            {{ $liabilities->filter(fn($l) => $l->isOverdue())->count() }}
                        </div>
                    </div>
                </div>

                <!-- سه ستون: بدهی‌ها - چک‌ها - اقساط -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- بدهی‌ها -->
                    <div>
                        <h3 class="text-xl font-semibold text-red-700 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            بدهی‌ها
                        </h3>

                        @if($liabilities->where('type', 'debt')->isEmpty())
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-8 text-center text-gray-500">
                                هنوز بدهی ثبت نشده است.
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($liabilities->where('type', 'debt') as $liability)
                                    <div class="bg-white border border-red-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-red-900">{{ $liability->creditor_name }}</h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $liability->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-xl font-bold text-red-700">
                                                    {{ number_format($liability->remaining_amount) }}
                                                    <span class="text-base font-normal">ریال</span>
                                                </div>
                                                <div class="text-xs text-gray-600 mt-1">
                                                    از {{ number_format($liability->amount) }} ریال
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-3">
                                            <div class="text-xs text-gray-600">
                                                سررسید: {{ $liability->due_date ? $liability->due_date->format('Y/m/d') : '—' }}
                                            </div>

                                            <div class="flex items-center gap-x-3">
                                                @if($liability->status == 'pending')
                                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">در انتظار</span>
                                                @elseif($liability->status == 'paid')
                                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">پرداخت شده</span>
                                                @elseif($liability->status == 'overdue')
                                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">سررسید گذشته</span>
                                                @endif

                                                @can('edit liabilities')
                                                    <a href="{{ route('liabilities.edit', $liability) }}"
                                                       class="text-blue-600 hover:text-blue-800 transition"
                                                       title="ویرایش">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- چک‌ها -->
                    <div>
                        <h3 class="text-xl font-semibold text-blue-700 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            چک‌ها
                        </h3>

                        @if($liabilities->where('type', 'check')->isEmpty())
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-8 text-center text-gray-500">
                                هنوز چکی ثبت نشده است.
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($liabilities->where('type', 'check') as $liability)
                                    <div class="bg-white border border-blue-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-blue-900">{{ $liability->creditor_name }}</h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $liability->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-xl font-bold text-blue-700">
                                                    {{ number_format($liability->remaining_amount) }}
                                                    <span class="text-base font-normal">ریال</span>
                                                </div>
                                                <div class="text-xs text-gray-600 mt-1">
                                                    از {{ number_format($liability->amount) }} ریال
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-3">
                                            <div class="text-xs text-gray-600">
                                                سررسید: {{ $liability->due_date ? $liability->due_date->format('Y/m/d') : '—' }}
                                            </div>

                                            <div class="flex items-center gap-x-3">
                                                @if($liability->status == 'pending')
                                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">در انتظار</span>
                                                @elseif($liability->status == 'paid')
                                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">پرداخت شده</span>
                                                @elseif($liability->status == 'overdue')
                                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">سررسید گذشته</span>
                                                @endif

                                                @can('edit liabilities')
                                                    <a href="{{ route('liabilities.edit', $liability) }}"
                                                       class="text-blue-600 hover:text-blue-800 transition"
                                                       title="ویرایش">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- اقساط -->
                    <div>
                        <h3 class="text-xl font-semibold text-green-700 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            اقساط
                        </h3>

                        @if($liabilities->where('type', 'installment')->isEmpty())
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-8 text-center text-gray-500">
                                هنوز قسطی ثبت نشده است.
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($liabilities->where('type', 'installment') as $liability)
                                    <div class="bg-white border border-green-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-green-900">{{ $liability->creditor_name }}</h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $liability->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-xl font-bold text-green-700">
                                                    {{ number_format($liability->remaining_amount) }}
                                                    <span class="text-base font-normal">ریال</span>
                                                </div>
                                                <div class="text-xs text-gray-600 mt-1">
                                                    از {{ number_format($liability->amount) }} ریال
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-3">
                                            <div class="text-xs text-gray-600">
                                                سررسید: {{ $liability->due_date ? $liability->due_date->format('Y/m/d') : '—' }}
                                            </div>

                                            <div class="flex items-center gap-x-3">
                                                @if($liability->status == 'pending')
                                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">در انتظار</span>
                                                @elseif($liability->status == 'paid')
                                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">پرداخت شده</span>
                                                @elseif($liability->status == 'overdue')
                                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">سررسید گذشته</span>
                                                @endif

                                                @can('edit liabilities')
                                                    <a href="{{ route('liabilities.edit', $liability) }}"
                                                       class="text-blue-600 hover:text-blue-800 transition"
                                                       title="ویرایش">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection