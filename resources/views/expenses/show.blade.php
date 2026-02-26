@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">جزئیات هزینه</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('expenses.edit', $expense) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        <a href="{{ route('expenses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اطلاعات اصلی -->
                    <div class="bg-orange-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-orange-800">اطلاعات هزینه</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">عنوان:</span>
                                <div class="font-medium">{{ $expense->title }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">دسته‌بندی:</span>
                                <div class="font-medium">{{ $expense->category_label }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مبلغ:</span>
                                <div class="text-xl font-bold text-orange-700">{{ number_format($expense->amount) }} ریال</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ هزینه:</span>
                                <div class="font-medium">{{ $expense->expense_date }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- اطلاعات مرتبط -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-blue-800">اطلاعات مرتبط</h3>
                        <div class="space-y-3">
                            @if($expense->car)
                            <div>
                                <span class="text-sm text-gray-600">خودرو:</span>
                                <div class="font-medium">
                                    <a href="{{ route('cars.show', $expense->car) }}" class="text-blue-600 hover:underline">
                                        {{ $expense->car->title }}
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            @if($expense->account)
                            <div>
                                <span class="text-sm text-gray-600">حساب پرداخت:</span>
                                <div class="font-medium">{{ $expense->account->name }}</div>
                            </div>
                            @endif
                            
                            @if($expense->paymentMethod)
                            <div>
                                <span class="text-sm text-gray-600">روش پرداخت:</span>
                                <div class="font-medium">{{ $expense->paymentMethod->name }}</div>
                            </div>
                            @endif
                            
                            <div>
                                <span class="text-sm text-gray-600">ثبت شده توسط:</span>
                                <div class="font-medium">{{ $expense->creator->name }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ثبت:</span>
                                <div class="font-medium">{{ $expense->created_at->format('Y/m/d H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- توضیحات -->
                    @if($expense->description)
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">توضیحات</h3>
                        <p class="text-gray-700">{{ $expense->description }}</p>
                    </div>
                    @endif

                    <!-- تصویر رسید -->
                    @if($expense->receipt_image)
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">تصویر رسید</h3>
                        <img src="{{ asset('storage/' . $expense->receipt_image) }}" alt="رسید" class="max-w-md rounded-lg shadow-lg">
                    </div>
                    @endif

                    <!-- یادداشت‌ها -->
                    @if($expense->notes)
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">یادداشت‌ها</h3>
                        <p class="text-gray-700">{{ $expense->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection