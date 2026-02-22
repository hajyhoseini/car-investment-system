@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">جزئیات تعهد</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('liabilities.edit', $liability) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        <a href="{{ route('liabilities.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اطلاعات اصلی -->
                    <div class="bg-red-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-red-600">اطلاعات اصلی</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="text-sm text-gray-600">نوع تعهد:</span>
                                <div class="text-lg font-medium">
                                    @if($liability->type == 'debt')
                                        بدهی
                                    @elseif($liability->type == 'check')
                                        چک
                                    @elseif($liability->type == 'installment')
                                        قسط
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">نام طلبکار:</span>
                                <div class="text-lg font-medium">{{ $liability->creditor_name }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مبلغ کل:</span>
                                <div class="text-xl font-bold text-red-600">{{ fa_currency($liability->amount) }} ریال</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مبلغ باقی‌مانده:</span>
                                <div class="text-xl font-bold text-orange-600">{{ fa_currency($liability->remaining_amount) }} ریال</div>
                            </div>
                        </div>
                    </div>

                    <!-- وضعیت و تاریخ -->
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-red-600">وضعیت و تاریخ</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="text-sm text-gray-600">وضعیت:</span>
                                <div class="mt-1">
                                    @if($liability->status == 'pending')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">در انتظار</span>
                                    @elseif($liability->status == 'paid')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">پرداخت شده</span>
                                    @elseif($liability->status == 'overdue')
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">سررسید گذشته</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ سررسید:</span>
                                <div class="text-lg font-medium">{{ $liability->due_date->format('Y/m/d') }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">وضعیت سررسید:</span>
                                <div class="text-lg font-medium">
                                    @if($liability->isOverdue())
                                        <span class="text-red-600">⚠️ سررسید گذشته</span>
                                    @else
                                        <span class="text-green-600">✅ در محدوده زمانی</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- توضیحات -->
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-red-600">توضیحات</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $liability->description ?? 'توضیحاتی ثبت نشده است.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection