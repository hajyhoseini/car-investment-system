@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">جزئیات حساب: {{ $account->name }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('accounts.edit', $account) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        <a href="{{ route('accounts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-blue-800">اطلاعات حساب</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">نام حساب:</span>
                                <span class="font-medium mr-2">{{ $account->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">نوع حساب:</span>
                                <span class="font-medium mr-2">{{ $account->type_label }}</span>
                            </div>
                            @if($account->type == 'bank' && $account->bank_name)
                            <div>
                                <span class="text-sm text-gray-600">نام بانک:</span>
                                <span class="font-medium mr-2">{{ $account->bank_name }}</span>
                            </div>
                            @endif
                            @if($account->account_number)
                            <div>
                                <span class="text-sm text-gray-600">شماره حساب:</span>
                                <span class="font-medium mr-2">{{ $account->account_number }}</span>
                            </div>
                            @endif
                            @if($account->card_number)
                            <div>
                                <span class="text-sm text-gray-600">شماره کارت:</span>
                                <span class="font-medium mr-2">{{ $account->card_number }}</span>
                            </div>
                            @endif
                            @if($account->sheba_number)
                            <div>
                                <span class="text-sm text-gray-600">شماره شبا:</span>
                                <span class="font-medium mr-2">{{ $account->sheba_number }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-green-800">اطلاعات مالی</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">موجودی فعلی:</span>
                                <span class="text-xl font-bold text-green-700 mr-2">{{ number_format($account->balance) }} {{ $account->currency }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">وضعیت:</span>
                                @if($account->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">فعال</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">غیرفعال</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ایجاد:</span>
                                <span class="font-medium mr-2">{{ $account->created_at->format('Y/m/d') }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">آخرین بروزرسانی:</span>
                                <span class="font-medium mr-2">{{ $account->updated_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($account->description)
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">توضیحات</h3>
                        <p class="text-gray-700">{{ $account->description }}</p>
                    </div>
                    @endif
                </div>

                <!-- آمار تراکنش‌ها -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-purple-800">تراکنش‌های ورودی</h3>
                        <div class="text-2xl font-bold text-purple-700">{{ number_format($incomingTotal ?? 0) }} ریال</div>
                        <div class="text-sm text-gray-600 mt-2">تعداد: {{ $account->incomingTransactions()->count() }} تراکنش</div>
                    </div>
                    <div class="bg-orange-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-orange-800">تراکنش‌های خروجی</h3>
                        <div class="text-2xl font-bold text-orange-700">{{ number_format($outgoingTotal ?? 0) }} ریال</div>
                        <div class="text-sm text-gray-600 mt-2">تعداد: {{ $account->outgoingTransactions()->count() }} تراکنش</div>
                    </div>
                </div>

                <!-- دکمه حذف -->
                @can('delete accounts')
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('آیا از حذف این حساب اطمینان دارید؟ با حذف حساب، تمام تراکنش‌های مرتبط نیز حذف خواهند شد.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            حذف حساب
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection