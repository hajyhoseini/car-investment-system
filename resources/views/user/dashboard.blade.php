@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- خوش‌آمدگویی --}}
        <div class="bg-gradient-to-l from-green-600 to-teal-700 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">
                داشبورد شخصی {{ auth()->user()->name }}
            </h1>
            <p class="opacity-90">
                @if(isset($userRole))
                    نقش شما: 
                    @switch($userRole)
                        @case('admin') <span class="bg-red-200 text-red-800 px-2 py-1 rounded">ادمین</span> @break
                        @case('manager') <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded">مدیر</span> @break
                        @case('investor') <span class="bg-green-200 text-green-800 px-2 py-1 rounded">سرمایه‌گذار</span> @break
                        @default <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded">کاربر عادی</span>
                    @endswitch
                @endif
            </p>
        </div>

        @if(isset($investor) && $investor)
            {{-- کارت‌های آمار شخصی --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="text-sm text-gray-600">کل سرمایه‌گذاری</div>
                    <div class="text-2xl font-bold text-green-600">
                        {{ number_format($investor->investments->sum('amount')) }} ریال
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="text-sm text-gray-600">تعداد سرمایه‌گذاری</div>
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $myInvestments->total() }}
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="text-sm text-gray-600">سود دریافتی</div>
                    <div class="text-2xl font-bold text-purple-600">
                        {{ number_format($totalProfit) }} ریال
                    </div>
                </div>
            </div>

            {{-- لیست سرمایه‌گذاری‌های من --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">
                        سرمایه‌گذاری‌های من
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-right py-2">خودرو</th>
                                    <th class="text-right py-2">مبلغ سرمایه‌گذاری</th>
                                    <th class="text-right py-2">درصد</th>
                                    <th class="text-right py-2">وضعیت</th>
                                    <th class="text-right py-2">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myInvestments as $investment)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3">
                                        <a href="{{ route('cars.show', $investment->car) }}" class="text-blue-600 hover:underline">
                                            {{ $investment->car->title }}
                                        </a>
                                    </td>
                                    <td class="py-3">{{ number_format($investment->amount) }} ریال</td>
                                    <td class="py-3">{{ number_format($investment->percentage, 2) }}%</td>
                                    <td class="py-3">
                                        @if($investment->car->status == 'available')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">در انتظار فروش</span>
                                        @elseif($investment->car->status == 'sold')
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">فروخته شده</span>
                                        @elseif($investment->car->status == 'reserved')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">رزرو</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <a href="{{ route('investments.show', $investment) }}" class="text-blue-600 hover:underline">
                                            جزئیات
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">
                                        هیچ سرمایه‌گذاری برای شما ثبت نشده است.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $myInvestments->links() }}
                    </div>
                </div>
            </div>

        @else
            {{-- کاربر سرمایه‌گذار نیست --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-8 text-center">
                <svg class="h-16 w-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="text-xl font-bold text-yellow-800 mb-2">حساب سرمایه‌گذاری فعال نیست</h3>
                <p class="text-yellow-600 mb-6 max-w-lg mx-auto">
                    {{ $message ?? 'شما سرمایه‌گذار نیستید.' }}
                </p>
                
                @if(isset($userRole) && $userRole == 'admin')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-right">
                        <p class="text-blue-800 font-medium mb-2">🔹 شما به عنوان ادمین هستید</p>
                        <p class="text-blue-600 text-sm">برای مشاهده اطلاعات سرمایه‌گذاری به عنوان سرمایه‌گذار، می‌توانید:</p>
                        <ul class="text-blue-600 text-sm list-disc list-inside mt-2">
                            <li>با حساب سرمایه‌گذار (که person_id دارد) وارد شوید</li>
                            <li>یا از طریق پنل مدیریت، یک سرمایه‌گذار برای خود ایجاد کنید</li>
                        </ul>
                    </div>
                @endif
                
                <div class="mt-6 flex gap-4 justify-center">
                    <a href="{{ route('profile.edit') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        تکمیل اطلاعات
                    </a>
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        بازگشت به داشبورد اصلی
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection