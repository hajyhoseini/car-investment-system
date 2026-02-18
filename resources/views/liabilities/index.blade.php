@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">مدیریت تعهدات</h2>
                    <a href="{{ route('liabilities.create') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                        <svg class="h-5 w-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        ثبت تعهد جدید
                    </a>
                </div>

                <!-- خلاصه تعهدات -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-red-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل تعهدات</div>
                        <div class="text-2xl font-bold text-red-600">{{ number_format($liabilities->sum('amount')) }} ریال</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">باقی‌مانده</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ number_format($liabilities->sum('remaining_amount')) }} ریال</div>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">تعداد تعهدات</div>
                        <div class="text-2xl font-bold text-orange-600">{{ $liabilities->count() }}</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">سررسید گذشته</div>
                        <div class="text-2xl font-bold text-purple-600">{{ $liabilities->filter(function($item) { return $item->isOverdue(); })->count() }}</div>
                    </div>
                </div>

                <!-- دسته‌بندی تعهدات -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- بدهی‌ها -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3 flex items-center text-red-600">
                            <svg class="h-5 w-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            بدهی‌ها
                        </h3>
                        @foreach($liabilities->where('type', 'debt') as $liability)
                        <div class="border border-red-200 rounded-lg p-4 mb-3 hover:shadow-md transition bg-red-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold">{{ $liability->creditor_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $liability->description }}</p>
                                </div>
                                <div class="text-left">
                                    <div class="text-lg font-bold text-red-600">{{ number_format($liability->remaining_amount) }} ریال</div>
                                    <div class="text-xs text-gray-500">از {{ number_format($liability->amount) }} ریال</div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-xs text-gray-500">سررسید: {{ $liability->due_date }}</span>
                                <div>
                                    @if($liability->status == 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">در انتظار</span>
                                    @elseif($liability->status == 'paid')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">پرداخت شده</span>
                                    @elseif($liability->status == 'overdue')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">سررسید گذشته</span>
                                    @endif
                                    <a href="{{ route('liabilities.edit', $liability) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                        <svg class="h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- چک‌ها -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3 flex items-center text-blue-600">
                            <svg class="h-5 w-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            چک‌ها
                        </h3>
                        @foreach($liabilities->where('type', 'check') as $liability)
                        <div class="border border-blue-200 rounded-lg p-4 mb-3 hover:shadow-md transition bg-blue-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold">{{ $liability->creditor_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $liability->description }}</p>
                                </div>
                                <div class="text-left">
                                    <div class="text-lg font-bold text-blue-600">{{ number_format($liability->remaining_amount) }} ریال</div>
                                    <div class="text-xs text-gray-500">از {{ number_format($liability->amount) }} ریال</div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-xs text-gray-500">سررسید: {{ $liability->due_date }}</span>
                                <div>
                                    @if($liability->status == 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">در انتظار</span>
                                    @elseif($liability->status == 'paid')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">پرداخت شده</span>
                                    @elseif($liability->status == 'overdue')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">سررسید گذشته</span>
                                    @endif
                                    <a href="{{ route('liabilities.edit', $liability) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                        <svg class="h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- اقساط -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3 flex items-center text-green-600">
                            <svg class="h-5 w-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            اقساط
                        </h3>
                        @foreach($liabilities->where('type', 'installment') as $liability)
                        <div class="border border-green-200 rounded-lg p-4 mb-3 hover:shadow-md transition bg-green-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold">{{ $liability->creditor_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $liability->description }}</p>
                                </div>
                                <div class="text-left">
                                    <div class="text-lg font-bold text-green-600">{{ number_format($liability->remaining_amount) }} ریال</div>
                                    <div class="text-xs text-gray-500">از {{ number_format($liability->amount) }} ریال</div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-xs text-gray-500">سررسید: {{ $liability->due_date }}</span>
                                <div>
                                    @if($liability->status == 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">در انتظار</span>
                                    @elseif($liability->status == 'paid')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">پرداخت شده</span>
                                    @elseif($liability->status == 'overdue')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">سررسید گذشته</span>
                                    @endif
                                    <a href="{{ route('liabilities.edit', $liability) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                        <svg class="h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection