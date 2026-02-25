@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">
                        جزئیات تراکنش: {{ $transaction->transaction_number }}
                    </h2>
                    <div class="flex gap-2">
                        <a href="{{ route('transactions.edit', $transaction) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- اطلاعات اصلی -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-blue-800">اطلاعات اصلی</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">شماره تراکنش:</span>
                                <span class="font-medium">{{ $transaction->transaction_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">نوع تراکنش:</span>
                                <span class="px-2 py-1 rounded-full text-xs 
                                    @if($transaction->type == 'income') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $transaction->type_label }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">مبلغ:</span>
                                <span class="text-lg font-bold text-blue-700">{{ number_format($transaction->amount) }} ریال</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">تاریخ تراکنش:</span>
                                <span class="font-medium">{{ $transaction->transaction_date }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">وضعیت:</span>
                                <span class="px-2 py-1 rounded-full text-xs 
                                    @if($transaction->status == 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $transaction->status_label }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- اطلاعات حساب‌ها -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-green-800">اطلاعات حساب‌ها</h3>
                        <div class="space-y-3">
                            @if($transaction->from_account_id)
                            <div>
                                <span class="text-sm text-gray-600">حساب مبدأ:</span>
                                <div class="font-medium">{{ $transaction->fromAccount->name ?? '—' }}</div>
                                <div class="text-xs text-gray-500">موجودی: {{ number_format($transaction->fromAccount->balance ?? 0) }} ریال</div>
                            </div>
                            @endif
                            
                            @if($transaction->to_account_id)
                            <div>
                                <span class="text-sm text-gray-600">حساب مقصد:</span>
                                <div class="font-medium">{{ $transaction->toAccount->name ?? '—' }}</div>
                                <div class="text-xs text-gray-500">موجودی: {{ number_format($transaction->toAccount->balance ?? 0) }} ریال</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- اطلاعات شخص و روش پرداخت -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-purple-800">شخص و روش پرداخت</h3>
                        <div class="space-y-3">
                            @if($transaction->person_id)
                            <div>
                                <span class="text-sm text-gray-600">شخص مرتبط:</span>
                                <div class="font-medium">{{ $transaction->person->full_name ?? '—' }}</div>
                            </div>
                            @endif
                            
                            @if($transaction->payment_method_id)
                            <div>
                                <span class="text-sm text-gray-600">روش پرداخت:</span>
                                <div class="font-medium">{{ $transaction->paymentMethod->name ?? '—' }}</div>
                            </div>
                            @endif

                            @if($transaction->check_number)
                            <div>
                                <span class="text-sm text-gray-600">شماره چک:</span>
                                <div class="font-medium">{{ $transaction->check_number }}</div>
                            </div>
                            @endif

                            @if($transaction->check_date)
                            <div>
                                <span class="text-sm text-gray-600">تاریخ چک:</span>
                                <div class="font-medium">{{ $transaction->check_date }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- اطلاعات تکمیلی -->
                    <div class="bg-orange-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-orange-800">اطلاعات تکمیلی</h3>
                        <div class="space-y-3">
                            @if($transaction->asset_id)
                            <div>
                                <span class="text-sm text-gray-600">دارایی مرتبط:</span>
                                <div class="font-medium">{{ $transaction->asset->name ?? '—' }}</div>
                            </div>
                            @endif
                            
                            <div>
                                <span class="text-sm text-gray-600">ثبت شده توسط:</span>
                                <div class="font-medium">{{ $transaction->creator->name ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ثبت:</span>
                                <div class="font-medium">{{ $transaction->created_at->format('Y/m/d H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- توضیحات -->
                    @if($transaction->description)
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">توضیحات</h3>
                        <p class="text-gray-700">{{ $transaction->description }}</p>
                    </div>
                    @endif

                    <!-- یادداشت‌ها -->
                    @if($transaction->notes)
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">یادداشت‌ها</h3>
                        <p class="text-gray-700">{{ $transaction->notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- دکمه حذف -->
                @can('delete transactions')
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('آیا از حذف این تراکنش اطمینان دارید؟ این عملیات قابل برگشت نیست.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            حذف تراکنش
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection