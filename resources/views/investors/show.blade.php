@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">اطلاعات سرمایه‌گذار</h2>
                    <div>
                        <a href="{{ route('investors.edit', $investor) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            ویرایش
                        </a>
                        <a href="{{ route('investors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            بازگشت
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">نام و نام خانوادگی</div>
                        <div class="text-lg font-bold">{{ $investor->full_name }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کد ملی</div>
                        <div class="text-lg font-bold">{{ $investor->national_code }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">تلفن</div>
                        <div class="text-lg font-bold">{{ $investor->phone }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">ایمیل</div>
                        <div class="text-lg font-bold">{{ $investor->email ?? '—' }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                        <div class="text-sm text-gray-600">آدرس</div>
                        <div class="text-lg">{{ $investor->address ?? '—' }}</div>
                    </div>
                </div>

                <h3 class="text-xl font-bold mt-8 mb-4">سرمایه‌گذاری‌های انجام شده</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">خودرو</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">مبلغ سرمایه‌گذاری</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">درصد</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">تاریخ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">وضعیت خودرو</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($investor->investments as $investment)
                            <tr>
                                <td class="px-6 py-4">{{ $investment->car->title }}</td>
                                <td class="px-6 py-4">{{ number_format($investment->amount) }} ریال</td>
                                <td class="px-6 py-4">{{ $investment->percentage }}%</td>
                                <td class="px-6 py-4">{{ $investment->investment_date }}</td>
                                <td class="px-6 py-4">
                                    @if($investment->car->status == 'available')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">در انتظار فروش</span>
                                    @elseif($investment->car->status == 'sold')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">فروخته شده</span>
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
@endsection