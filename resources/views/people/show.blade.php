@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">اطلاعات شخص: {{ $person->full_name }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('people.edit', $person) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        <a href="{{ route('people.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- اطلاعات شخصی -->
                    <div class="bg-gray-50 p-6 rounded-xl col-span-2">
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">اطلاعات شخصی</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">نام کامل:</span>
                                <div class="text-base font-medium">{{ $person->full_name }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">نوع شخص:</span>
                                <div class="text-base font-medium">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        @if($person->type == 'buyer') bg-blue-100 text-blue-800
                                        @elseif($person->type == 'seller') bg-green-100 text-green-800
                                        @elseif($person->type == 'creditor') bg-yellow-100 text-yellow-800
                                        @elseif($person->type == 'debtor') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $person->type_label }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">کد ملی:</span>
                                <div class="text-base font-medium">{{ $person->national_code ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تلفن:</span>
                                <div class="text-base font-medium">{{ $person->phone ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">ایمیل:</span>
                                <div class="text-base font-medium">{{ $person->email ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">کد پستی:</span>
                                <div class="text-base font-medium">{{ $person->postal_code ?? '—' }}</div>
                            </div>
                            @if($person->is_legal)
                            <div>
                                <span class="text-sm text-gray-600">نام شرکت:</span>
                                <div class="text-base font-medium">{{ $person->company_name ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">کد اقتصادی:</span>
                                <div class="text-base font-medium">{{ $person->economic_code ?? '—' }}</div>
                            </div>
                            @endif
                            <div>
                                <span class="text-sm text-gray-600">تاریخ تولد:</span>
                                <div class="text-base font-medium">{{ $person->birth_date ? $person->birth_date->format('Y/m/d') : '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- تصویر -->
                    <div class="bg-gray-50 p-6 rounded-xl text-center">
                        <img src="{{ $person->avatar_url }}" alt="{{ $person->full_name }}" 
                             class="w-32 h-32 rounded-full object-cover mx-auto mb-4">
                        <h4 class="font-bold">{{ $person->full_name }}</h4>
                    </div>

                    <!-- آدرس -->
                    <div class="bg-gray-50 p-6 rounded-xl col-span-2">
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">آدرس</h3>
                        <p class="text-gray-700">{{ $person->address ?? 'آدرسی ثبت نشده است.' }}</p>
                    </div>

                    <!-- توضیحات -->
                    <div class="bg-gray-50 p-6 rounded-xl col-span-2">
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">توضیحات</h3>
                        <p class="text-gray-700">{{ $person->description ?? 'توضیحاتی ثبت نشده است.' }}</p>
                    </div>

                   <!-- خریدهای انجام شده -->
@if($person->purchases->count() > 0)
<div class="bg-gray-50 p-6 rounded-xl col-span-3">
    <h3 class="text-lg font-semibold mb-4 text-green-600">خریدهای انجام شده</h3>
    <table class="min-w-full">
        <thead>
            <tr class="border-b">
                <th class="text-right py-2">خودرو</th>
                <th class="text-right py-2">قیمت فروش</th>
                <th class="text-right py-2">تاریخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->purchases as $purchase)
            <tr class="border-b hover:bg-gray-100">
                <td class="py-2">
                    @if($purchase->car)
                    <a href="{{ route('cars.show', $purchase->car) }}" class="text-blue-600 hover:underline">
                        {{ $purchase->car->title }}
                    </a>
                    @else
                    {{ $purchase->buyer_name ?? 'خودرو حذف شده' }}
                    @endif
                </td>
                <td class="py-2">{{ number_format($purchase->selling_price) }} ریال</td>
                <td class="py-2">{{ $purchase->sale_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- تعهدات مرتبط -->
@if($person->liabilities->count() > 0)
<div class="bg-gray-50 p-6 rounded-xl col-span-3">
    <h3 class="text-lg font-semibold mb-4 text-red-600">تعهدات مرتبط</h3>
    <table class="min-w-full">
        <thead>
            <tr class="border-b">
                <th class="text-right py-2">نوع</th>
                <th class="text-right py-2">مبلغ</th>
                <th class="text-right py-2">باقی‌مانده</th>
                <th class="text-right py-2">سررسید</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->liabilities as $liability)
            <tr class="border-b hover:bg-gray-100">
                <td class="py-2">
                    @if($liability->type == 'debt') بدهی
                    @elseif($liability->type == 'check') چک
                    @elseif($liability->type == 'installment') قسط
                    @endif
                </td>
                <td class="py-2">{{ number_format($liability->amount) }} ریال</td>
                <td class="py-2">{{ number_format($liability->remaining_amount) }} ریال</td>
                <td class="py-2">{{ $liability->due_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection