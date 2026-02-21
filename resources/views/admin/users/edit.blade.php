@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش کاربر: {{ $user->name }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            نمایش کاربر
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نام و نام خانوادگی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام و نام خانوادگی</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name') 
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- ایمیل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror" 
                                   required>
                            @error('email') 
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- رمز عبور جدید (اختیاری) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور جدید (اختیاری)</label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror" 
                                   placeholder="برای تغییر رمز وارد کنید">
                            @error('password') 
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- تکرار رمز عبور جدید -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور جدید</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                   placeholder="تکرار رمز عبور جدید">
                        </div>

                        <!-- نقش‌ها -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-3">نقش‌ها</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($roles as $role)
                                <label class="flex items-center p-4 border rounded-xl hover:bg-gray-50 cursor-pointer transition @if(in_array($role->name, $userRoles)) border-blue-500 bg-blue-50 @endif">
                                    <input type="checkbox" 
                                           name="roles[]" 
                                           value="{{ $role->name }}" 
                                           class="ml-3 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           @if(in_array($role->name, $userRoles)) checked @endif>
                                    <span class="text-sm text-gray-700">
                                        @if($role->name == 'admin') 
                                            <span class="font-bold text-red-600">ادمین</span>
                                        @elseif($role->name == 'manager') 
                                            <span class="font-bold text-blue-600">مدیر</span>
                                        @elseif($role->name == 'investor') 
                                            <span class="font-bold text-green-600">سرمایه‌گذار</span>
                                        @else 
                                            <span class="font-bold text-gray-600">{{ $role->name }}</span>
                                        @endif
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            @error('roles') 
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- اطلاعات اضافی -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4">اطلاعات حساب</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ایجاد:</span>
                                <span class="block font-medium">{{ $user->created_at->format('Y/m/d H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">آخرین به‌روزرسانی:</span>
                                <span class="block font-medium">{{ $user->updated_at->format('Y/m/d H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">وضعیت ایمیل:</span>
                                <span class="block font-medium">
                                    @if($user->email_verified_at)
                                        <span class="text-green-600">تایید شده</span>
                                    @else
                                        <span class="text-red-600">تایید نشده</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 gap-3">
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی کاربر
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection