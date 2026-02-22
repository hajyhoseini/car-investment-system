@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">جزئیات خودرو: {{ $car->title }}</h2>
                    <div class="flex gap-2">
                        @can('edit cars')
                        <a href="{{ route('cars.edit', $car) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        @endcan
                        @can('sell cars')
                        @if($car->status == 'available')
                        <a href="{{ route('cars.sell', $car) }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            فروش خودرو
                        </a>
                        @endif
                        @endcan
                        <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                <!-- خلاصه وضعیت خودرو -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">وضعیت</div>
                        <div class="text-lg font-bold">
                            @if($car->status == 'available')
                                <span class="text-green-600">موجود</span>
                            @elseif($car->status == 'sold')
                                <span class="text-red-600">فروخته شده</span>
                            @else
                                <span class="text-yellow-600">رزرو</span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">قیمت خرید</div>
                        <div class="text-lg font-bold text-blue-600">{{ fa_currency($car->purchase_price) }} ریال</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل سرمایه‌گذاری</div>
                        <div class="text-lg font-bold text-green-600">{{ fa_currency($car->total_invested) }} ریال</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">تعداد سرمایه‌گذاران</div>
                        <div class="text-lg font-bold text-purple-600">{{ $car->investments->count() }} نفر</div>
                    </div>
                </div>

                <!-- اطلاعات اصلی خودرو -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- مشخصات فنی -->
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">مشخصات فنی</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">برند:</span>
                                <div class="text-base font-medium">{{ $car->brand }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مدل:</span>
                                <div class="text-base font-medium">{{ $car->model }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">سال ساخت:</span>
                                <div class="text-base font-medium">{{ $car->year }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">رنگ:</span>
                                <div class="text-base font-medium">{{ $car->color ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">کارکرد:</span>
                                <div class="text-base font-medium">{{ fa_currency($car->kilometers) }} کیلومتر</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">نوع سوخت:</span>
                                <div class="text-base font-medium">{{ $car->fuel_type }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">گیربکس:</span>
                                <div class="text-base font-medium">{{ $car->transmission }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ خرید:</span>
                                <div class="text-base font-medium">{{ $car->jalali_purchase_date ?? $car->purchase_date }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- توضیحات -->
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">توضیحات</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $car->description ?? 'توضیحاتی ثبت نشده است.' }}</p>
                    </div>
                </div>

                <!-- لیست سرمایه‌گذاران این خودرو -->
                <div class="mt-8">
                    <h3 class="text-xl font-bold mb-4">سرمایه‌گذاران این خودرو</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">سرمایه‌گذار</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">مبلغ سرمایه‌گذاری</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">درصد</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاریخ سرمایه‌گذاری</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($car->investments as $investment)
                                <tr>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('investors.show', $investment->investor) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $investment->investor->full_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">{{ fa_currency($investment->amount) }} ریال</td>
                                    <td class="px-6 py-4">{{ $investment->percentage }}%</td>
                                    <td class="px-6 py-4">{{ $investment->jalali_date ?? $investment->investment_date }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        هیچ سرمایه‌گذاری برای این خودرو ثبت نشده است.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="1" class="px-6 py-3 text-left font-bold">جمع کل:</td>
                                    <td class="px-6 py-3 font-bold text-green-600">{{ fa_currency($car->investments->sum('amount')) }} ریال</td>
                                    <td class="px-6 py-3 font-bold text-blue-600">{{ fa_currency($car->investments->sum('percentage'), 2) }}%</td>
                                    <td class="px-6 py-3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- اگر خودرو فروخته شده، اطلاعات فروش رو نشون بده -->
                @if($car->status == 'sold' && $car->sales->isNotEmpty())
                <div class="mt-8">
                    <h3 class="text-xl font-bold mb-4">اطلاعات فروش</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @php $sale = $car->sales->first(); @endphp
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">قیمت فروش</div>
                            <div class="text-lg font-bold text-purple-600">{{ fa_currency($sale->selling_price) }} ریال</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">سود کل</div>
                            <div class="text-lg font-bold text-green-600">{{ fa_currency($sale->total_profit) }} ریال</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">تاریخ فروش</div>
                            <div class="text-lg font-bold">{{ $sale->jalali_sale_date ?? $sale->sale_date }}</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">خریدار</div>
                            <div class="text-lg font-bold">{{ $sale->buyer_name }}</div>
                        </div>
                    </div>
                    <div class="mt-4 text-left">
                        <a href="{{ route('car-sales.profits', $sale) }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            مشاهده گزارش سود سرمایه‌گذاران
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection