@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش سرمایه‌گذار: {{ $investor->full_name }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('investors.show', $investor) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            نمایش جزئیات
                        </a>
                        <a href="{{ route('investors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('investors.update', $investor) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نام کامل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام و نام خانوادگی <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name', $investor->full_name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('full_name') border-red-500 @enderror" 
                                   required>
                            @error('full_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- کد ملی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کد ملی <span class="text-red-500">*</span></label>
                            <input type="text" name="national_code" value="{{ old('national_code', $investor->national_code) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('national_code') border-red-500 @enderror" 
                                   required>
                            @error('national_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تلفن -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تلفن <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $investor->phone) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('phone') border-red-500 @enderror" 
                                   required>
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- ایمیل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                            <input type="email" name="email" value="{{ old('email', $investor->email) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('email') border-red-500 @enderror">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- آدرس -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">آدرس</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('address') border-red-500 @enderror">{{ old('address', $investor->address) }}</textarea>
                            @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- خلاصه اطلاعات مالی -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-green-600">اطلاعات مالی</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">کل سرمایه‌گذاری:</span>
                                <span class="block text-xl font-bold text-green-600">{{ number_format($investor->total_invested) }} ریال</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تعداد سرمایه‌گذاری:</span>
                                <span class="block text-xl font-bold text-blue-600">{{ $investor->investments->count() }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ثبت‌نام:</span>
                                <span class="block text-xl font-bold text-purple-600">{{ $investor->created_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('investors.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی سرمایه‌گذار
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection