@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">اطلاعات سرمایه‌گذار: {{ $investor->full_name }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('investors.edit', $investor) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        <a href="{{ route('investors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اطلاعات شخصی -->
                    <div class="bg-green-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-green-600">اطلاعات شخصی</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="text-sm text-gray-600">نام و نام خانوادگی:</span>
                                <div class="text-lg font-medium">{{ $investor->full_name }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">کد ملی:</span>
                                <div class="text-lg font-medium">{{ $investor->national_code }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تلفن:</span>
                                <div class="text-lg font-medium">{{ $investor->phone }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">ایمیل:</span>
                                <div class="text-lg font-medium">{{ $investor->email ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">آدرس:</span>
                                <div class="text-lg font-medium">{{ $investor->address ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- اطلاعات مالی -->
                    <div class="bg-blue-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">اطلاعات مالی</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="text-sm text-gray-600">کل سرمایه‌گذاری:</span>
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($investor->total_invested) }} ریال</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تعداد سرمایه‌گذاری:</span>
                                <div class="text-2xl font-bold text-purple-600">{{ $investor->investments->count() }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">میانگین هر سرمایه‌گذاری:</span>
                                <div class="text-xl font-bold text-green-600">
                                    {{ $investor->investments->count() > 0 ? number_format($investor->total_invested / $investor->investments->count()) : 0 }} ریال
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- لیست سرمایه‌گذاری‌ها -->
                <div class="mt-8">
                    <h3 class="text-xl font-bold mb-4">سرمایه‌گذاری‌های انجام شده</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">خودرو</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">مبلغ سرمایه‌گذاری</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">درصد</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاریخ</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وضعیت خودرو</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($investor->investments as $investment)
                                <tr>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('cars.show', $investment->car) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $investment->car->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">{{ number_format($investment->amount) }} ریال</td>
                                    <td class="px-6 py-4">{{ $investment->percentage }}%</td>
                                    <td class="px-6 py-4">{{ $investment->investment_date }}</td>
                                    <td class="px-6 py-4">
                                        @if($investment->car->status == 'available')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">موجود</span>
                                        @elseif($investment->car->status == 'sold')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">فروخته شده</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">رزرو</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        هیچ سرمایه‌گذاری انجام نشده است.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection