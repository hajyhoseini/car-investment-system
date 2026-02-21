@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">جزئیات سرمایه‌گذاری</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('investments.edit', $investment) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        <a href="{{ route('investments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اطلاعات خودرو -->
                    <div class="bg-blue-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">اطلاعات خودرو</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">عنوان:</span>
                                <div class="text-lg font-medium">{{ $investment->car->title }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">برند و مدل:</span>
                                <div class="text-lg font-medium">{{ $investment->car->brand }} {{ $investment->car->model }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">سال ساخت:</span>
                                <div class="text-lg font-medium">{{ $investment->car->year }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">قیمت خرید:</span>
                                <div class="text-lg font-bold text-blue-600">{{ number_format($investment->car->purchase_price) }} ریال</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">وضعیت:</span>
                                <div class="mt-1">
                                    @if($investment->car->status == 'available')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">موجود</span>
                                    @elseif($investment->car->status == 'sold')
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">فروخته شده</span>
                                    @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">رزرو</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- اطلاعات سرمایه‌گذار -->
                    <div class="bg-green-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-green-600">اطلاعات سرمایه‌گذار</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">نام و نام خانوادگی:</span>
                                <div class="text-lg font-medium">{{ $investment->investor->full_name }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">کد ملی:</span>
                                <div class="text-lg font-medium">{{ $investment->investor->national_code }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تلفن:</span>
                                <div class="text-lg font-medium">{{ $investment->investor->phone }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">ایمیل:</span>
                                <div class="text-lg font-medium">{{ $investment->investor->email ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- اطلاعات سرمایه‌گذاری -->
                    <div class="bg-purple-50 p-6 rounded-xl md:col-span-2">
                        <h3 class="text-lg font-semibold mb-4 text-purple-600">جزئیات سرمایه‌گذاری</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">مبلغ سرمایه‌گذاری:</span>
                                <div class="text-xl font-bold text-purple-600">{{ number_format($investment->amount) }} ریال</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">درصد مشارکت:</span>
                                <div class="text-xl font-bold text-purple-600">{{ $investment->percentage }}%</div>
                            </div>
                         <!-- در بخش نمایش تاریخ -->
<div>
    <span class="text-sm text-gray-600">تاریخ سرمایه‌گذاری:</span>
    <div class="text-xl font-bold text-purple-600">{{ $investment->jalali_date ?? $investment->investment_date }}</div>
</div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ثبت در سیستم:</span>
                                <div class="text-xl font-bold text-purple-600">{{ $investment->created_at->format('Y/m/d') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection